<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CategoryImport;
use App\Utils\BulkDelete;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Vanilo\Category\Models\Taxonomy;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\Product;
use App\Exports\CategoryExport;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{

    public function bulk_delete(Request $request)
    {
        $response = BulkDelete::perform($request, new Taxon());
        $taxonomies = Taxonomy::all();
        foreach ($taxonomies as $key => $t) {
            $t->delete();
        }
        return $response;
    }

    public function create()
    {
        $categories = Taxon::orderBy('name')->get();
        return view('admin.category.create', compact('categories'));
    }

    public function index(Request $request)
    {
        $categories_for_tableview = Taxon::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('state', $request->status);
            })

            // Created At
            ->when($request->filled('created_at'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->created_at);
            })

            // Updated At
            ->when($request->filled('updated_at'), function ($query) use ($request) {
                $query->whereDate('updated_at', $request->updated_at);
            })

            // From Date
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })

            // To Date
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            });

        // Sorting
        switch ($request->get('sort')) {
            case 'created_at_asc':
                $categories_for_tableview->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $categories_for_tableview->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $categories_for_tableview->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $categories_for_tableview->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $categories_for_tableview->orderBy('created_at', 'desc');
                break;
        }

        $categories_for_tableview = $categories_for_tableview->get();

        // Category tree (optionally filtered)
        $categories = Taxon::with('children')
            ->whereNull('parent_id')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->get();

        return view('admin.category.index', compact(
            'categories',
            'categories_for_tableview'
        ));
    }

    public function edit($id)
    {
        $category = Taxon::findOrFail($id);
        $categories = Taxon::where('id', '!=', $id)->get();
        return view('admin.category.edit', compact('category', 'categories'));
    }

    public function delete($id)
    {
        $category = Taxon::findOrFail($id);

        // Prevent deletion if the category has child categories
        if ($category->children()->exists()) {
            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->error('Cannot delete this category because it has child categories.!');
            return redirect()->back();
        }

        $category->delete();
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Category deleted successfully..!');
        return redirect()->route('admin.category.index');

    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'parent_id' => 'nullable|exists:taxons,id',
            'image' => 'nullable|image',
        ]);

        $taxon = Taxon::findOrFail($id);

        // Update taxonomy name if needed
        $taxonomy = Taxonomy::firstOrCreate([
            'name' => $data['name'],
        ]);

        $taxon->update([
            'taxonomy_id' => $taxonomy->id,
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
        ]);

        if ($request->hasFile('image')) {

            // Delete old image
            if ($taxon->image_path) {
                $oldImage = str_replace('storage/', '', $taxon->image_path);

                if (Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // Store new image
            $imagePath = $request->file('image')
                ->store('categories/images', 'public');

            $taxon->update([
                'image_path' => 'storage/' . $imagePath,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Category updated successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'parent_id' => 'nullable|exists:taxons,id',
            'image' => 'nullable|image',
            // 'banner' => 'nullable|image',
        ]);

        $taxonomy = Taxonomy::firstOrCreate([
            'name' => $data['name'],
        ]); //electronics

        $taxon = Taxon::create([
            'taxonomy_id' => $taxonomy->id,
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('categories/images', 'public');

            $taxon->update([
                'image_path' => 'storage/' . $imagePath,
            ]);
        }

        // if ($request->hasFile('banner')) {
        //     $banner = $request->file('banner')
        //         ->store('categories/banners', 'public');
        // }

        // Save extra fields in meta_data column if available
        // $taxon->update([
        //     'meta_data' => [
        //         'logo' => $logo,
        //         'banner' => $banner,
        //     ]
        // ]);

        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Category created successfully..!');
        return redirect()
            ->route('admin.category.index');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new CategoryImport(), $request->file('file'));
        return back()->with('success', 'Categories imported successfully.');
    }

    public function exportToPdf()
    {
        $categories = Taxon::all();
        $pdf = Pdf::loadView('admin.category.pdf', compact('categories'))->setPaper('a4', 'portrait');
        return $pdf->download('categorGradient Able ♥ crafted by Team Codedthemesies.pdf');
        // To display in browser instead:
        // return $pdf->stream('products.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(
            new CategoryExport(),
            'categories_' . now()->format('Y_m_d_H_i_s') . '.xlsx'
        );
    }

    public function downloadExcelTemplate()
    {
        $filePath = public_path('admin-assets/files/category.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, 'category_template.xlsx');
    }

}
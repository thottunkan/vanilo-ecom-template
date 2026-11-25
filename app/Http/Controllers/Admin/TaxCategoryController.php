<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vanilo\Taxes\Models\TaxCategory;
use Vanilo\Taxes\Models\TaxCategoryType;
use Vanilo\Taxes\Models\TaxRate;

class TaxCategoryController extends Controller
{

    public function bulk_delete(Request $request)
    {
        $response = BulkDelete::perform($request, new TaxCategory());
        $taxonomies = TaxRate::all();
        foreach ($taxonomies as $key => $t) {
            $t->delete();
        }
        return $response;
    }

    public function index(Request $request)
    {
        $tax_categories = TaxCategory::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('state', $request->status); // Change to 'status' if applicable
            })

            // Created At
            ->when($request->filled('created_at'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->created_at);
            })

            // Updated At
            ->when($request->filled('updated_at'), function ($query) use ($request) {
                $query->whereDate('updated_at', $request->updated_at);
            })

            // Date Range
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })

            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            });

        // Sorting
        switch ($request->get('sort')) {

            case 'created_at_asc':
                $tax_categories->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $tax_categories->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $tax_categories->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $tax_categories->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $tax_categories->orderBy('created_at', 'desc');
                break;
        }

        $tax_categories = $tax_categories->get();

        return view('admin.taxcategory.index', compact('tax_categories'));
    }

    public function edit($id)
    {
        $types = TaxCategoryType::cases();
        $tax_category = DB::table('tax_categories')
            ->where('id', $id)
            ->first();

        return view('admin.taxcategory.edit', compact('tax_category', 'types'));
    }

    public function create()
    {
        $types = TaxCategoryType::cases();
        return view('admin.taxcategory.create', compact('types'));
    }

    public function store(Request $request)
    {
        TaxCategory::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Tax category created successfully..!');
        return redirect()->route('admin.taxcategory.index');

    }

    public function update(Request $request, $id)
    {
        $tax_category = TaxCategory::findOrFail($id);

        $tax_category->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Tax category updated successfully..!');

        return redirect()->route('admin.taxcategory.index');
    }

    public function delete($id)
    {
        $tax_category = TaxCategory::find($id);
        $tax_category->delete();
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Tax category deleted successfully..!');
        return redirect()->route('admin.taxcategory.index');
    }
}

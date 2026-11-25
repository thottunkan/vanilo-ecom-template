<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class AdminBrandController extends Controller
{

    public function bulk_delete(Request $request){
        return BulkDelete::perform($request, new Brand());
    }

    public function index(Request $request)
    {
        $brands = Brand::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                        // ->orWhere('description', 'like', "%{$search}%")
                        // ->orWhere('slug', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('state', $request->status); // Change 'state' if your column is 'status'
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
                $brands->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $brands->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $brands->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $brands->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $brands->orderBy('created_at', 'desc');
                break;
        }

        $brands = $brands->get();

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        return view('admin.brands.edit', compact('brand'));
    }

    public function delete($id)
    {
        $brand = Brand::find($id);
        $brand->delete();
        return redirect()->route('admin.brand.index');
    }

    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            Brand::create($data);

            return redirect()
                ->route('admin.brand.index') // change to your route name
                ->with('success', 'Brand created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create brand. ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand = Brand::find($id);
        $brand->update($data);
        return redirect()->route('admin.brand.index');

    }



}

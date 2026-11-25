<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Konekt\Address\Models\Zone;
use Vanilo\Taxes\Models\TaxRate;
use Vanilo\Taxes\Models\TaxCategory;

class TaxRateController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new TaxRate());
    }

    public function index(Request $request)
    {
        $tax_rates = TaxRate::with('taxCategory')

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('amount', 'like', "%{$search}%")
                        ->orWhereHas('taxCategory', function ($category) use ($search) {
                            $category->where('name', 'like', "%{$search}%");
                        });
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('state', $request->status); // Change to 'status' if needed
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
                $tax_rates->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $tax_rates->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $tax_rates->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $tax_rates->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $tax_rates->orderBy('created_at', 'desc');
                break;
        }

        $tax_rates = $tax_rates->get();

        return view('admin.taxrate.index', compact('tax_rates'));
    }

    public function create()
    {
        $tax_categories = TaxCategory::all();
        $zones = Zone::all();
        return view('admin.taxrate.create', compact('tax_categories', 'zones'));
    }

    public function get_taxrates($category_id)
    {

        $tax_rates = TaxRate::where('tax_category_id', $category_id)->get();
        return response()->json([
            'data' => $tax_rates,
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tax_category_id' => 'required|exists:tax_categories,id',
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'zone_id' => 'nullable|integer'
        ]);

        // TaxRate::create([
        //     'tax_category_id' => $request->tax_category_id,
        //     'name'            => $request->name,
        //     'amount'          => $request->amount,
        // ]);
        TaxRate::create([
            'name' => $data['name'],
            'tax_category_id' => $data['tax_category_id'],
            'zone_id' => $data['zone_id'],
            'rate' => $data['rate'],
            'calculator' => 'india',

            'configuration' => [
                'rate' => (float) $request->rate,
                'title' => $request->name,
                'included' => false,
            ],
        ]);

        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Tax rate created successfully..!');

        return redirect()->route('admin.taxrate.index');
    }

    public function edit($id)
    {
        $tax_rate = TaxRate::findOrFail($id);
        $tax_categories = TaxCategory::all();
        $zones = Zone::all();
        return view('admin.taxrate.edit', compact('tax_rate', 'tax_categories', 'zones'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'tax_category_id' => 'required|exists:tax_categories,id',
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
        ]);

        $tax_rate = TaxRate::findOrFail($id);

        $tax_rate->update([
            'tax_category_id' => $data['tax_category_id'],
            'name' => $data['name'],
            'rate' => $data['rate'],
        ]);

        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Tax rate updated successfully..!');

        return redirect()->route('admin.taxrate.index');
    }

    public function delete($id)
    {
        $tax_rate = TaxRate::findOrFail($id);
        $tax_rate->delete();

        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Tax rate deleted successfully..!');

        return redirect()->route('admin.taxrate.index');
    }
}
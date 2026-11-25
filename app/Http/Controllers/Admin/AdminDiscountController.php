<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class AdminDiscountController extends Controller
{
    /**
     * Display all discounts.
     */
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Discount());
    }
    public function index(Request $request)
    {
        $discounts = Discount::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
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
                $discounts->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $discounts->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $discounts->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $discounts->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $discounts->orderBy('created_at', 'desc');
                break;
        }

        $discounts = $discounts->get();

        return view('admin.discount.index', compact('discounts'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.discount.create');
    }

    public function edit($id)
    {
        $discount = Discount::findOrFail($id);

        return view('admin.discount.edit', compact('discount'));
    }

    /**
     * Store a new discount.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'option_key' => 'nullable|string|max:255',
            'option_value' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        Discount::create($validated);

        return redirect()
            ->route('admin.discount.index')
            ->with('success', 'Discount created successfully.');
    }

    /**
     * Update an existing discount.
     */
    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'option_key' => 'nullable|string|max:255',
            'option_value' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        $discount->update($validated);

        return redirect()
            ->route('admin.discount.index')
            ->with('success', 'Discount updated successfully.');
    }

    /**
     * Delete a discount.
     */
    public function delete($id)
    {
        $discount = Discount::findOrFail($id);

        $discount->delete();

        return redirect()
            ->route('admin.discount.index')
            ->with('success', 'Discount deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vanilo\Foundation\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $payment_methods = PaymentMethod::query()

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
                $payment_methods->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $payment_methods->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $payment_methods->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $payment_methods->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $payment_methods->orderBy('created_at', 'desc');
                break;
        }

        $payment_methods = $payment_methods->get();

        return view('admin.payment.methods.index', compact('payment_methods'));
    }

    public function create()
    {
        return view('admin.payment.methods.create');
    }

    public function store(Request $request)
    {
        if ($request->has('configuration') && is_string($request->configuration)) {
            $request->merge([
                'configuration' => json_decode($request->configuration, true)
            ]);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'zone_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'gateway' => 'required|string|max:255',
            'configuration' => 'nullable|array',
            'is_enabled' => 'nullable|boolean',
        ]);

        $data['configuration'] = $data['configuration'] ?? [];
        $data['is_enabled'] = $request->boolean('is_enabled');

        PaymentMethod::create($data);

        return redirect()
            ->route('admin.payment-method.index')
            ->with('success', 'Payment method created successfully.');
    }

    public function edit($id)
    {
        $payment_method = PaymentMethod::findOrFail($id);

        return view('admin.payment.methods.edit', compact('payment_method'));
    }

    public function update(Request $request, $id)
    {
        $payment_method = PaymentMethod::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'zone_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'gateway' => 'required|string|max:255',
            'configuration' => 'nullable|array',
            'is_enabled' => 'nullable|boolean',
        ]);

        $data['configuration'] = $data['configuration'] ?? [];
        $data['is_enabled'] = $request->boolean('is_enabled');

        $payment_method->update($data);

        return redirect()
            ->route('admin.payment-method.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function delete($id)
    {
        $payment_method = PaymentMethod::findOrFail($id);

        $payment_method->delete();

        return redirect()
            ->route('admin.payment-method.index')
            ->with('success', 'Payment method deleted successfully.');
    }
}
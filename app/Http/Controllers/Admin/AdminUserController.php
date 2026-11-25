<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Imports\UserImport;
use App\Models\User;
use App\Utils\BulkDelete;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class AdminUserController extends Controller
{

    public function bulk_delete(Request $request)
    {
        $flag = [
            'colname' => 'role',
            'colvalue' => 'customer'
        ];
        return BulkDelete::perform($request, new User(), $flag);
    }

    public function index(Request $request)
    {
        $customers = User::where('role', 'customer')

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status);
            })

            // Customer Type
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })

            // Gender
            ->when($request->filled('gender'), function ($query) use ($request) {
                $query->where('gender', $request->gender);
            })

            // Language
            ->when($request->filled('language'), function ($query) use ($request) {
                $query->where('language', $request->language);
            })

            // Currency
            ->when($request->filled('currency'), function ($query) use ($request) {
                $query->where('currency', $request->currency);
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

            case 'name_asc':
                $customers->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $customers->orderBy('name', 'desc');
                break;

            case 'email_asc':
                $customers->orderBy('email', 'asc');
                break;

            case 'email_desc':
                $customers->orderBy('email', 'desc');
                break;

            case 'created_at_asc':
                $customers->orderBy('created_at', 'asc');
                break;

            case 'updated_at_asc':
                $customers->orderBy('updated_at', 'asc');
                break;

            case 'updated_at_desc':
                $customers->orderBy('updated_at', 'desc');
                break;

            case 'login_count_asc':
                $customers->orderBy('login_count', 'asc');
                break;

            case 'login_count_desc':
                $customers->orderBy('login_count', 'desc');
                break;

            case 'last_login_desc':
                $customers->orderBy('last_login_at', 'desc');
                break;

            case 'last_login_asc':
                $customers->orderBy('last_login_at', 'asc');
                break;

            case 'created_at_desc':
            default:
                $customers->orderBy('created_at', 'desc');
                break;
        }

        $customers = $customers->get();

        return view('admin.customer.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
            'language' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
            'password' => 'required|confirmed|min:8',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'customer';
        $validated['type'] = 'client';

        User::create($validated);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit($id)
    {
        $customer = User::find($id);
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, User $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:50',
            'bio' => 'nullable|string',
            'language' => 'nullable|string|max:10',
            'currency' => 'nullable|string|max:10',
            'is_active' => 'required|boolean',
            'password' => 'nullable|confirmed|min:8',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        $customer->delete();

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function deactivate(User $customer)
    {
        $customer->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Customer deactivated successfully.');
    }

    public function activate(User $customer)
    {
        $customer->update([
            'is_active' => true,
        ]);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Customer activated successfully.');
    }

    public function exportToPdf()
    {
        $users = User::all();
        $pdf = Pdf::loadView('admin.customer.pdf', compact('users'))->setPaper('a3', 'landscape');

        return $pdf->download('customers.pdf');
        // To display in browser instead:
        // return $pdf->stream('products.pdf');
    }

    public function exportToExcel()
    {
        return Excel::download(
            new UserExport(),
            'customers_' . now()->format('Y_m_d_H_i_s') . '.xlsx'
        );
    }

    public function importFromExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new UserImport(), $request->file('file'));
        return back()->with('success', 'Categories imported successfully.');
    }

    public function importFromCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new UserImport(), $request->file('file'));
        return back()->with('success', 'Categories imported successfully.');
    }

    public function downloadExcelTemplate()
    {
        $filePath = public_path('admin-assets/files/customer_template.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, 'customer_template.xlsx');
    }


}
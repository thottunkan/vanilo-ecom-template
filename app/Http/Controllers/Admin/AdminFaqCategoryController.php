<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class AdminFaqCategoryController extends Controller
{
    /**
     * Display a listing of FAQ categories.
     */
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new FaqCategory());
    }
    
    public function index(Request $request)
    {
        $query = FaqCategory::query();

        // Search
        $query->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->input('search');

            $query->where('name', 'like', '%' . $search . '%');
        });

        // Status
        $query->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status', $request->input('status'));
        });

        // Created At
        $query->when($request->filled('created_at'), function ($query) use ($request) {
            $query->whereDate('created_at', $request->input('created_at'));
        });

        // Updated At
        $query->when($request->filled('updated_at'), function ($query) use ($request) {
            $query->whereDate('updated_at', $request->input('updated_at'));
        });

        // From Date
        $query->when($request->filled('from_date'), function ($query) use ($request) {
            $query->whereDate('created_at', '>=', $request->input('from_date'));
        });

        // To Date
        $query->when($request->filled('to_date'), function ($query) use ($request) {
            $query->whereDate('created_at', '<=', $request->input('to_date'));
        });

        // Sorting
        switch ($request->input('sort')) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $query->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $faqcategory = $query->get();

        return view('admin.faqcategory.index', compact('faqcategory'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('admin.faqcategory.create');
    }

    /**
     * Store a new FAQ category.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ]);

        FaqCategory::create($data);

        return redirect()
            ->route('admin.faq-category.index')
            ->with('success', 'FAQ category created successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit($id)
    {
        $faqcategory = FaqCategory::findOrFail($id);

        return view('admin.faqcategory.edit', compact('faqcategory'));
    }

    /**
     * Update an FAQ category.
     */
    public function update(Request $request, $id)
    {
        $faqcategory = FaqCategory::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ]);

        $faqcategory->update($data);

        return redirect()
            ->route('admin.faq-category.index')
            ->with('success', 'FAQ category updated successfully.');
    }

    /**
     * Delete an FAQ category.
     */
    public function delete($id)
    {
        $faqcategory = FaqCategory::findOrFail($id);

        $faqcategory->delete();

        return redirect()
            ->route('admin.faq-category.index')
            ->with('success', 'FAQ category deleted successfully.');
    }
}
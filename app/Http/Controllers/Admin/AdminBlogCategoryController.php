<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class AdminBlogCategoryController extends Controller
{
    /**
     * Display all categories.
     */

    public function bulk_delete(Request $request)
    {
        $response = BulkDelete::perform($request, new BlogCategory());
        $blogs = Blog::all();
        foreach ($blogs as $key => $b) {
            $b->delete();
        }
        return $response;
    }
    
    public function index(Request $request)
    {
        $blog_categories = BlogCategory::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                        // ->orWhere('slug', 'like', "%{$search}%")
                        // ->orWhere('description', 'like', "%{$search}%");
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
                $blog_categories->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $blog_categories->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $blog_categories->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $blog_categories->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $blog_categories->orderBy('created_at', 'desc');
                break;
        }

        $blog_categories = $blog_categories->get();

        return view('admin.blogcategory.index', compact('blog_categories'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        return view('admin.blogcategory.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
        ]);

        BlogCategory::create($validated);

        return redirect()
            ->route('admin.blog-category.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);

        return view('admin.blogcategory.edit', compact('category'));
    }

    /**
     * Update the category.
     */
    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.blog-category.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete the category.
     */
    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);

        $category->delete();

        return redirect()
            ->route('admin.blog-category.index')
            ->with('success', 'Category deleted successfully.');
    }
}
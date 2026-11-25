<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminBlogController extends Controller
{

    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Blog());
    }
    public function index(Request $request)
    {
        $blogs = Blog::with('category')
            ->when($request->search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            })
            ->when($request->created_at, function ($query, $date) {
                $query->whereDate('created_at', $date);
            })
            ->when($request->updated_at, function ($query, $date) {
                $query->whereDate('updated_at', $date);
            })
            ->when($request->from_date, function ($query, $date) {
                $query->whereDate('created_at', '>=', $date);
            })
            ->when($request->to_date, function ($query, $date) {
                $query->whereDate('created_at', '<=', $date);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->sort, function ($query, $sort) {
                match ($sort) {
                    'created_at_desc' => $query->orderBy('created_at', 'desc'),
                    'created_at_asc' => $query->orderBy('created_at', 'asc'),
                    'updated_at_desc' => $query->orderBy('updated_at', 'desc'),
                    'name_asc' => $query->orderBy('title', 'asc'),
                    'name_desc' => $query->orderBy('title', 'desc'),
                    default => $query->latest(),
                };
            }, function ($query) {
                $query->latest();
            })
            ->get();

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $blog_categories = BlogCategory::all();
        return view('admin.blogs.create', compact('blog_categories'));
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $blog_categories = BlogCategory::all();

        return view('admin.blogs.edit', compact('blog', 'blog_categories'));
    }

    public function delete($id)
    {
        $blog = Blog::find($id);
        $blog->delete();
        return redirect()->route('admin.blog.index');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'blog_content' => 'required|string',
                'blog_category_id' => 'required|exists:blog_categories,id',
                'status'=> 'required|string'
            ]);

            // Upload banner image
            if ($request->hasFile('banner_image')) {
                $data['banner_image_path'] = $request->file('banner_image')
                    ->store('blog-banners', 'public');
            }

            // Assign logged-in user
            $data['user_id'] = Auth::id();

            $blog = Blog::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Blog created successfully.',
                'data' => $blog,
            ], 201);
            //return redirect()->route('admin.blog.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);

            $data = $request->validate([
                'title' => 'required|string|max:255',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'blog_content' => 'required|string',
                'blog_category_id' => 'required|exists:blog_categories,id',
                'status'=> 'required|string'
            ]);

            // Upload new banner image
            if ($request->hasFile('banner_image')) {

                // Delete old image if it exists
                if ($blog->banner_image_path && Storage::disk('public')->exists($blog->banner_image_path)) {
                    Storage::disk('public')->delete($blog->banner_image_path);
                }

                $data['banner_image_path'] = $request->file('banner_image')
                    ->store('blog-banners', 'public');
            }

            $blog->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Blog updated successfully.',
                'data' => $blog,
            ], 201);

            // return redirect()
            //     ->route('admin.blog.index')
            //     ->with('success', 'Blog updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

}

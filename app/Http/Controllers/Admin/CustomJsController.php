<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customjs;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class CustomJsController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Customjs());
    }
    public function index(Request $request)
    {
        $list_customjs = Customjs::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('script_name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                    // ->orWhere('script', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status); // Change to 'status' if applicable
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
                $list_customjs->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $list_customjs->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $list_customjs->orderBy('script_name', 'asc');
                break;

            case 'name_desc':
                $list_customjs->orderBy('script_name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $list_customjs->orderBy('created_at', 'desc');
                break;
        }

        $list_customjs = $list_customjs->get();

        return view('admin.customjs.index', compact('list_customjs'));
    }

    public function create()
    {
        return view('admin.customjs.create');
    }

    // public function edit($id)
    // {
    //     $custom_js = Customjs::find($id);
    //     return view('admin.customjs.edit', compact('custom_js'));
    // }

    public function edit($id)
    {
        $custom_js = Customjs::findOrFail($id);

        // Release stale locks after 10 minutes
        if (
            $custom_js->locked_at &&
            $custom_js->locked_at->lt(now()->subMinutes(10))
        ) {
            $custom_js->update([
                'locked_by' => null,
                'locked_at' => null,
            ]);
        }

        // Someone else is editing
        if (
            $custom_js->locked_by &&
            $custom_js->locked_by != auth()->id()
        ) {
            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->error('This JavaScript is currently being edited by another user.');

            return redirect()
                ->route('admin.custom-js.index');
        }

        // Lock for current user
        $custom_js->update([
            'locked_by' => auth()->id(),
            'locked_at' => now(),
        ]);

        return view('admin.customjs.edit', compact('custom_js'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'script_name' => 'required|string|max:255',
                'script' => 'required|string',
                'is_active' => 'nullable|in:active,inactive',
            ]);

            $data['slug'] = \Str::slug($data['script_name']);

            $customJs = CustomJs::create($data);

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Custom JS created successfully.',
            //     'data' => $customJs,
            // ], 201);
            return redirect()->route('admin.custom-js.index');

        } catch (\Exception $e) {
            // return response()->json([
            //     'success' => false,
            //     'message' => 'Failed to create custom JS.',
            //     'error' => $e->getMessage(),
            // ], 500);
            return redirect()->route('admin.custom-js.index');
        }
    }

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $data = $request->validate([
    //             'script_name' => 'required|string|max:255',
    //             'script' => 'required|string',
    //             'is_active' => 'nullable|in:active,inactive',
    //         ]);

    //         $customJs = CustomJs::findOrFail($id);

    //         $data['slug'] = \Str::slug($data['script_name']);

    //         $customJs->update($data);
    //         return redirect()->route('admin.custom-js.edit', $id);
    //         // return response()->json([
    //         //     'success' => true,
    //         //     'message' => 'Custom JS updated successfully.',
    //         //     'data' => $customJs,
    //         // ], 200);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to update custom JS.',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'script_name' => 'required|string|max:255',
            'is_active' => 'required|in:active,inactive',
            'script' => 'required|string',
        ]);

        $custom_js = Customjs::findOrFail($id);

        // Only the user who owns the lock can save
        if ($custom_js->locked_by != auth()->id()) {
            return redirect()
                ->route('admin.custom-js.index')
                ->with('error', 'You do not own the editing lock.');
        }

        $custom_js->update([
            'script_name' => $validated['script_name'],
            'is_active' => $validated['is_active'],
            'script' => $validated['script'],
            'locked_by' => null,
            'locked_at' => null,
        ]);

        return redirect()
            ->route('admin.custom-js.index')
            ->with('success', 'Custom JavaScript updated successfully.');
    }

    public function delete($id)
    {
        try {
            $customJs = CustomJs::findOrFail($id);

            $customJs->delete();

            return response()->json([
                'success' => true,
                'message' => 'Custom JS deleted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete custom JS.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function unlock($id)
    {
        $custom_js = Customjs::findOrFail($id);

        if ($custom_js->locked_by == auth()->id()) {
            $custom_js->update([
                'locked_by' => null,
                'locked_at' => null,
            ]);
        }

        return redirect()->route('admin.custom-js.index');
    }

}

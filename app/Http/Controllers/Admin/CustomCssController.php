<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customcss;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomCssController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Customcss());
    }
    public function index(Request $request)
    {
        $list_customcss = Customcss::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('style_name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('css', 'like', "%{$search}%");
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
                $list_customcss->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $list_customcss->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $list_customcss->orderBy('style_name', 'asc');
                break;

            case 'name_desc':
                $list_customcss->orderBy('style_name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $list_customcss->orderBy('created_at', 'desc');
                break;
        }

        $list_customcss = $list_customcss->get();

        return view('admin.customcss.index', compact('list_customcss'));
    }

    public function create()
    {
        return view('admin.customcss.create');
    }

    public function edit($id)
    {
        $customCss = Customcss::findOrFail($id);

        // Release stale locks after 10 minutes
        if (
            $customCss->locked_at &&
            $customCss->locked_at->lt(now()->subMinutes(10))
        ) {

            $customCss->update([
                'locked_by' => null,
                'locked_at' => null,
            ]);
        }

        // Someone else is editing
        if (
            $customCss->locked_by &&
            $customCss->locked_by != auth()->id()
        ) {
            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->error('This CSS is currently being edited by another user.');
            return redirect()
                ->route('admin.custom-css.index');
            // ->with('error', 'This CSS is currently being edited by another user.');
        }

        // Lock for current user
        $customCss->update([
            'locked_by' => auth()->id(),
            'locked_at' => now(),
        ]);

        return view('admin.customcss.edit', compact('customCss'));
    }
    
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'style_name' => 'required|string|max:255',
                'css' => 'required|string',
                'is_active' => 'nullable|in:active,inactive',
            ]);

            $data['slug'] = \Illuminate\Support\Str::slug($data['style_name']);
            //$data['is_active'] = $data['is_active'] ?? 'active';

            CustomCss::create($data);

            return redirect()
                ->route('admin.custom-css.index')
                ->with('success', 'Custom CSS created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create custom CSS. ' . $e->getMessage());
        }
    }

    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'style_name' => 'required|string|max:255',
    //         'is_active' => 'required|in:active,inactive',
    //         'css' => 'required|string',
    //     ]);

    //     $customCss = Customcss::find($id);
    //     $customCss->update($validated);

    //     return redirect()
    //         ->route('admin.custom-css.index')
    //         ->with('success', 'Custom CSS updated successfully.');
    // }
    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'style_name' => 'required|string|max:255',
    //         'is_active' => 'required|in:active,inactive',
    //         'css' => 'required|string',
    //         'updated_at' => 'required',
    //     ]);

    //     $customCss = Customcss::findOrFail($id);

    //     // Check if another user has modified the record
    //     if ($customCss->updated_at->ne(Carbon::parse($request->updated_at))) {
    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->withErrors([
    //                 'error' => 'This CSS has been modified by another user. Please refresh the page and try again.'
    //             ]);
    //     }

    //     $customCss->update([
    //         'style_name' => $validated['style_name'],
    //         'is_active' => $validated['is_active'],
    //         'css' => $validated['css'],
    //     ]);

    //     return redirect()
    //         ->route('admin.custom-css.index')
    //         ->with('success', 'Custom CSS updated successfully.');
    // }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'style_name' => 'required|string|max:255',
            'is_active' => 'required|in:active,inactive',
            'css' => 'required|string',
        ]);

        $customCss = Customcss::findOrFail($id);

        // Only the user who owns the lock can save
        if ($customCss->locked_by != auth()->id()) {
            return redirect()
                ->route('admin.custom-css.index')
                ->with('error', 'You do not own the editing lock.');
        }

        $customCss->update([
            'style_name' => $validated['style_name'],
            'is_active' => $validated['is_active'],
            'css' => $validated['css'],
            'locked_by' => null,
            'locked_at' => null,
        ]);

        return redirect()
            ->route('admin.custom-css.index')
            ->with('success', 'Custom CSS updated successfully.');
    }

    public function unlock($id)
    {
        $customCss = Customcss::findOrFail($id);

        if ($customCss->locked_by == auth()->id()) {
            $customCss->update([
                'locked_by' => null,
                'locked_at' => null,
            ]);
        }

        return redirect()->route('admin.custom-css.index');
    }

    public function delete($id)
    {

    }
}

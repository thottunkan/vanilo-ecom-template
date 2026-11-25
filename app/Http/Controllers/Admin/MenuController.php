<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Menu());
    }
    public function index(Request $request)
    {
        $menus = Menu::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        // ->orWhere('title', 'like', "%{$search}%")
                        // ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status); // Change to 'status' if applicable
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
                $menus->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $menus->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $menus->orderBy('title', 'asc');
                break;

            case 'name_desc':
                $menus->orderBy('title', 'desc');
                break;

            case 'created_at_desc':
                $menus->orderBy('created_at', 'desc');
                break;

            default:
                // Default ordering
                $menus->orderBy('order', 'asc');
                break;
        }

        $menus = $menus->get();

        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function edit($id)
    {
        $menu = Menu::find($id);
        return view('admin.menus.edit', compact('menu'));
    }

    public function delete(Menu $menu)
    {
        $menu->delete();
        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'status' => 'required|string'
        ]);
        Menu::create($data);
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Menu created successfully..!');
        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu created successfully.');
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'status' => 'required|string'
        ]);
        $menu = Menu::find($id);
        $menu->update($validated);
        notyf()
            ->position('x', 'right')
            ->position('y', 'top')
            ->success('Menu updated successfully..!');
        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu updated successfully.');
    }

    public function updateOrder(Request $request)
    {
        try {
            foreach ($request->order as $item) {
                Menu::where('id', $item['id'])->update([
                    'order' => $item['position']
                ]);
            }
            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->success('Order updated successfully..!');
            return response()->json([
                'success' => true,
                'message' => 'Menu order updated successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Menu order.'
            ]);
        }

    }
}

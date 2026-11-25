<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PolicyController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Policy());
    }

    public function index(Request $request)
    {
        $policies = Policy::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('content', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
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
                $policies->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $policies->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $policies->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $policies->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
                $policies->orderBy('created_at', 'desc');
                break;

            default:
                // Original default ordering
                $policies->orderBy('order', 'asc');
                break;
        }

        $policies = $policies->get();

        return view('admin.policies.index', compact('policies'));
    }

    public function create()
    {
        return view('admin.policies.create');
    }

    public function edit($id)
    {
        $policy = Policy::find($id);
        return view('admin.policies.edit', compact('policy'));
    }

    public function delete($id)
    {
        Policy::find($id)->delete();
        return redirect()->back();
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string',
                'content' => 'required',
                'status' => 'required|string'
            ]);
            $data['slug'] = Str::slug($data['title']);
            Policy::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Policy created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string',
                'content' => 'required',
                'status' => 'required|string'
            ]);

            $policy = Policy::find($id);

            $data['slug'] = Str::slug($data['title']);

            $policy->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Policy updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateOrder(Request $request)
    {
        try {

            foreach ($request->order as $item) {
                Policy::where('id', $item['id'])->update([
                    'order' => $item['position']
                ]);
            }

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->success('Policy order updated successfully..!');

            return response()->json([
                'success' => true,
                'message' => 'Policy order updated successfully.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Unable to update policy order.',
                'error' => $e->getMessage()
            ], 500);

        }
    }
}

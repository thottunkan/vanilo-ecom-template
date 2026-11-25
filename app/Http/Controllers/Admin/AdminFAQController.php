<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\FAQ;
use App\Models\FaqCategory;
use App\Services\AdminServices\FAQService;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;

class AdminFAQController extends Controller
{
    protected $faqService;

    public function __construct(FAQService $faqService)
    {
        $this->faqService = $faqService;
    }

    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new FAQ());
    }

    public function index(Request $request)
    {
        $faqs = FAQ::query()

            // Search
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('faq_qn', 'like', "%{$search}%")
                        ->orWhere('faq_ans', 'like', "%{$search}%");
                    // ->orWhere('title', 'like', "%{$search}%");
                });
            })

            // Status
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status); // Change to 'status' if needed
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
                $faqs->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $faqs->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $faqs->orderBy('question', 'asc');
                break;

            case 'name_desc':
                $faqs->orderBy('question', 'desc');
                break;

            case 'created_at_desc':
            default:
                $faqs->orderBy('created_at', 'desc');
                break;
        }

        $faqs = $faqs->get();

        return view('admin.faq.index', compact('faqs'));
    }

    public function create()
    {
        $faqcategory = FaqCategory::all();
        return view('admin.faq.create', compact('faqcategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'faq_qn' => 'required|string|max:255',
            'faq_ans' => 'required|string',
            'banner_image' => 'nullable|image|max:2048',
            'category_id' => 'required|integer',
            'status' => 'required|string'
        ]);

        $this->faqService->save($request);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit($id)
    {
        $faq = $this->faqService->findById($id);
        $faqcategory = FaqCategory::all();
        return view('admin.faq.edit', compact('faq', 'faqcategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'faq_qn' => 'required|string|max:255',
            'faq_ans' => 'required|string',
            'banner_image' => 'nullable|image|max:2048',
            'status' => 'required|string',
            'category_id' => 'required|integer'
        ]);

        $this->faqService->update($request, $id);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function delete($id)
    {
        $this->faqService->delete($id);

        return redirect()->route('admin.faq.index')
            ->with('success', 'FAQ deleted successfully.');
    }
}
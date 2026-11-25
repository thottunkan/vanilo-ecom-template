<?php

namespace App\Services\AdminServices;


use App\Models\FAQ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FAQService
{
    /**
     * Get all FAQs.
     */
    public function getAll()
    {
        return FAQ::all();
    }

    /**
     * Get FAQ by ID.
     */
    public function findById($id)
    {
        return FAQ::findOrFail($id);
    }

    /**
     * Store new FAQ.
     */
    public function save(Request $request)
    {
        $imagePath = null;

        // if ($request->hasFile('banner_image')) {
        //     $imagePath = $request->file('banner_image')
        //         ->store('faq', 'public');
        // }

        return Faq::create([
            'faq_qn' => $request->faq_qn,
            'faq_ans' => $request->faq_ans,
            'status' => $request->status,
            'category_id' => $request->category_id
            // 'banner_image_path' => $imagePath,
        ]);
    }

    /**
     * Update FAQ.
     */
    public function update(Request $request, $id)
    {
        $faq = FAQ::findOrFail($id);

        if ($request->hasFile('banner_image')) {

            if (
                $faq->banner_image_path &&
                Storage::disk('public')->exists($faq->banner_image_path)
            ) {
                Storage::disk('public')->delete($faq->banner_image_path);
            }

            $faq->banner_image_path = $request->file('banner_image')
                ->store('faq', 'public');
        }

        $faq->update([
            'faq_qn' => $request->faq_qn,
            'faq_ans' => $request->faq_ans,
            // 'banner_image_path' => $faq->banner_image_path,
            'status' => $request->status,
            'category_id' => $request->category_id
        ]);

        return $faq;
    }

    /**
     * Delete FAQ.
     */
    public function delete($id)
    {
        $faq = FAQ::findOrFail($id);

        if (
            $faq->banner_image_path &&
            Storage::disk('public')->exists($faq->banner_image_path)
        ) {
            Storage::disk('public')->delete($faq->banner_image_path);
        }

        return $faq->delete();
    }
}
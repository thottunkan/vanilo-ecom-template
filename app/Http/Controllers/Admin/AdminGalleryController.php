<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminGalleryController extends Controller
{
    public function create()
    {
        $gallery = Gallery::first();
        return view('admin.gallery.create', compact('gallery'));
    }

    public function store(Request $request)
    {
        // Get the existing gallery
        $gallery = Gallery::first();
        // Get existing gallery data
        $existingData = $gallery?->images ?? [];
        if (is_string($existingData)) {
            $existingData = json_decode($existingData, true) ?? [];
        }
        if (!is_array($existingData)) {
            $existingData = [];
        }
        // Get submitted data excluding Laravel form fields
        $data = $request->except(['_token', '_method']);
        /*
        |--------------------------------------------------------------------------
        | Image fields
        |--------------------------------------------------------------------------
        */
        $imageFields = [
            'gallery_image1',
            'gallery_image2',
            'gallery_image3',
            'gallery_image4',
            'gallery_image5',
            'gallery_image6',
            'gallery_image7',
            'gallery_image8',
            'gallery_image9',
            'gallery_image10',
            'gallery_image11',
            'gallery_image12',
            'gallery_image13',
            'gallery_image14',
            'gallery_image15',
            'gallery_image16',
        ];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image if it exists
                if (
                    !empty($existingData[$field]) &&
                    Storage::disk('public')->exists($existingData[$field])
                ) {
                    Storage::disk('public')->delete($existingData[$field]);
                }
                // Store new image
                $data[$field] = $request
                    ->file($field)
                    ->store('gallery-images', 'public');
            } else {
                // Keep old image if no new image was uploaded
                if (isset($existingData[$field])) {
                    $data[$field] = $existingData[$field];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save gallery
        |--------------------------------------------------------------------------
        */
        if ($gallery) {
            $gallery->images = $data;
            $gallery->save();
        } else {
            $gallery = Gallery::create([
                'images' => $data,
            ]);
        }
        return redirect()
            ->back()
            ->with('success', 'Gallery updated successfully.');
    }

}
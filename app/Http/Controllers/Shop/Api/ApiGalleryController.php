<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class ApiGalleryController extends Controller
{
    public function getGalleryImages()
    {
        try {
            $gallery = Gallery::first();

            if ($gallery) {
                return response()->json([
                    'status' => true,
                    'message' => 'Record fetched successfully',
                    'data' => $gallery->images
                ], 200);
            }

            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch records',
                'data' => []
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
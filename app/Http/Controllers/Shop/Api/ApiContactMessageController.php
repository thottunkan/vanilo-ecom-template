<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ApiContactMessageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'first_name' => 'nullable|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'status' => 'nullable|string|max:50',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:30',
                'message' => 'required|string|max:5000',
            ]);

            $data['user_agent'] = $request->userAgent();
            $data['user_id'] = auth()->id();

            ContactMessage::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Contact message submitted successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to submit contact message.',
                'error' => $e->getMessage(), // remove in production if needed
            ], 500);
        }
    }

}

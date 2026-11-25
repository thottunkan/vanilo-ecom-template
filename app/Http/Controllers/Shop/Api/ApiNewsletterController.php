<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApiNewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = Newsletter::where('email', $request->email)->first();

        if ($subscriber) {

            if ($subscriber->is_subscribed) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email is already subscribed.'
                ]);
            }

            $subscriber->update([
                'is_subscribed' => true,
                'user_id' => auth()->id(),
                'subscribed_at' => Carbon::now(),
                'unsubscribed_at' => null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Subscription activated successfully.'
            ]);
        }

        Newsletter::create([
            'email' => $request->email,
            'user_id' => auth()->id(),
            'is_subscribed' => true,
            'subscribed_at' => Carbon::now(),
            'unsubscribe_token' => Str::random(60),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Thank you for subscribing.'
        ]);
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe(Request $request)
    {
        try {
            $data = $request->validate([
                'email' => 'required|email',
            ]);

            $subscriber = Newsletter::where('email', $data['email'])->first();

            if (!$subscriber) {
                return response()->json([
                    'status' => false,
                    'message' => 'Subscriber not found.',
                ], 404);
            }

            $subscriber->update([
                'is_subscribed' => false,
                'unsubscribed_at' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Newsletter unsubscribed successfully.',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
            ], 500);
        }
    }
}

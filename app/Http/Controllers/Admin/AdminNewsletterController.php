<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Services\EmailService;
use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Log;

class AdminNewsletterController extends Controller
{
    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new Newsletter());
    }

    public function index()
    {
        $newsletters = Newsletter::all();
        return view('admin.newsletter.index', compact('newsletters'));
    }

    public function create()
    {
        return view('admin.newsletter.create');
    }

    public function edit($id)
    {
        $newsletter = Newsletter::find($id);
        return view('admin.newsletter.edit', compact('newsletter'));
    }

    public function delete($id)
    {

    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'email' => 'required|email',
            'is_subscribed' => 'required|boolean',
        ]);

        try {
            $newsletter = Newsletter::where('user_id', $request->user_id)
                ->where('email', $request->email)
                ->first();

            if ($newsletter) {

                $newsletter->update([
                    'is_subscribed' => $request->is_subscribed,
                ]);

                // Send email only if subscribed
                if ($request->is_subscribed == 1) {
                    EmailService::send(
                        'newsletter-subsciption',
                        $newsletter->email,
                        [
                            'customer_name' => 'Admin Test Email',
                            'website_name' => 'Shop Demo',
                            'verification_url' => url('/'),
                        ]
                    );
                }

                $message = 'Newsletter subscription updated successfully.';

            } else {

                $newsletter = Newsletter::create([
                    'user_id' => $request->user_id,
                    'email' => $request->email,
                    'is_subscribed' => $request->is_subscribed,
                ]);

                // Send email only if subscribed
                if ($request->is_subscribed == 1) {
                    EmailService::send(
                        'newsletter-subsciption',
                        $newsletter->email,
                        [
                            'customer_name' => 'Admin Test Email',
                            'website_name' => 'Shop Demo',
                            'verification_url' => url('/'),
                        ]
                    );
                }

                $message = 'Newsletter subscription created successfully.';
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {

            Log::error('Newsletter save failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to save newsletter subscription.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'email' => 'required|email',
            'is_subscribed' => 'required|boolean',
        ]);

        try {
            $newsletter = Newsletter::findOrFail($id);

            $newsletter->update([
                'user_id' => $request->user_id,
                'email' => $request->email,
                'is_subscribed' => $request->is_subscribed,
            ]);

            // Send email only when subscribed
            if ($newsletter->is_subscribed == 1) {
                EmailService::send(
                    'newsletter-subsciption',
                    $newsletter->email,
                    [
                        'customer_name' => 'Admin Test Email',
                        'website_name' => 'Shop Demo',
                        'verification_url' => url('/'),
                    ]
                );
            } else {

            }

            return redirect()->route('admin.newsletter.index')
                ->with('success', 'Newsletter updated successfully.');

        } catch (\Exception $e) {
            Log::error('Newsletter update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to update newsletter.');
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Services\EmailService;
use Illuminate\Http\Request;
use Log;

class TestEmailController extends Controller
{


    public function sendTestEmail(Request $request)
    {

        try {
            $request->validate([
                'test_email' => 'required|email',
            ]);
          
            EmailService::send(
                'test-email',
                $request->test_email,
                [
                    'customer_name' => 'Admin Test Email',
                    'website_name' => 'Shop Demo',
                    'verification_url' => url('/'),
                ]
            );

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->success('Email sent successfully..!');

            return redirect()->route('admin.email-settings.create'); //->with('success', 'Test email sent successfully.');
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage(), [
                'email' => $request->test_email,
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to send the test email. Please check the logs for details.');
        }
    }

}

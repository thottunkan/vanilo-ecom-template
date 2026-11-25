<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Artisan;
use Illuminate\Http\Request;

class EmailSettingsController extends Controller
{

    public function create()
    {
        $emailSetting = EmailSetting::first();
        return view('admin.settings.email-settings', compact('emailSetting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mail_mailer' => 'required|string|max:50',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        EmailSetting::updateOrCreate(
            ['id' => 1], // Always keep one record
            $data
        );
        Artisan::call('config:clear');
        return back()->with('success', 'Email settings saved successfully.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'mail_mailer' => 'required|string|max:50',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);
        $emailSetting = EmailSetting::firstOrFail();
        $emailSetting->update($data);
        Artisan::call('config:clear');
        return back()->with('success', 'Email settings updated successfully.');
    }

}

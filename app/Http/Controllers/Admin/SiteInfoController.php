<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteInfoController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $siteinfo = SiteInfo::first();
        return view('admin.settings.site-info',compact('siteinfo'));
    }

    public function edit($id)
    {

    }

    public function delete($id)
    {

    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email',
            'company_address' => 'nullable|string|max:255',
            'company_phone_number' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string|max:255',
            'copyright' => 'nullable|string|max:255',
        ]);
     
        $siteInfo = SiteInfo::first();

        if (!$siteInfo) {
            $siteInfo = new SiteInfo();
        }

        // Upload new logo and delete old one
        if ($request->hasFile('site_logo')) {
            if ($siteInfo->logo_path && Storage::disk('public')->exists($siteInfo->logo_path)) {
                Storage::disk('public')->delete($siteInfo->logo_path);
            }
            $data['logo_path'] = $request->file('site_logo')->store('site/logo', 'public');
        }

        // Upload new favicon and delete old one
        if ($request->hasFile('favicon')) {
            if ($siteInfo->favicon_path && Storage::disk('public')->exists($siteInfo->favicon_path)) {
                Storage::disk('public')->delete($siteInfo->favicon_path);
            }

            $data['favicon_path'] = $request->file('favicon')->store('site/favicon', 'public');
        } 


        $siteInfo->fill($data);
        $siteInfo->save();

        return redirect()->back()->with('success', 'Site information saved successfully.');
    }

    public function update(Request $request, $id)
    {

    }
}

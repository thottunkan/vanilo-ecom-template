<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteInfo;
use Illuminate\Http\Request;

class ApiSiteInfoController extends Controller
{
    public function index()
    {
        try {
            $siteinfo = SiteInfo::first();
            if(!$siteinfo){
                return response()->json([
                    'status' => false,
                    'message' => 'Record not found..!',
                    'data' => $siteinfo
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Site information fetched successfully..!',
                'data' => $siteinfo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Unable to fetch site information..!',
                'data' => $siteinfo
            ]);
        }
    }
}

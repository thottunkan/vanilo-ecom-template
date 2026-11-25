<?php

namespace App\Http\Controllers\Shop\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class ApiMenuController extends Controller
{
    public function index()
    {
        try {
            $menus = Menu::all();
            if ($menus->isEmpty()) {
                return response()->json(["status" => false, "message" => "No menu added for this site..!", "data" => $menus]);
            }
            return response()->json(["status" => true, "message" => "Menu fetched successfully..!", "data" => $menus]);
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => "Unable to load menu..! :" . $e->getMessage(), "data" => $menus]);
        }

    }
}

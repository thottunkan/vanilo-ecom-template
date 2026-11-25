<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function create()
    {
        return view('admin.color.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'color_values' => 'nullable|array',
        ]);

        $color = Color::first();

        if ($color) {
            $color->update([
                'color_values' => $request->all(),
            ]);
        } else {
            $color = Color::create([
                'color_values' => $request->all(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Colors saved successfully.',
            'data' => $color,
        ]);
    }
}

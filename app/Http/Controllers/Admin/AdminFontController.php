<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Font;
use Illuminate\Http\Request;

class AdminFontController extends Controller
{
    public function index()
    {
        $font = Font::first();

        $font_values = [];

        if ($font) {
            $font_values = $font->font_values;

            if (is_string($font_values)) {
                $font_values = json_decode($font_values, true) ?? [];
            }
        }

        return view('admin.fonts.index', compact('font_values'));
    }

    public function update(Request $request)
    {
        $font = Font::first();

        $newData = $request->except(['_token', '_method']);

        if (is_string($newData)) {
            $newData = json_decode($newData, true) ?? [];
        }

        if (!$font) {
            $font = Font::create([
                'font_values' => $newData,
            ]);
        } else {
            $existingData = $font->font_values ?? [];

            if (is_string($existingData)) {
                $existingData = json_decode($existingData, true) ?? [];
            }

            $font->update([
                'font_values' => array_replace_recursive($existingData, $newData),
            ]);
        }

        return redirect()
            ->route('admin.font.index')
            ->with('success', 'Font updated successfully.');
    }
}
<?php

namespace App\Imports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Vanilo\Foundation\Models\Taxon;
use Vanilo\Foundation\Models\Taxonomy;

class CategoryImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['name'])) {
            return null;
        }

        // Find taxonomy
        $taxonomy = Taxonomy::firstOrCreate(
            [
                'name' => trim($row['taxonomy']),
            ],
            [
                'slug' => Str::slug($row['taxonomy']),
                'image_path' => null,
            ]
        );

        // Find parent taxon (if any)
        $parent = null;

        if (!empty($row['parent_category'])) {
            $parent = Taxon::where('taxonomy_id', $taxonomy->id)
                ->where('name', trim($row['parent_category']))
                ->first();
        }

        return Taxon::updateOrCreate(
            [
                'taxonomy_id' => $taxonomy->id,
                'slug' => !empty($row['slug'])
                    ? trim($row['slug'])
                    : Str::slug($row['name']),
            ],
            [
                'parent_id'        => $parent?->id,
                'priority'         => $row['priority'] ?? 0,
                'name'             => trim($row['name']),
                'subtitle'         => $row['subtitle'] ?? null,
                'excerpt'          => $row['excerpt'] ?? null,
                'description'      => $row['description'] ?? null,
                'ext_title'        => $row['meta_title'] ?? null,
                'meta_keywords'    => $row['meta_keywords'] ?? null,
                'meta_description' => $row['meta_description'] ?? null,
                'image_path'       => $row['image_path'] ?? null,
            ]
        );
    }
}
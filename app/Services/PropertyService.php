<?php

namespace App\Services;

use Vanilo\Properties\Models\Property;

class PropertyService
{
    public function assignProperties($model, array $values): void
    {
        $properties = [];

        foreach ($values as $name => $value) {
            if (!empty($value)) {

                Property::firstOrCreate(
                    [
                        'name' => $name,
                        'slug' => $name,
                    ],
                    [
                        'type' => 'text',
                    ]
                );

                $properties[$name] = $value;
            }
        }

        if (!empty($properties)) {
            $model->assignPropertyValues($properties);
        }
    }
}
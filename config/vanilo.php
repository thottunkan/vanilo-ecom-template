<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Vanilo Configuration
    |--------------------------------------------------------------------------
    */
    'cart' => [
        'preserve_for_user' => true,
        // 'auto_assign_user' => true,
        'user' => [
            'model' => App\Models\User::class,
        ],
    ],
    'foundation' => [

        'image' => [

            'product' => [
                'variants' => [
                    'thumbnail' => [
                        'width' => 250,
                        'height' => 250,
                        'fit' => 'crop',
                    ],
                    'cart' => [
                        'width' => 120,
                        'height' => 90,
                        'fit' => 'crop',
                    ],
                ],
            ],

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Taxes (IMPORTANT FIX)
    |--------------------------------------------------------------------------
    */

    'taxes' => [
        'engine' => [
            // Use "simple" for GST/VAT calculations
            'driver' => 'simple',
            // Optional: use shipping instead of billing address
            'simple' => [
                'use_shipping_address' => false,
            ],
        ],
    ],

];
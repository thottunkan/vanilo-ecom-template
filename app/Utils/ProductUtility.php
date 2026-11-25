<?php

namespace App\Utils;
use Vanilo\MasterProduct\Models\MasterProduct;

class ProductUtility
{
    public static function has_product_varients($master_product_id, $master_product_varient_id)
    {
        $product = MasterProduct::findOrFail($master_product_id);
        $buyable = $product;
        if ($master_product_varient_id) {
            $buyable = $product->variants()
                ->where('id', $master_product_varient_id)
                ->firstOrFail();
        }
        return $buyable;
    }

    
}
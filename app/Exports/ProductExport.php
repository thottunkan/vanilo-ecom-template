<?php

namespace App\Exports;

use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Links\Query\Get;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    // NOTE: this column order must stay in sync with ProductImport::mapRow().
    // Index 0-13 = plain fields, 14 = categories, 15 = related products,
    // 16 = attributes (JSON), 17 = variants (JSON).

    public function collection()
    {
        // Eager load everything we read in map() to avoid N+1 queries.
        // If your MasterProduct model exposes a direct relation for
        // related-product links (e.g. via a Vanilo\Links trait), add it
        // here too instead of querying per-row in getRelatedProducts().
        return MasterProduct::with(['taxons', 'variants'])->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'sku',
            'price',
            'description',
            'stock',
            'color',
            'size',
            'length',
            'width',
            'height',
            'tax_category_id',
            'tax_rate_id',
            'tax_rate',
            'state',
            'categories',
            'related_products',
            'attributes',
            'variants',
        ];
    }

    /**
     * Get related product SKUs.
     *
     * Errors are logged (with the product SKU) rather than swallowed,
     * so a broken link lookup shows up in the logs instead of just
     * silently producing an empty column for every row.
     */
    protected function getRelatedProducts(MasterProduct $product): string
    {
        try {
            return Get::the('related-products')
                ->links()
                ->of($product)
                ->map(function ($link) {
                    return optional($link->linked)->sku;
                })
                ->filter()
                ->implode(',');
        } catch (\Throwable $e) {
            Log::error('Product Export: failed to resolve related products', [
                'sku'   => $product->sku,
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    /**
     * Encode custom attributes as JSON, defensively. Guards against the
     * attribute being null or not actually an array (e.g. if the model
     * cast is missing), which would otherwise export as the literal
     * string "null" or "false" and break re-import.
     */
    protected function getAttributesJson(MasterProduct $product): string
    {
        $attributes = $product->attributes;

        if (empty($attributes) || !is_array($attributes)) {
            return '';
        }

        try {
            return json_encode($attributes, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
        } catch (\Throwable $e) {
            Log::error('Product Export: failed to encode attributes', [
                'sku'   => $product->sku,
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    /**
     * Encode variants as JSON. Includes color/size/dimension fields when
     * the variant model actually has them, so a round trip through
     * ProductImport doesn't quietly lose data that only lived on the
     * variant (not the parent product).
     */
    protected function getVariantsJson(MasterProduct $product): string
    {
        if ($product->variants->isEmpty()) {
            return '';
        }

        try {
            $variants = $product->variants->map(function ($variant) {
                $data = [
                    'sku'   => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                ];

                foreach (['color', 'size', 'length', 'width', 'height'] as $field) {
                    if (isset($variant->{$field}) && $variant->{$field} !== null && $variant->{$field} !== '') {
                        $data[$field] = $variant->{$field};
                    }
                }

                return $data;
            })->values();

            return json_encode($variants, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
        } catch (\Throwable $e) {
            Log::error('Product Export: failed to encode variants', [
                'sku'   => $product->sku,
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    public function map($product): array
    {
        // Categories
        $categories = $product->taxons
            ->pluck('name')
            ->implode(',');

        return [
            $product->name,
            $product->sku,
            $product->price,
            $product->description,
            $product->stock,
            $product->color,
            $product->size,
            $product->length,
            $product->width,
            $product->height,
            $product->tax_category_id,
            $product->tax_rate_id,
            $product->tax_rate,
            $product->state,
            $categories,
            $this->getRelatedProducts($product),
            $this->getAttributesJson($product),
            $this->getVariantsJson($product),
        ];
    }
}
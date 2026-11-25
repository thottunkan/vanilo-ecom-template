<?php

namespace App\Imports;

use App\Services\ProductService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Links\Models\LinkType;

class ProductImport implements ToCollection
{
    protected ProductService $productService;

    // Collected after the main loop so we can link related products
    // even when the referenced SKU appears later in the file.
    protected array $pendingRelatedLinks = [];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function collection(Collection $rows)
    {
        // Remove heading row
        $rows->shift();

        // Make sure the link type exists once, not once per row.
        LinkType::firstOrCreate(
            ['slug' => 'related-products'],
            ['name' => 'Related Products']
        );

        foreach ($rows as $row) {
            try {
                $data = $this->mapRow($row);

                if (empty($data['sku'])) {
                    continue;
                }

                // Skip duplicate SKU
                if (MasterProduct::where('sku', $data['sku'])->exists()) {
                    continue;
                }

                $product = MasterProduct::create([
                    'name' => $data['name'],
                    'slug' => Str::slug($data['name']) . '-' . Str::random(5),
                    'sku' => $data['sku'],
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'excerpt' => $data['description'],
                    'stock' => $data['stock'],
                    'color' => $data['color'],
                    'size' => $data['size'],
                    'length' => $data['length'],
                    'width' => $data['width'],
                    'height' => $data['height'],
                    'attributes' => $data['attributes'],
                    'tax_category_id' => $data['tax_category_id'],
                    'tax_rate_id' => $data['tax_rate_id'],
                    'tax_rate' => $data['tax_rate'],
                    'state' => $data['state'],
                ]);

                // Each side-effect below is isolated: if one fails, the
                // others still run and we get a clear log line per failure
                // instead of the whole row (or whole import) dying silently.

                $this->applyCategories($product, $data['taxons']);
                $this->queueRelatedProducts($product, $data['related_skus']);
                $this->applyCustomAttributes($product, $data['attributes']);
                $this->applyVariants($product, $data['variants']);
                $this->applyProperties($product, $data);

            } catch (\Throwable $e) {
                Log::error('Product Import Error', [
                    'sku' => $row[1] ?? '',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Second pass: resolve related-product SKUs now that every
        // product in this file has been created.
        $this->flushRelatedProducts();
    }

    /**
     * Turn a raw row into a clean, typed array. All JSON decoding
     * happens exactly once here, with its own error handling, so
     * later blocks never rely on a stale json_last_error() state.
     */
    protected function mapRow(Collection|array $row): array
    {
        return [
            'name' => trim($row[0] ?? ''),
            'sku' => trim($row[1] ?? ''),
            'price' => $row[2] ?? 0,
            'description' => $row[3] ?? '',
            'stock' => $row[4] ?? 0,
            'color' => $row[5] ?? null,
            'size' => $row[6] ?? null,
            'length' => $row[7] ?? null,
            'width' => $row[8] ?? null,
            'height' => $row[9] ?? null,
            'tax_category_id' => $row[10] ?? null,
            'tax_rate_id' => $row[11] ?? null,
            'tax_rate' => $row[12] ?? 0,
            'state' => $row[13] ?? 'active',
            'taxons' => $this->splitList($row[14] ?? ''),
            'related_skus' => $this->splitList($row[15] ?? ''),
            'attributes' => $this->decodeJson($row[16] ?? '', 'attributes', $row[1] ?? ''),
            'variants' => $this->decodeJson($row[17] ?? '', 'variants', $row[1] ?? ''),
        ];
    }

    protected function splitList(string $value): array
    {
        if (trim($value) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    /**
     * Safely decode a JSON cell. Never throws, never dd()s, and logs
     * exactly what went wrong (with the offending SKU) if it fails.
     */
    protected function decodeJson(string $raw, string $label, string $sku): array
    {
        $raw = trim($raw);

        if ($raw === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            Log::warning("Product Import: invalid {$label} JSON, skipping", [
                'sku' => $sku,
                'error' => json_last_error_msg(),
                'raw' => $raw,
            ]);

            return [];
        }

        return $decoded;
    }

    protected function applyCategories(MasterProduct $product, array $taxons): void
    {
        if (empty($taxons)) {
            return;
        }

        try {
            $this->productService->applyCatetoryiesToProduct($product, ['taxons' => $taxons]);
        } catch (\Throwable $e) {
            Log::error('Product Import: failed to apply categories', [
                'sku' => $product->sku,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function queueRelatedProducts(MasterProduct $product, array $relatedSkus): void
    {
        if (empty($relatedSkus)) {
            return;
        }

        // Defer resolution to after all rows are imported, so SKUs that
        // appear later in the same file can still be linked.
        $this->pendingRelatedLinks[$product->id] = $relatedSkus;
    }

    protected function flushRelatedProducts(): void
    {
        foreach ($this->pendingRelatedLinks as $productId => $relatedSkus) {
            try {
                $product = MasterProduct::find($productId);

                if (!$product) {
                    continue;
                }

                $relatedIds = MasterProduct::whereIn('sku', $relatedSkus)->pluck('id')->all();

                if (!empty($relatedIds)) {
                    $this->productService->attachRelatedProducts($product, $relatedIds);
                }
            } catch (\Throwable $e) {
                Log::error('Product Import: failed to attach related products', [
                    'product_id' => $productId,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    protected function applyCustomAttributes(MasterProduct $product, array $attributes): void
    {
        if (empty($attributes)) {
            return;
        }

        try {
            $this->productService->createCustomAttributes($product, $attributes);
        } catch (\Throwable $e) {
            Log::error('Product Import: failed to create custom attributes', [
                'sku' => $product->sku,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function applyVariants(MasterProduct $product, array $variants): void
    {
        if (empty($variants)) {
            return;
        }   

        try {
            $this->productService->createVariantsFromExcel($product, ['variants' => $variants]);
        } catch (\Throwable $e) {
            Log::error('Product Import: failed to create variants', [
                'sku' => $product->sku,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function applyProperties(MasterProduct $product, array $data): void
    {
        try {
            $this->productService->assignProperties($product, [
                'color' => $data['color'],
                'size' => $data['size'],
                'price' => $data['price'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Product Import: failed to assign properties', [
                'sku' => $product->sku,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
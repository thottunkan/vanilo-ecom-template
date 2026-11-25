<?php
namespace App\Services;
use App\Imports\ProductImport;
use Illuminate\Http\Request;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use Str;
use Vanilo\Category\Models\Taxon;
use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Links\Models\LinkType;
use Vanilo\Links\Query\Eliminate;
use Vanilo\Links\Query\Establish;
use Vanilo\Links\Query\Get;
use Vanilo\Properties\Models\Property;
class ProductService
{

    //shop


    public function getProductsByCategorySlug($slug)
    {
        $category = Taxon::where('slug', $slug)->firstOrFail();

        $products = MasterProduct::with([
            'variants',
            'taxons'
        ])
            ->whereHas('taxons', function ($query) use ($category) {
                $query->where('id', $category->id);
            })
            ->get();

        $this->transformProductForImage($products);

        return response()->json([
            'category' => $category,
            'product_count' => $products->count(),
            'products' => $products,
        ]);
    }

    public function getProductCategoryByProductSlug($slug)
    {
        $product = MasterProduct::where('slug', $slug)->with('taxons')->get();
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'taxons' => $product->taxons
        ]);
    }

    public function filter(Request $request)
    {
        $categories = $request->filled('categories')
            ? array_map('trim', explode(',', $request->categories))
            : [];

        $colors = $request->filled('colors')
            ? array_map('trim', explode(',', $request->colors))
            : [];

        $sizes = $request->filled('sizes')
            ? array_map('trim', explode(',', $request->sizes))
            : [];

        $products = MasterProduct::query()

            // Categories filter
            ->when(!empty($categories), function ($query) use ($categories) {
                $query->whereHas('taxons', function ($q) use ($categories) {
                    $q->whereIn('slug', $categories);
                });
            })

            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', $request->min_price);
            })

            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', $request->max_price);
            })

            ->when($request->filled('name'), function ($query) use ($request) {
                $query->where('name', '=', $request->name);
            })
            ->when($request->filled('sku'), function ($query) use ($request) {
                $query->where('sku', '=', $request->sku);
            })

            ->when(!empty($colors), function ($query) use ($colors) {
                $query->whereIn('color', $colors);
            })

            ->when(!empty($sizes), function ($query) use ($sizes) {
                $query->whereIn('size', $sizes);
            })

            ->with([
                'media',
                'taxons',
                'propertyValues',
                'variants' => function ($variantQuery) use ($request, $colors, $sizes) {

                    if ($request->filled('min_price')) {
                        $variantQuery->where('price', '>=', $request->min_price);
                    }

                    if ($request->filled('max_price')) {
                        $variantQuery->where('price', '<=', $request->max_price);
                    }

                    if (!empty($colors)) {
                        $variantQuery->whereIn('color', $colors);
                    }

                    if (!empty($sizes)) {
                        $variantQuery->whereIn('size', $sizes);
                    }

                }
            ])

            ->get();
        return $products;

    }

    //admin section
    public function createVariants($model, $data)
    {
        $count = 0;

        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                // Log::info($variant['sku']);
                ++$count;
                $variantModel = $model->createVariant([
                    'color' => $variant['color'] ?? null,
                    'size' => $variant['size'] ?? null,
                    'sku' =>
                        $data['name'] . '_' . $count,
                    'price' =>
                        $variant['price'] ?? 0,
                    'stock' =>
                        $variant['stock'] ?? 0,
                    'slug' => Str::slug($data['name'] . ' ' . $count) ?? null,
                    'name' => $data['name'] . ' [' . $data['color'] . ']' ?? null
                ]);

                $this->assignProperties($variantModel, [
                    'color' => $variant['color'] ?? null,
                    'size' => $variant['size'] ?? null,
                    'price' => $variant['price'] ?? null,
                ]);

            }
        }
    }

    public function createVariantsFromExcel($model, $data)
    {
        $count = 0;

        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $variant) {
                Log::info($variant['sku']);
                ++$count;
                $variantModel = $model->createVariant([
                    'color' => $variant['color'] ?? null,
                    'size' => $variant['size'] ?? null,
                    'sku' =>
                        $variant['sku'] . '_' . $count,
                    'price' =>
                        $variant['price'] ?? 0,
                    'stock' =>
                        $variant['stock'] ?? 0,
                    'slug' => Str::slug($variant['name'] . ' ' . $count) ?? null,
                    'name' => $variant['name'] . ' [' . $variant['color'] . ']' ?? null
                ]);

                $this->assignProperties($variantModel, [
                    'color' => $variant['color'] ?? null,
                    'size' => $variant['size'] ?? null,
                    'price' => $variant['price'] ?? null,
                ]);

            }
        }
    }

    public function updateVariants($model, $data)
    {
        $variants = $model->variants()->get();

        foreach ($variants as $variant) {
            $variant->delete();
        }

        $this->createVariants($model, $data);
    }

    public function createCustomAttributes($model, $attributes)
    {
        $customAttributes = [];

        // foreach ($attributes ?? [] as $attribute) {
        //     $name = strtolower(trim($attribute['name'] ?? ''));
        //     $value = trim($attribute['value'] ?? '');

        //     if ($name !== '' && $value !== '') {
        //         $customAttributes[$name] = $value;
        //     }
        // }

        $model->update([
            'attributes' => $attributes,
        ]);
    }

    public function applyCatetoryiesToProduct($model, $data)
    {

        if (!empty($data['taxons'])) {
            $model->taxons()
                ->sync($data['taxons']);
        }
    }

    public function assignProperties($model, array $values)
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

    public function updateProperties($product)
    {
        if ($product->properties()->exists()) {
            $product->properties()->delete();
            $this->assignProperties($product, [
                'color' => $data['color'] ?? null,
                'size' => $data['size'] ?? null,
                'price' => $data['price'] ?? null,
            ]);
        }
    }

    public function storeImages($model, $images, $name): void
    {
        foreach ($images as $key => $image) {
            $media = $model
                ->addMedia($image)
                ->toMediaCollection($name);

            if ($key === 0) {
                $media->setCustomProperty('isPrimary', true);
                $media->save();
            }
        }
    }

    public function uploadOriginalImages($product, $files): void
    {
        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            $product->addMedia($file)
                ->toMediaCollection('images');
        }
    }

    public function uploadThumbnailImages($product, $files): void
    {
        if (empty($files)) {
            return;
        }

        foreach ($files as $file) {
            $product->addMedia($file)
                ->toMediaCollection('thumbnail_images');
        }
    }

    public function transformProductForImage($products)
    {
        return $products->transform(function ($product) {

            $product->images = $product->getMedia('images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'thumbnail_url' => $media->getUrl('thumb'),
                    'original_url' => asset($media->getUrl('medium')),
                    'is_primary' => $media->getCustomProperty('isPrimary', false),
                ];
            });

            $product->thumbnail_images = $product->getMedia('thumbnail_images')->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->file_name,
                    'thumbnail_url' => $media->getUrl('thumb'),
                    'original_url' => $media->getUrl('medium'),
                    'is_primary' => $media->getCustomProperty('isPrimary', false),
                ];
            });

            return $product;
        });
    }

    public function attachRelatedProducts(MasterProduct $product, array $relatedProductIds): void
    {
        $relatedProducts = MasterProduct::whereIn('id', $relatedProductIds)->get();

        foreach ($relatedProducts as $relatedProduct) {
            Establish::an('related-products')
                ->link()
                ->between($product)
                ->and($relatedProduct);
        }
    }

    public function createRelatedProductsLinkType(): LinkType
    {
        return LinkType::firstOrCreate(
            ['slug' => 'related-products'],
            ['name' => 'Related Products']
        );
    }

    function getRelatedProducts($product)
    {
        try {
            return Get::the('related-products')
                ->links()
                ->of($product);
        } catch (\Exception $e) {
            return collect();
        }
    }

    public function updateRelatedProducts(MasterProduct $product, array $relatedProductIds): void
    {
        // Remove existing related product links
        $existingRelatedProducts = Get::the('related-products')
            ->links()
            ->of($product);

        foreach ($existingRelatedProducts as $relatedProduct) {
            Eliminate::the('related-products')
                ->link()
                ->between($product)
                ->and($relatedProduct);
        }

        // Create new related product links
        $relatedProducts = MasterProduct::whereIn('id', $relatedProductIds)->get();

        foreach ($relatedProducts as $relatedProduct) {
            // Prevent linking product to itself
            if ($product->id === $relatedProduct->id) {
                continue;
            }

            Establish::an('related-products')
                ->link()
                ->between($product)
                ->and($relatedProduct);
        }
    }

    public function getProductsFromPriceLowToHigh($lowestPrice, $highestPrice)
    {
        $products = MasterProduct::query()

            ->whereBetween('price', [$lowestPrice, $highestPrice])

            ->with([
                'media',
                'taxons',
                'propertyValues',
                'variants'
            ])

            ->orderBy('price', 'asc')

            ->get();

        return $products;
    }

    public function getProductsFromPriceHighToLow($highestPrice, $lowestPrice)
    {
        $products = MasterProduct::query()

            ->whereBetween('price', [$lowestPrice, $highestPrice])

            ->with([
                'media',
                'taxons',
                'propertyValues',
                'variants'
            ])

            ->orderBy('price', 'desc')

            ->get();

        return $products;
    }

    public function getProductsFromAtoZ()
    {
        $products = MasterProduct::query()

            ->with([
                'media',
                'taxons',
                'propertyValues',
                'variants'
            ])

            ->orderBy('name', 'asc')

            ->get();

        return $products;
    }
    public function getProductsFromZtoA()
    {
        $products = MasterProduct::query()

            ->with([
                'media',
                'taxons',
                'propertyValues',
                'variants'
            ])

            ->orderBy('name', 'desc')

            ->get();

        return $products;
    }

    public function getFeaturedProducts()
    {
        $products = MasterProduct::query()

            ->where('is_featured', true)

            ->with([
                'media',
                'taxons',
                'propertyValues',
                'variants'
            ])

            ->get();

        return $products;
    }



    //     public function getProductsFromPriceLowToHigh($lowestPrice, $highestPrice)
    // {
    //     return MasterProduct::query()
    //         ->whereBetween('price', [$lowestPrice, $highestPrice])
    //         ->with([
    //             'media',
    //             'taxons',
    //             'propertyValues',
    //             'variants' => function ($query) use ($lowestPrice, $highestPrice) {
    //                 $query->whereBetween('price', [$lowestPrice, $highestPrice]);
    //             }
    //         ])
    //         ->orderBy('price', 'asc')
    //         ->get();
    // }


    // public function getProductsFromPriceHighToLow($highestPrice, $lowestPrice)
    // {
    //     return MasterProduct::query()
    //         ->whereBetween('price', [$lowestPrice, $highestPrice])
    //         ->with([
    //             'media',
    //             'taxons',
    //             'propertyValues',
    //             'variants' => function ($query) use ($lowestPrice, $highestPrice) {
    //                 $query->whereBetween('price', [$lowestPrice, $highestPrice]);
    //             }
    //         ])
    //         ->orderBy('price', 'desc')
    //         ->get();
    // }

    public function importFromExcel(Request $request)
    {
        // $request->validate([
        //     'import_form_excel' => 'required|mimes:xlsx,xls,csv'
        // ]);
        try {
            Excel::import(
                new ProductImport($this),
                $request->file('file')
            );
            return true;
        } catch (\Exception $e) {
            return false;
        }

    }

    public function importFromCSV()
    {

    }
}
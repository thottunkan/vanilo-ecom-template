<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;

use App\Utils\BulkDelete;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Vanilo\Category\Models\Taxon;

use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Properties\Models\Property;
use Vanilo\Taxes\Models\TaxCategory;
use Vanilo\Taxes\Models\TaxRate;
use App\Exports\ProductExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function bulk_delete(Request $request)
    {
        return BulkDelete::perform($request, new MasterProduct());
    }

    public function index(Request $request)
    {
        $products = MasterProduct::with([
            'variants',
            'propertyValues',
            'taxons'
        ])

            // Search title/content/name fields
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('subtitle', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })

            // Status filter
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('state', $request->status);
            })

            ->when($request->filled('stock'), function ($query) use ($request) {
                $query->where('stock', $request->stock);
            })

            // Created date exact filter
            ->when($request->filled('created_at'), function ($query) use ($request) {
                $query->whereDate('created_at', $request->created_at);
            })

            // Updated date exact filter
            ->when($request->filled('updated_at'), function ($query) use ($request) {
                $query->whereDate('updated_at', $request->updated_at);
            })

            // Date range filter
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })

            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            });


        // Sorting
        switch ($request->get('sort')) {

            case 'created_at_asc':
                $products->orderBy('created_at', 'asc');
                break;

            case 'updated_at_desc':
                $products->orderBy('updated_at', 'desc');
                break;

            case 'name_asc':
                $products->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $products->orderBy('name', 'desc');
                break;

            case 'created_at_desc':
            default:
                $products->orderBy('created_at', 'desc');
                break;
        }


        // Get all records (no pagination)
        $products = $products->get();


        return view('admin.product.index', compact('products'));
    }


    public function create()
    {
        $tax_categories = TaxCategory::all();
        $categories = Taxon::with('children')
            ->whereNull('parent_id')
            ->get();
        $related_products = MasterProduct::all();
        return view('admin.product.create', compact('categories', 'tax_categories', 'related_products'));
    }

    public function edit($id)
    {

        $categories = Taxon::with('children')
            ->whereNull('parent_id')
            ->get();
        $tax_categories = TaxCategory::all();

        $product = MasterProduct::with([
            'propertyValues.property',
            'media',
            'variants',
            'taxons',
            'taxRate'
        ])->findOrFail($id);

        $selectedRelatedIds = $this->productService
            ->getRelatedProducts($product)
            ->pluck('id')
            ->toArray();

        $related_products = MasterProduct::where('id', '!=', $id)->get();

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

        return view('admin.product.edit', compact('product', 'categories', 'tax_categories', 'selectedRelatedIds', 'related_products'));
    }

    public function delete($id)
    {
        $product = MasterProduct::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.product.index');

    }

    public function store(Request $request)
    {

        try {

            $data = $request->validate([

                'name' => 'required|string',
                'sku' => 'required|string|unique:master_products,sku',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'taxons' => 'nullable|array',
                'stock' => 'required|integer',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'length' => 'nullable|string',
                'width' => 'nullable|string',
                'height' => 'nullable|string',
                'variants' => 'nullable|array',
                'attributes' => 'nullable|array',
                'tax_category_id' => 'required|integer',
                'tax_rate_id' => 'required|integer',
                'tax_rate' => 'nullable',
                'state' => 'nullable',

                // 'variants.*.sku' => 'required|string',
                'variants.*.stock' => 'nullable|integer',
                'variants.*.color' => 'nullable|string',
                'variants.*.size' => 'nullable|string',
                'variants.*.price' => 'nullable|numeric|min:0',

                //related products
                'related_products' => 'nullable|array',

                //image section
                'thumbnail_images' => 'nullable|array',
                'images' => 'nullable|array',
                'images.*' => 'image|max:2048'

            ]);

            //return json_encode($data['variants']);
            $product = MasterProduct::create([
                'name' => $data['name'] ?? null,
                'slug' => Str::slug($data['name']) ?? null,
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'] ?? null,
                'description' => $data['description'] ?? null,
                'excerpt' => $data['description'] ?? null,
                'color' => $data['color'] ?? null,
                'size' => $data['size'] ?? null,
                'stock' => $data['stock'] ?? null,
                'length' => $data['length'] ?? null,
                'width' => $data['width'] ?? null,
                'height' => $data['height'] ?? null,
                'attributes' => null,
                'tax_category_id' => $data['tax_category_id'] ?? null,
                'tax_rate_id' => $data['tax_rate_id'] ?? null,
                'tax_rate' => $data['tax_rate'] ?? 0,
                'state' => $data['state']
            ]);

            $this->productService->applyCatetoryiesToProduct($product, $data);
            //creating related product table

            if (!empty($data['related_products'])) {
                $this->productService->createRelatedProductsLinkType();
                $this->productService->attachRelatedProducts($product, $data['related_products']);
            }

            if (!empty($data['attributes'])) {
                $this->productService->createCustomAttributes($product, $data['attributes']);
            }

            $this->productService->createVariants($product, $data);

            $this->productService->assignProperties($product, [
                'color' => $data['color'] ?? null,
                'size' => $data['size'] ?? null,
                'price' => $data['price'] ?? null,
            ]);

            if ($request->hasFile('images')) {
                $this->productService->uploadOriginalImages(
                    $product,
                    $request->file('images')
                );
            }

            if ($request->hasFile('thumbnail_images')) {
                $this->productService->uploadThumbnailImages(
                    $product,
                    $request->file('thumbnail_images')
                );
            }

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->success('Product created successfully..!');
            return redirect()->route('admin.product.index');

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $e->getMessage(),
                    'data' => []
                ], 201);
            }
            return back()
                ->withErrors($e->getMessage());
        }
    }



    public function update(Request $request, $id)
    {
        try {

            $product = MasterProduct::with(['variants'])->findOrFail($id);

            $data = $request->validate([

                'name' => 'required|string',
                'sku' => 'required|string|unique:master_products,sku,' . $product->id,
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'taxons' => 'nullable|array',
                'stock' => 'required|integer',
                'color' => 'nullable|string',
                'size' => 'nullable|string',
                'tax_rate' => 'nullable',
                'tax_category_id' => 'nullable|integer',
                'tax_rate_id' => 'nullable|integer',

                'variants' => 'nullable|array',
                'variants.*.id' => 'nullable|integer',
                'variants.*.stock' => 'nullable|integer',
                'variants.*.color' => 'nullable|string',
                'variants.*.size' => 'nullable|string',
                'variants.*.price' => 'required|numeric|min:0',

                'images' => 'nullable|array',
                'images.*' => 'image|max:2048',
                'state' => 'nullable',

                'length' => 'nullable|string',
                'width' => 'nullable|string',
                'height' => 'nullable|string',
                'attributes' => 'nullable|array',
                'related_products' => 'nullable|array',
                'thumbnail_images' => 'nullable|array',

            ]);
           
            $product->update([
                'name' => $data['name'] ?? null,
                'slug' => Str::slug($data['name']) ?? null,
                'sku' => $data['sku'] ?? null,
                'price' => $data['price'] ?? null,
                'description' => $data['description'] ?? null,
                'excerpt' => $data['description'] ?? null,
                'color' => $data['color'] ?? null,
                'size' => $data['size'] ?? null,
                'stock' => $data['stock'] ?? null,
                'length' => $data['length'] ?? null,
                'width' => $data['width'] ?? null,
                'height' => $data['height'] ?? null,
                'tax_category_id' => $data['tax_category_id'] ?? null,
                'tax_rate_id' => $data['tax_rate_id'] ?? null,
                'tax_rate' => $data['tax_rate'] ?? 0,
                'state' => $data['state'] ?? null,
            ]);

            $this->productService->applyCatetoryiesToProduct($product, $data);
            //$product->variants()
            $this->productService->updateVariants($product, $data);
            // $this->productService->assignProperties($product, [
            //     'color' => $data['color'] ?? null,
            //     'size' => $data['size'] ?? null,
            //     'price' => $data['price'] ?? null,
            // ]);

            if (!empty($data['attributes'])) {
                $this->productService->createCustomAttributes($product, $data['attributes']);
            }

            if (!empty($data['related_products'])) {
                $this->productService->updateRelatedProducts($product, $data['related_products']);
            }

            if ($request->hasFile('images')) {
                $this->productService->uploadOriginalImages(
                    $product,
                    $request->file('images')
                );
            }

            if ($request->hasFile('thumbnail_images')) {
                $this->productService->uploadThumbnailImages(
                    $product,
                    $request->file('thumbnail_images')
                );
            }

            notyf()
                ->position('x', 'right')
                ->position('y', 'top')
                ->success('Product updated successfully..!');

            return redirect()->route('admin.product.index');

        } catch (\Exception $e) {

            Log::error($e);

            return back()
                ->withInput()
                ->withErrors($e->getMessage());
        }
    }

    public function removeImage(Request $request, $product_id, $image_id)
    {
        $product = MasterProduct::findOrFail($product_id);

        $media = $product->getMedia('images')
            ->firstWhere('id', $image_id);

        if (!$media) {
            return response()->json([
                'message' => 'Image not found.'
            ], 404);
        }

        $wasPrimary = $media->getCustomProperty('isPrimary', false);

        $media->delete();

        // if ($wasPrimary) {

        //     $newPrimary = $product->getMedia('images')->first();

        //     if ($newPrimary) {
        //         $newPrimary->setCustomProperty('isPrimary', true);
        //         $newPrimary->save();
        //     }
        // }

        return response()->json([
            'message' => 'Image removed successfully.'
        ]);
    }

    public function removeThumbnailImage(Request $request, $product_id, $image_id)
    {
        $product = MasterProduct::findOrFail($product_id);

        $media = $product->getMedia('thumbnail_images')
            ->firstWhere('id', $image_id);

        if (!$media) {
            return response()->json([
                'message' => 'Image not found.'
            ], 404);
        }

        $wasPrimary = $media->getCustomProperty('isPrimary', false);

        $media->delete();

        // if ($wasPrimary) {

        //     $newPrimary = $product->getMedia('images')->first();

        //     if ($newPrimary) {
        //         $newPrimary->setCustomProperty('isPrimary', true);
        //         $newPrimary->save();
        //     }
        // }

        return response()->json([
            'message' => 'Image removed successfully.'
        ]);
    }

    public function inportFromExcel(Request $request)
    {
        $this->productService->importFromExcel($request);

        notyf()->success('Products imported successfully.');

        return redirect()->route('admin.product.index');
    }

    public function inportFromCSV(Request $request)
    {
        //return $this->productService->importFromCSV($request);
    }

    public function exportToExcel()
    {
        return Excel::download(new ProductExport, 'products.xlsx');
    }

    public function downloadExcelTemplate()
    {
        $filePath = public_path('admin-assets/files/product_import_template.xlsx');

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath, 'product_import_template.xlsx');
    }
}
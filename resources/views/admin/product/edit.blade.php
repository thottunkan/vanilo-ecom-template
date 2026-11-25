@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Product',
                'links' => ['Catalog', 'Product', 'Edit Product'],
            ])


            <div class="row">

                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Product</h5>
                        </div>

                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.product.update', $product->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- PRODUCT BASIC INFO --}}
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $product->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>SKU</label>
                                    <input type="text" class="form-control" name="sku"
                                        value="{{ old('sku', $product->sku) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Stock</label>
                                    <input type="number" class="form-control" name="stock"
                                        value="{{ old('stock', $product->stock) }}" required>

                                </div>

                                <div class="mb-3">
                                    <label>Price</label>
                                    <input type="number" step="0.01" class="form-control" name="price"
                                        value="{{ old('price', $product->price) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label>Status</label>

                                    <select name="state" class="form-control">
                                        <option value="draft" {{ $product->state == 'draft' ? 'selected' : '' }}>
                                            <span class="bg-warning">Draft</span>
                                        </option>

                                        <option value="active" {{ $product->state == 'active' ? 'selected' : '' }}>
                                            <span class='bg-success'>Active</span>
                                        </option>

                                        <option value="inactive" {{ $product->state == 'inactive' ? 'selected' : '' }}>
                                            <span class="bg-danger">Inactive</span>
                                        </option>
                                    </select>
                                </div>

                                {{-- <div class="mb-3">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description">{{ old('description', $product->description) }}</textarea>
                                </div> --}}

                                {{-- MASTER ATTRIBUTES --}}
                                <div class="mb-3">
                                    <label>Color</label><br>
                                    <input class="form-control form-control" placeholder="(optional)" type="text"
                                        name="color" value="{{ $product->color ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label>Size</label>
                                    <input class="form-control" placeholder="(optional)" type="text" name="size"
                                        value="{{ $product->size ?? '' }}">
                                </div>

                                <div class="mb-3">
                                    <label>Length</label>
                                    <input class="form-control" value={{ $product->length ?? '0' }}
                                        placeholder="(optional)" type="text" name="length" />
                                </div>

                                <div class="mb-3">
                                    <label>Width</label>
                                    <input class="form-control" placeholder="(optional)" type="text" name="width"
                                        value="{{ $product->width ?? 0 }}">
                                </div>

                                <div class="mb-3">
                                    <label>Height</label>
                                    <input class="form-control" placeholder="(optional)" type="text" name="height"
                                        value="{{ $product->height ?? 0 }}">
                                </div>

                                @php
                                    // dd($product->taxRate)
                                @endphp

                                {{-- <div class="mb-3">
                                    <label>Tax Category</label>
                                    <select name="tax_category_id" class="form-control" id="tax_category">
                                        <option selected value="">--choose--</option>
                                        @foreach ($tax_categories as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="mb-3">
                                    <label>Tax Category</label>

                                    <select name="tax_category_id" class="form-control" id="tax_category">


                                        @foreach ($tax_categories as $tc)
                                            <option value="{{ $tc->id }}"
                                                {{ $product->tax_category_id == $tc->id ? 'selected' : '' }}>
                                                {{ $tc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="mb-3">
                                    <label>Applicable Tax Rates</label>
                                    <select name="tax_rate_id" class="form-control" id="applicable_tax_rates">
                                        @if (!empty($product->taxRate))
                                            <option value="{{ $product->taxRate->id }}">{{ $product->taxRate->name }}
                                            </option>
                                        @endif
                                    </select>
                                </div> --}}
                                <div class="mb-3">
                                    <label>Applicable Tax Rates</label>

                                    <select name="tax_rate_id" class="form-control" id="applicable_tax_rates">

                                        @if ($product->taxRate)
                                            <option value="{{ $product->taxRate->id }}" selected>
                                                {{ $product->taxRate->name }}
                                            </option>
                                        @else
                                            <option value="">--choose--</option>
                                        @endif

                                    </select>
                                </div>

                                <input type="text" hidden id="tax_rate" name="tax_rate">

                                <div class="mb-3">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description">{{ $product->description ?? '' }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="">Choose related products</label>
                                    <select name="related_products[]" class="form-control" multiple>
                                        @foreach ($related_products as $related_product)
                                            <option value="{{ $related_product->id }}"
                                                {{ in_array($related_product->id, $selectedRelatedIds) ? 'selected' : '' }}>
                                                {{ $related_product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @php
                                    $attributes = $product->attributes ?? [];
                                    $hasAttributes = !empty($attributes);
                                @endphp

                                <div class="mb-3">
                                    <label>
                                        <input type="checkbox" class="form-check-input" name="is_custom_attributes_required"
                                            id="is_custom_attributes_required" {{ $hasAttributes ? 'checked' : '' }}>
                                        Need custom attributes
                                    </label>

                                    <div id="custom-attributes-section"
                                        style="display: {{ $hasAttributes ? 'block' : 'none' }};" class="mt-3">
                                        <div id="attributes-wrapper">

                                            @foreach ($attributes as $index => $attribute)
                                                @if (!empty($attribute['name']) || !empty($attribute['value']))
                                                    <div class="attribute-item row g-3 align-items-end mb-3">

                                                        <div class="col-md-5">
                                                            <label class="form-label">Attribute Name</label>
                                                            <input type="text" class="form-control"
                                                                name="attributes[{{ $index }}][name]"
                                                                value="{{ $attribute['name'] }}"
                                                                placeholder="e.g. Material">
                                                        </div>

                                                        <div class="col-md-5">
                                                            <label class="form-label">Attribute Value</label>
                                                            <input type="text" class="form-control"
                                                                name="attributes[{{ $index }}][value]"
                                                                value="{{ $attribute['value'] }}"
                                                                placeholder="e.g. Cotton">
                                                        </div>

                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger w-100"
                                                                onclick="removeCustomAttribute(this)">
                                                                Remove
                                                            </button>
                                                        </div>

                                                    </div>
                                                @endif
                                            @endforeach

                                        </div>

                                        <button type="button" class="btn btn-primary" onclick="addAttribute()">
                                            Add Attribute
                                        </button>
                                    </div>
                                </div>

                                {{-- CATEGORIES --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Select Categories</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="category-tree">
                                            {{-- {{ dd($product->taxons) }} --}}
                                            {{-- @include('admin.partials.category-tree-edit',[
                                            'categories'=>$categories,
                                            'selectedCategories'=>$product->categories->pluck('id')->toArray()
                                        ]) --}}
                                            @include('admin.partials.category-tree', [
                                                'categories' => $categories,
                                                'selected_categories' => $product->taxons->pluck('id')->toArray(),
                                            ])
                                        </div>
                                    </div>
                                </div>



                                <hr>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" name="is_variant_required"
                                        id="is_variant_required" {{ $product->variants->count() ? 'checked' : '' }}>

                                    <label class="form-check-label" for="is_variant_required">
                                        Need variants?
                                    </label>
                                </div>

                                <div id="variants-section"
                                    style="{{ $product->variants->count() ? '' : 'display:none;' }}">

                                    <h4>Variants</h4>
                                    <br>

                                    <div id="variants-wrapper">

                                        @forelse($product->variants as $key => $variant)
                                            <div class="variant-item row g-3 align-items-end mb-3 p-3 border rounded">

                                                <div class="col-md-3">
                                                    <label class="form-label">Price</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $key }}][price]"
                                                        value="{{ $variant->price }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $key }}][stock]"
                                                        value="{{ $variant->stock }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Color</label>
                                                    <input type="text" class="form-control"
                                                        name="variants[{{ $key }}][color]"
                                                        value="{{ $variant->color }}">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Size</label>
                                                    <input type="text" class="form-control"
                                                        name="variants[{{ $key }}][size]"
                                                        value="{{ $variant->size }}">
                                                </div>

                                                {{-- Keep variant id for updating --}}
                                                <input type="hidden" name="variants[{{ $key }}][id]"
                                                    value="{{ $variant->id }}">

                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-danger w-100"
                                                        onclick="removeVariant(this)">
                                                        <i class="ph ph-trash"></i>
                                                        Remove
                                                    </button>
                                                </div>

                                            </div>

                                        @empty

                                            {{-- <div class="variant-item row g-3 align-items-end mb-3 p-3 border rounded">

                                                <div class="col-md-3">
                                                    <label class="form-label">Price</label>
                                                    <input type="number" class="form-control" name="variants[0][price]">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Stock</label>
                                                    <input type="number" class="form-control" name="variants[0][stock]">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Color</label>
                                                    <input type="text" class="form-control" name="variants[0][color]">
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label">Size</label>
                                                    <input type="text" class="form-control" name="variants[0][size]">
                                                </div>

                                                <div class="col-md-3">
                                                    <button type="button" class="btn btn-danger w-100"
                                                        onclick="removeVariant(this)">
                                                        <i class="ph ph-trash"></i>
                                                        Remove
                                                    </button>
                                                </div>

                                            </div> --}}
                                        @endforelse

                                    </div>

                                    <button type="button" class="btn btn-primary" onclick="addVariant()">
                                        Add Variant
                                    </button>

                                </div>
                                {{-- IMAGES --}}

                                <hr>
                                <div class="mb-3">
                                    <label>Images</label>
                                    <input type="file" class="form-control" name="images[]" multiple
                                        accept="image/*">
                                </div>

                                <style>

                                    .image-wrapper {
                                        position: relative;
                                    }

                                    .delete-image {
                                        position: absolute;
                                        top: 5px;
                                        right: 5px;
                                        width: 28px;
                                        height: 28px;
                                        padding: 0;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                    }

                                    .delete-thumbnail-image {
                                        position: absolute;
                                        top: 5px;
                                        right: 5px;
                                        width: 28px;
                                        height: 28px;
                                        padding: 0;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                    }
                                </style>

                                @php
                                    $images = $product->getMedia('images');
                                    $thumbnail_images = $product->getMedia('thumbnail_images');

                                @endphp

                                @if ($images->count())
                                    <div class="row">
                                        @foreach ($images as $image)
                                            <div class="col-md-2 mb-3">
                                                <div class="position-relative image-wrapper">

                                                    <img src="{{ $image->getUrl('thumb') }}" class="img-thumbnail ">

                                                    <button type="button"
                                                        class="btn btn-danger btn-sm rounded-circle delete-image"
                                                        data-product="{{ $product->id }}"
                                                        data-image="{{ $image->id }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>

                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label>
                                        <input type="checkbox" class="form-check-input"
                                            name="is_thumbnail_image_required" id="is_thumbnail_image_required">
                                        Need Thumbnail Images?
                                    </label>
                                    @if ($thumbnail_images->count())
                                        <div class="row">
                                            @foreach ($thumbnail_images as $image)
                                                <div class="col-md-2 mb-3">
                                                    <div class="position-relative image-wrapper">

                                                        <img src="{{ $image->getUrl('thumb') }}"
                                                            class="img-thumbnail w-100" alt="Thumbnail">

                                                        <button type="button"
                                                            class="btn btn-danger btn-sm rounded-circle delete-thumbnail-image position-absolute"
                                                            style="top:5px; right:5px;"
                                                            data-product="{{ $product->id }}"
                                                            data-image="{{ $image->id }}">
                                                            <i class="fa fa-times"></i>
                                                        </button>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    <input type="file" class="form-control mt-2" id="thumbnail_images"
                                        name="thumbnail_images[]" multiple accept="image/*" style="display: none;">
                                </div>


                                <button type="submit" class="btn btn-primary">
                                    Update Product
                                </button>
                                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).on('change', '#tax_category', function() {

            let categoryId = $(this).val();
            let taxRateSelect = $('#applicable_tax_rates');

            // Clear existing options
            taxRateSelect.empty();

            // Add default option
            taxRateSelect.append('<option value="">--choose--</option>');

            if (!categoryId) {
                return;
            }

            const taxRateRoute = "{{ route('admin.taxrate.get_taxrates', ':id') }}";
            let url = taxRateRoute.replace(':id', categoryId);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    // Clear again before appending (optional but safe)
                    taxRateSelect.empty();
                    taxRateSelect.append('<option value="">--choose--</option>');

                    $.each(response.data, function(key, rate) {
                        taxRateSelect.append(`
                    <option value="${rate.id}">
                        ${rate.name} (${rate.rate}%)
                    </option>
                `);
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });

        });
    </script>

    <script src="{{ asset('app-scripts/admin/product/product-create.js') }}"></script>

    <script>
        window.deleteImageConfig = {
            urlTemplate: "{{ route('admin.product.delete_product_image', ['product_id' => '__PRODUCT__', 'image_id' => '__IMAGE__']) }}",
            csrf: "{{ csrf_token() }}"
        };

        window.deleteThumbnailImageConfig = {
            urlTemplate: "{{ route('admin.product.delete_product_thumbnail_image', ['product_id' => '__PRODUCT__', 'image_id' => '__IMAGE__']) }}",
            csrf: "{{ csrf_token() }}"
        };
    </script>
    
    <script src="{{ asset('app-scripts/admin/product/delete-image.js') }}"></script>

    <script>
        $('#is_custom_attributes_required').on('change', function() {

            $('#custom-attributes-section').toggle(this.checked);
        });
    </script>

@endsection

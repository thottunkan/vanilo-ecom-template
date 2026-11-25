@extends('shop.layout.shop-layout')
@section('title', $product->name)
@section('content')
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <img class="img-fluid rounded" style="max-height:600px;object-fit:contain"
                        src="{{ $product->getFirstMediaUrl('images', 'medium') ?: $product->getFirstMediaUrl('thumbnail_images', 'medium') ?: asset('images/placeholder.jpg') }}"
                        alt="{{ $product->name }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="small text-muted">{{ $product->taxons->first()?->name }}</div>
                <h1 class="fw-bold">{{ $product->name }}</h1>
                <div class="display-6 fw-bold mb-3">₹{{ number_format((float) $product->price, 2) }}</div>
                <div class="text-secondary mb-4">{!! nl2br(e($product->description)) !!}</div>
                {{-- @if ($product->variants->count())
                <label class="form-label fw-semibold">Variant</label><select id="variant-id" class="form-select mb-3">
                    <option value="">Select variant</option>
                    @foreach ($product->variants as $variant)
                    <option value="{{ $variant->id }}">{{ $variant->name }} @if ($variant->price)
                        - ₹{{ number_format((float) $variant->price, 2) }}
                        @endif
                    </option>
                    @endforeach
                </select>
                @endif --}}

                @if ($product->variants->count())
                    <label class="form-label fw-semibold">Size</label>
                    <select id="variant-id" class="form-select mb-3">
                        <option value="">Select available sizes</option>

                        @foreach ($product->variants->unique('size') as $variant)
                            <option value="{{ $variant->size }}">
                                {{ $variant->size }}
                            </option>
                        @endforeach
                    </select>
                @endif

                @if ($product->variants->count())
                    <label class="form-label fw-semibold">Colors</label><select id="variant-id" class="form-select mb-3">
                        <option value="">Select available colors</option>
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->color }}">
                                {{ $variant->color }}
                                {{-- @if ($variant->size) --}}
                                {{-- {{ $variant->size }} --}}
                                {{-- @endif --}}
                            </option>
                        @endforeach
                    </select>
                @endif

                <div class="d-flex gap-2">
                    <input id="quantity" type="number" min="1" value="1" class="form-control" style="max-width:100px">
                    @include('shop.widgets.btn-addtocart',['product'=>$product])
                    @include('shop.widgets.addtowishlist', ['product' => $product])
                </div>

                <div class="mt-4 p-3 bg-light rounded"><strong>SKU:</strong> {{ $product->sku }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('shop.widgets.related', [
                    'products' => $related,
                ])
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.js-add-cart[data-detail-page="1"]', function (e) {
            e.preventDefault();
            const b = $(this);
            // Get quantity
            const quantity = parseInt($('#quantity').val(), 10) || 1;
            // Get variant if it exists
            const variantId = $('#variant-id').val() || '';
            // Update button data
            b.data('quantity', quantity);
            b.data('variant-id', variantId);
            // Update actual data attributes too
            b.attr('data-quantity', quantity);
            b.attr('data-variant-id', variantId);
            console.log('Quantity:', quantity);
            console.log('Variant ID:', variantId);
            alert(quantity);
        });
    </script>
@endsection
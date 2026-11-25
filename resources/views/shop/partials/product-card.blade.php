@php $image = $product->getFirstMediaUrl('images','medium') ?: $product->getFirstMediaUrl('thumbnail_images','medium'); @endphp
<div class="card product-card shadow-sm">
    <a href="{{ route('shop.product.show', $product->slug) }}"><img class="card-img-top product-img"
            src="{{ $image ?: asset('images/placeholder.jpg') }}" alt="{{ $product->name }}"></a>
    <div class="card-body d-flex flex-column">
        <div class="small text-muted mb-1">{{ $product->taxons->last()?->name }}</div>
        <h5 class="card-title"><a class="text-decoration-none text-dark"
                href="{{ route('shop.product.show', $product->slug) }}">{{ $product->name }}</a></h5>
        <div class="mt-auto"><span class="price">₹{{ number_format((float) $product->price, 2) }}</span>
            @if ($product->discount)
                <span class="badge text-bg-danger ms-2">Offer</span>
            @endif
            <div class="d-flex gap-2 mt-3">
                {{-- <button type="button" class="btn btn-dark flex-grow-1 js-add-cart"
                    data-product-id="{{ $product->id }}">Add to cart</button> --}}
                @include('shop.widgets.btn-addtocart', ['product', $product])
                @include('shop.widgets.addtowishlist', ['product' => $product])
            </div>
        </div>
    </div>
</div>

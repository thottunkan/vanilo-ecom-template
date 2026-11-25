<div class="card " style="width: 18rem;">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Product Image --}}
    @if ($product->getFirstMediaUrl('images'))
        <img src="{{ $product->getFirstMediaUrl('images') }}" class="card-img-top" alt="{{ $product->name }}">
    @else
        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
            <span class="text-muted">No Image</span>
        </div>
    @endif

    <div class="card-body">

        {{-- Category --}}
        @if ($product->category)
            <small class="text-muted text-uppercase">
                {{ $product->category->name }}
            </small>
        @endif

        {{-- Product Name --}}
        <h5 class="card-title mt-2">
            {{ $product->name }}
        </h5>

        {{-- Rating --}}
        <div class="mb-2">
            <span class="text-warning">★★★★★</span>
            <small class="text-muted">
                ({{ $product->reviews_count ?? 0 }})
            </small>
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <span class="fw-bold fs-5">
                ${{ number_format($product->price, 2) }}
            </span>

            @if ($product->old_price)
                <span class="text-muted text-decoration-line-through ms-2">
                    ${{ number_format($product->old_price, 2) }}
                </span>
            @endif
        </div>

        {{-- Discount --}}
        @if ($product->discount)
            <span class="badge bg-danger mb-3">
                -{{ $product->discount }}%
            </span>
        @endif

        @include('shop.widgets.btn-addtocart', ['product' => $product])
        @include('shop.widgets.addtowishlist', ['product' => $product])
    </div>
</div>

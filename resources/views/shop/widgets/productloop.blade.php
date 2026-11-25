@forelse($products as $product)
    <div class="col-sm-6 col-xl-4">
        @include('shop.partials.product-card', ['product' => $product])
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-light">No products match your filters.</div>
    </div>
@endforelse

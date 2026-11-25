<button type="button" class="btn btn-sm add-to-wishlist" data-product-id="{{ $product->id }}" data-variant-id=""
    data-quantity="1">

    @if ($product->isInWishlist())
        ❤️
    @else
        🤍
    @endif
</button>

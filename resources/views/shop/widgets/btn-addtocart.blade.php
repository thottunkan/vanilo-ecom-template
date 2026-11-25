{{-- <button type="button" id="add-to-cart" class="btn btn-primary btn-sm add-to-cart" data-product-id="{{ $product->id }}"
     data-variant-id="" data-quantity="1">
     <i class="ti ti-shopping-cart"></i>
        Add to Cart
</button> --}}

@if ($product->isincart())
    <button class="btn btn-danger flex-grow-1 js-remove-cart" data-product-id="{{ $product->id }}" data-detail-page="1"
        data-quantity="1">
        Remove
    </button>
@else
    <button class="btn btn-warning flex-grow-1 js-add-cart" data-product-id="{{ $product->id }}" data-detail-page="1"
        data-quantity="1">
        Add to Cart
    </button>
@endif

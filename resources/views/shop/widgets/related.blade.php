{{-- resources/views/shop/widgets/related.blade.php --}}

@if ($products->isNotEmpty())
    <section class="related-products py-4">
        <div class="container-fluid px-0">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Related Products</h3>
            </div>

            <div class="related-products-scroller">
                @foreach ($products as $product)
                    <div class="related-product-item">
                        @include('shop.partials.product-card', [
                            'product' => $product
                        ])
                    </div>
                @endforeach
            </div>

        </div>
    </section>
@endif

<style>
    .related-products-scroller {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 5px 4px 15px;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
    }

    .related-products-scroller::-webkit-scrollbar {
        height: 7px;
    }

    .related-products-scroller::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .related-products-scroller::-webkit-scrollbar-thumb {
        background: #aaa;
        border-radius: 10px;
    }

    .related-product-item {
        flex: 0 0 280px;
        width: 280px;
    }

    .related-product-item .product-card {
        height: 100%;
    }

    @media (max-width: 767.98px) {
        .related-product-item {
            flex: 0 0 220px;
            width: 220px;
        }
    }
</style>

{{-- resources/views/shop/widgets/review.blade.php --}}

<section class="product-reviews mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Customer Reviews</h3>

        @if ($reviews->count())
            <span class="text-muted small">
                {{ $reviews->count() }}
                {{ Str::plural('review', $reviews->count()) }}
            </span>
        @endif
    </div>

    @if ($reviews->isNotEmpty())

        <div class="reviews-list">

            @foreach ($reviews as $review)
                <div class="review-item border-bottom pb-4 mb-4">

                    <div class="d-flex justify-content-between align-items-start">

                        <div class="d-flex align-items-center gap-3">

                            <div class="review-avatar">
                                {{ strtoupper(substr($review->user?->name ?? 'U', 0, 1)) }}
                            </div>

                            <div>
                                <h6 class="mb-1">
                                    {{ $review->user?->name ?? 'Customer' }}
                                </h6>

                                <div class="review-rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= (int) $review->rating)
                                            <span class="text-warning">★</span>
                                        @else
                                            <span class="text-muted">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                        </div>

                        <small class="text-muted">
                            {{ $review->created_at?->diffForHumans() }}
                        </small>

                    </div>

                    @if (!empty($review->title))
                        <h6 class="mt-3 mb-2">
                            {{ $review->title }}
                        </h6>
                    @endif

                    @if (!empty($review->comment))
                        <p class="text-muted mb-0">
                            {{ $review->comment }}
                        </p>
                    @endif

                </div>
            @endforeach

        </div>

    @else

        <div class="text-center border rounded p-5">
            <div class="fs-1 text-muted mb-3">★</div>

            <h5>No reviews yet</h5>

            <p class="text-muted mb-0">
                Be the first customer to review this product.
            </p>
        </div>

    @endif

</section>

<style>
    .product-reviews {
        width: 100%;
    }

    .review-item:last-child {
        border-bottom: 0 !important;
        margin-bottom: 0 !important;
    }

    .review-avatar {
        width: 42px;
        height: 42px;
        min-width: 42px;
        border-radius: 50%;
        background: #212529;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .review-rating {
        font-size: 15px;
        line-height: 1;
        letter-spacing: 1px;
    }

    .review-item p {
        line-height: 1.7;
    }

    @media (max-width: 575.98px) {
        .review-item > .d-flex {
            gap: 10px;
        }

        .review-item small {
            font-size: 11px;
        }
    }
</style>

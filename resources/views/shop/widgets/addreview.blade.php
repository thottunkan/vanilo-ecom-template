{{-- resources/views/shop/widgets/addreview.blade.php --}}

@auth
    <section class="add-review mt-5">

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <h4 class="mb-1">Write a Review</h4>
                <p class="text-muted mb-4">
                    Share your experience with this product.
                </p>

                <form action="{{ route('shop.product.review.store', $product->slug) }}" method="POST">
                    @csrf

                    {{-- Rating --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Your Rating <span class="text-danger">*</span>
                        </label>

                        <div class="star-rating">
                            @for ($i = 5; $i >= 1; $i--)
                                <input type="radio" name="rating" id="rating-{{ $i }}"
                                    value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>

                                <label for="rating-{{ $i }}"
                                    title="{{ $i }} {{ Str::plural('Star', $i) }}">
                                    ★
                                </label>
                            @endfor
                        </div>

                        @error('rating')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Review title --}}
                    <div class="mb-3">
                        <label for="review-title" class="form-label fw-semibold">
                            Review Title
                        </label>

                        <input type="text" id="review-title" name="title"
                            class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                            maxlength="255" placeholder="Give your review a title">

                        @error('title')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Review comment --}}
                    <div class="mb-4">
                        <label for="review-comment" class="form-label fw-semibold">
                            Your Review <span class="text-danger">*</span>
                        </label>

                        <textarea id="review-comment" name="comment" rows="5" class="form-control @error('comment') is-invalid @enderror"
                            maxlength="2000" placeholder="What did you like or dislike about this product?">{{ old('comment') }}</textarea>

                        @error('comment')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark px-4">
                        Submit Review
                    </button>

                </form>

            </div>
        </div>

    </section>
@else
    <section class="add-review mt-5">
        <div class="alert alert-light border d-flex justify-content-between align-items-center">
            <span>
                Please login to write a review.
            </span>

            <a href="{{ route('login') }}" class="btn btn-dark btn-sm">
                Login
            </a>
        </div>
    </section>
@endauth

<style>
    .add-review .form-control {
        border-radius: 8px;
    }

    .add-review .form-control:focus {
        border-color: #212529;
        box-shadow: 0 0 0 0.15rem rgba(33, 37, 41, 0.1);
    }

    .star-rating {
        display: inline-flex;
        flex-direction: row;
        gap: 3px;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #ced4da;
        cursor: pointer;
        font-size: 32px;
        line-height: 1;
        transition: color 0.15s ease;
    }

    /* Because inputs are rendered 5 -> 1,
       selecting a star highlights it and all stars to its right. */
    .star-rating label:hover,
    .star-rating label:hover~label,
    .star-rating input:checked~label {
        color: #ffc107;
    }

    @media (max-width: 575.98px) {
        .add-review .card-body {
            padding: 1.25rem !important;
        }

        .star-rating label {
            font-size: 28px;
        }
    }
</style>

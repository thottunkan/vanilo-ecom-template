@extends('shop.layout.shop-layout')

@section('title', 'Checkout')

@section('content')
    <div class="container py-5">
        <h1 class="section-title mb-4">Checkout</h1>

        <form method="POST" action="{{ route('shop.checkout.store') }}">
            @csrf

            <div class="row g-4">
                {{-- Delivery Details --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-3">Delivery details</h5>

                            {{-- Shipping Address --}}
                            <div class="mb-3">
                                <label for="shipping_address" class="form-label">
                                    Shipping address
                                </label>

                                <textarea
                                    id="shipping_address"
                                    class="form-control @error('shipping_address') is-invalid @enderror"
                                    name="shipping_address"
                                    rows="4"
                                    placeholder="Shipping address"
                                    required
                                >{{ old('shipping_address') }}</textarea>

                                @error('shipping_address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Shipping Phone --}}
                            <div class="mb-3">
                                <label for="shipping_phone" class="form-label">
                                    Phone
                                </label>

                                <input
                                    id="shipping_phone"
                                    type="tel"
                                    class="form-control @error('shipping_phone') is-invalid @enderror"
                                    name="shipping_phone"
                                    value="{{ old('shipping_phone') }}"
                                    placeholder="Phone number"
                                    required
                                >

                                @error('shipping_phone')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Billing Address --}}
                            <div class="mb-3">
                                <label for="billing_address" class="form-label">
                                    Billing address
                                </label>

                                <textarea
                                    id="billing_address"
                                    class="form-control @error('billing_address') is-invalid @enderror"
                                    name="billing_address"
                                    rows="4"
                                    placeholder="Billing address"
                                >{{ old('billing_address') }}</textarea>

                                @error('billing_address')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="mb-3">Order summary</h5>

                            @forelse ($items as $item)
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span>
                                        {{ $item->getBuyable()?->name ?? 'Product' }}
                                        × {{ $item->quantity }}
                                    </span>

                                    <strong>
                                        ₹{{ number_format((float) $item->getBuyable()?->price * $item->quantity, 2) }}
                                    </strong>
                                </div>
                            @empty
                                <p class="text-muted mb-0">
                                    Your cart is empty.
                                </p>
                            @endforelse

                            @if (!empty($items))
                                <hr>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Subtotal</span>
                                    <strong>
                                        ₹{{ number_format((float) $subtotal, 2) }}
                                    </strong>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Tax total</span>
                                    <strong>
                                        ₹{{ number_format((float) $taxtotal, 2) }}
                                    </strong>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between fs-5">
                                    <span>Total</span>

                                    <strong>
                                        ₹{{ number_format((float) $total, 2) }}
                                    </strong>
                                </div>
                            @endif

                            <button
                                type="submit"
                                class="btn btn-dark w-100 mt-3"
                                @disabled(empty($items))
                            >
                                Place order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@extends('shop.layout.shop-layout')
@section('title', 'Cart')
@section('content')
    <div class="container py-5">
        <h1 class="section-title mb-4">Your cart</h1>@if($items->count())
            <div class="row g-4">
                <div class="col-lg-8">
                    @foreach($items as $item)@php $p = $item->getBuyable();
                        $image = $p?->getFirstMediaUrl('images', 'thumb') ?: $p?->getFirstMediaUrl('thumbnail_images', 'thumb'); @endphp
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="row align-items-center g-3">
                                    <div class="col-3 col-md-2"><img class="img-fluid rounded"
                                            src="{{ $image ?: asset('images/placeholder.jpg') }}"></div>
                                    <div class="col-9 col-md-4">
                                        <h5 class="mb-1">{{ $p?->name ?? 'Product' }}</h5><small
                                            class="text-muted">₹{{ number_format((float) $item->price, 2) }} each</small>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <form method="POST" action="{{ route('shop.cart.update', $item->id) }}">@csrf
                                            @method('PATCH')
                                            <div class="input-group"><input class="form-control" name="quantity" type="number"
                                                    min="1" value="{{ $item->quantity }}"><button
                                                    class="btn btn-outline-dark">Update</button></div>
                                        </form>
                                    </div>
                                    <div class="col-3 col-md-2 fw-bold">
                                        ₹{{ number_format((float) $item->price * (int) $item->quantity, 2) }}</div>
                                    <div class="col-3 col-md-1">
                                        <form method="POST" action="{{ route('shop.cart.remove', $item->id) }}">@csrf
                                            @method('DELETE')<button class="btn btn-sm btn-outline-danger">×</button></form>
                                    </div>
                                </div>
                            </div>
                    </div>@endforeach
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5>Summary</h5>
                            <div class="d-flex justify-content-between">
                                <span>Subtotal</span><strong>₹{{ number_format((float) $subtotal, 2) }}</strong>
                               
                            </div>
                            <div class="d-flex justify-content-between">
                                 <span>Tax total</span><strong>₹{{ number_format((float) $taxtotal, 2) }}</strong>
                            </div>
                            <hr>
                            
                            <div class="d-flex justify-content-between fs-5">
                                <span>Total</span><strong>₹{{ number_format((float) $total, 2) }}</strong></div><a
                                href="{{ route('shop.checkout') }}" class="btn btn-dark w-100 mt-3">Proceed to checkout</a>
                            <form method="POST" action="{{ route('shop.cart.clear') }}">@csrf @method('DELETE')<button
                                    class="btn btn-link w-100 text-danger mt-2">Clear cart</button></form>
                        </div>
                    </div>
                </div>
        </div>@else<div class="text-center py-5">
            <h3>Your cart is empty.</h3><a href="{{ route('shop.products') }}" class="btn btn-dark mt-3">Continue
                shopping</a>
        </div>@endif
    </div>
@endsection
@extends('shop.layout.shop-layout')
@section('title', 'My Account')
@section('content')<div class="container py-5">
        <h1 class="section-title">My Account</h1>
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">
                <h5>{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <div class="mt-3">
            <a class="btn btn-outline-dark" href="{{ route('shop.cart.index') }}">View cart</a>
            <a class="btn btn-outline-dark ms-2" href="{{ route('shop.wishlist') }}">Wishlist</a>
            <a class="btn btn-outline-dark ms-2" href="{{ route('shop.orders') }}">Orders</a>
            <a class="btn btn-outline-dark ms-2" href="{{ route('shop.wishlist') }}">Saved address</a>
            <a class="btn btn-outline-dark ms-2" href="{{ route('shop.showchange-password') }}">Change password</a>
        </div>
</div>
@endsection

@extends('shop.layout.shop-layout')
@section('title', 'Order placed')
@section('content')
    <div class="container py-5 text-center">
        <div class="display-1">✓</div>
        <h1 class="fw-bold">Order placed successfully</h1>
        <p class="text-muted">Your order #{{ $orderId }} has been received.</p><a class="btn btn-dark"
            href="{{ route('shop.products') }}">Continue shopping</a>
</div>@endsection
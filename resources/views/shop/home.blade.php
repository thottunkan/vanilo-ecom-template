@extends('shop.layout.shop-layout')
@section('title', $site?->company_name ?? 'Shop')
@section('content')
    <section class="hero py-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-7"><span class="badge bg-light text-dark mb-3">NEW COLLECTION</span>
                    <h1 class="display-4 fw-bold">Shop products you love.</h1>
                    <p class="lead text-white-50">Browse categories, discover featured products and checkout securely.</p><a
                        class="btn btn-light btn-lg" href="{{ route('shop.products') }}">Shop now</a>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="display-1">🛍️</div>
                </div>
            </div>
        </div>
    </section>

    {{-- {{ $content }} --}}
    <div class="container py-5">
        <h2 class="section-title mb-4">Shop by category</h2>
        <div class="row g-3 mb-5">
            @foreach ($categories as $category)
                <div class="col-6 col-md-3"><a
                        class="category-tile d-flex align-items-center justify-content-center text-decoration-none text-dark shadow-sm p-3"
                        href="{{ route('shop.category', $category->slug . '?&categories='.$category->slug) }}"><span
                            class="fw-semibold">{{ $category->name }}</span></a></div>
            @endforeach
        </div>
        {{-- <h2 class="section-title mb-4">Featured products</h2>
        <div class="row g-4 mb-5">@forelse($featuredProducts as $product)<div class="col-12 col-sm-6 col-lg-3">
                @include('shop.partials.product-card',['product'=>$product])</div>@empty<div class="col-12">
                <div class="alert alert-light">No featured products available.</div>
            </div>@endforelse</div> --}}
        <h2 class="section-title mb-4">Latest products</h2>
        <div class="row g-4">
            @foreach ($latestProducts as $product)
                <div class="col-12 col-sm-6 col-lg-3">@include('shop.partials.product-card', ['product' => $product])</div>
            @endforeach
        </div>
    </div>
@endsection

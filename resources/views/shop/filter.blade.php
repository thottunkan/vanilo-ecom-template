{{-- <!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Filter page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h1>Filter!</h1>

    <div class="row g-4">
        @foreach ($products as $product)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                @include('shop.widgets.product-card', ['product' => $product])
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js"
    integrity="sha512-8LENNbXmzI/Gbj+OwXmqR6V4QaUAw0/porPzy1+dQoJqC0JPHedWoe0DDOTL2uHA5XXJyIsPtiMHH86pVlay6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    window.routes = {
        addToCart: "{{ route('api.cart.add') }}",
        addToWishlist: "{{ route('shop.add-to-wishlist') }}",
    };
</script>
<script>
    window.csrfToken = "{{ csrf_token() }}";
</script>

<script src="{{ asset('app-scripts/shop/addtocart.js') }}"></script>
<script src="{{ asset('app-scripts/shop/addtowishlist.js') }}"></script>

</html> --}}
@extends('shop.layout.shop-layout')
@section('title', 'Products')
@section('content')<div class="container py-5">
        <h1 class="section-title mb-4">Products</h1>
        <div class="row g-4">
            @foreach ($products as $product)
                <div class="col-sm-6 col-lg-3">@include('shop.partials.product-card', ['product' => $product])</div>
            @endforeach
        </div>
</div>@endsection

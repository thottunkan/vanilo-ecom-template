@php($site = $site ?? \App\Models\SiteInfo::first())
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $site?->company_name ?? 'Vanilo Shop')</title>
    <link rel="icon" href="{{ $site?->favicon_path ? asset('storage/' . $site->favicon_path) : '' }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cloudflare.com">

    <style>
        body {
            background: #f8f9fa;
            color: #212529
        }

        .navbar-brand {
            font-weight: 800
        }

        .hero {
            background: linear-gradient(135deg, #111, #343a40);
            color: #fff
        }

        .product-card {
            height: 100%;
            border: 0;
            transition: .2s
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 .75rem 1.5rem rgba(0, 0, 0, .1)
        }

        .product-img {
            height: 260px;
            object-fit: cover
        }

        .price {
            font-weight: 800
        }

        .old-price {
            text-decoration: line-through;
            color: #888
        }

        .section-title {
            font-weight: 800
        }

        .category-tile {
            min-height: 130px;
            background: #fff;
            border-radius: 16px
        }

        .toast-container {
            z-index: 1080
        }
    </style>
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">

            @include('shop.widgets.navbar-brand')

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#shopNav"><span
                    class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="shopNav">
                <form class="d-flex mx-lg-auto my-3 my-lg-0" action="{{ route('shop.search') }}" method="GET">
                    <input class="form-control" name="q" value="{{ request('q') }}"
                        placeholder="Search products...">
                    <button class="btn btn-dark ms-2">Search</button>
                </form>
                <ul class="navbar-nav ms-lg-3 align-items-lg-center gap-lg-2">
                    <li class="nav-item"><a class="nav-link" href="{{ route('shop.products') }}">Shop</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.wishlist') }}">
                            Wishlist (<span id="wishlist-count">{{ (int) \App\Models\Wishlist::itemCount() }}</span>)
                        </a>
                    </li>

                    <li class="nav-item"><a class="nav-link" href="{{ route('shop.orders') }}">Orders</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.cart.index') }}">
                            Cart
                            (<span id="cart-count">{{ \Vanilo\Cart\Facades\Cart::itemCount() }}
                            </span>)
                        </a>
                    </li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('shop.account') }}">Account</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('shop.logout') }}">@csrf<button
                                    class="btn btn-link nav-link">Logout</button></form>
                        </li>
                    @else
                        <li class="nav-item"><a class="btn btn-dark btn-sm px-3" href="{{ route('shop.login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if (session('success') || session('error'))
        <div class="container mt-3">
            <div class="alert alert-{{ session('success') ? 'success' : 'danger' }} alert-dismissible fade show">
                {{ session('success') ?? session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @yield('content')

    <footer class="bg-dark text-white mt-5 py-5">
        <div class="container">
            <div class="row g-4 align-items-start">

                {{-- Company --}}
                <div class="col-lg-4 col-md-6">
                    <h5 class="mb-3">
                        {{ $site?->company_name ?? 'Vanilo Shop' }}
                    </h5>

                    <p class="text-white-50 mb-0">
                        {{ $site?->footer_text ?? 'Quality products, simple shopping.' }}
                    </p>
                </div>

                {{-- Menu Links --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="mb-3">Quick Links</h6>

                    <div class="d-flex flex-column gap-2">
                        @include('shop.widgets.menus')
                    </div>
                </div>

                {{-- Policy Links --}}
                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="mb-3">Policies</h6>

                    <div class="d-flex flex-column gap-2">
                        @include('shop.widgets.policies')
                    </div>
                </div>

                {{-- Contact --}}
                <div class="col-lg-4 col-md-12">
                    <h6 class="mb-3">Contact Us</h6>

                    @if ($site?->company_email)
                        <p class="text-white-50 mb-2">
                            {{ $site->company_email }}
                        </p>
                    @endif

                    @if ($site?->company_phone_number)
                        <p class="text-white-50 mb-0">
                            {{ $site->company_phone_number }}
                        </p>
                    @endif
                </div>

            </div>

            <hr class="border-secondary my-4">

            <div class="text-center text-md-start">
                <small class="text-white-50">
                    {{ $site?->copyright ?? '© ' . date('Y') . ' Vanilo Shop' }}
                </small>
            </div>
        </div>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        window.shopRoutes = {
            cartCount: "{{ route('shop.cart.count') }}",
            wishlistCount: "{{ route('shop.wishlist.count') }}",
            addToCart: "{{ route('shop.cart.add') }}",
            addToWishlist: "{{ route('shop.add-to-wishlist') }}"
        };
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('app-scripts/shop/storefront.js') }}"></script>
    <script src="{{ asset('app-scripts/admin/cart/addtocart.js') }}"></script>
    <script src="{{ asset('app-scripts/shop/addtowishlist.js') }}"></script>

</body>

</html>

@extends('shop.layout.shop-layout')
@section('title', 'Login')
@section('content')<div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="fw-bold mb-4">Sign in</h2>
                        <form method="POST" action="{{ route('shop.login.store') }}">@csrf<input class="form-control mb-3"
                                type="email" name="email" value="{{ old('email') }}" placeholder="Email" required><input
                                class="form-control mb-3" type="password" name="password" placeholder="Password" required>
                            @error('email')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror
                            <button class="btn btn-dark w-100">Login</button>
                        </form>
                        <p class="mt-3 mb-0">Don't have an account? <a href="{{ route('shop.register') }}">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
</div>@endsection

@extends('shop.layout.shop-layout')
@section('title', 'Register')
@section('content')<div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h2 class="fw-bold mb-4">Create account</h2>
                        <form method="POST" action="{{ route('shop.register.store') }}">@csrf<input class="form-control mb-3"
                                name="name" value="{{ old('name') }}" placeholder="Full name" required><input
                                class="form-control mb-3" type="email" name="email" value="{{ old('email') }}"
                                placeholder="Email" required><input class="form-control mb-3" type="password"
                                name="password" placeholder="Password" required><input class="form-control mb-3"
                                type="password" name="password_confirmation" placeholder="Confirm password" required>
                            @if ($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif
                            <button class="btn btn-dark w-100">Create account</button>
                        </form>
                        <p class="mt-3 mb-0">Already registered? <a href="{{ route('shop.login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
</div>@endsection

<!doctype html>
<html lang="en">
<!-- [Head] start -->

<!-- [Favicon] icon -->
@include('admin.includes.head')


<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-header="header-1" data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true"
    data-pc-direction="ltr" data-pc-theme="light">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <div class="auth-main v1 bg-grd-primary">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">

                        <form action="{{ route('admin.authenticate') }}" method="POST">
                            @csrf

                            <div class="text-center">
                                {{-- <img src="{{ asset('admin-assets/images/logo-dark.svg') }}" alt="logo"
                                    class="img-fluid mb-4" /> --}}

                                <h4 class="f-w-500 mb-1">Login with your email</h4>
                                <br>
                                {{-- <p class="mb-4">
                                    Don't have an Account?
                                    <a href="#" class="link-primary ms-1">
                                        Create Account
                                    </a>
                                </p> --}}
                            </div>


                            <div class="form-group mb-3">
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Email Address" value="{{ old('email') }}" required />

                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group mb-3">
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                    required />

                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="d-flex mt-1 justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input input-primary" type="checkbox" name="remember"
                                        id="customCheckc1" />

                                    <label class="form-check-label text-muted" for="customCheckc1">
                                        Remember me?
                                    </label>
                                </div>

                                <a href="#">
                                    <h6 class="f-w-400 mb-0">
                                        Forgot Password?
                                    </h6>
                                </a>
                            </div>


                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>
                            </div>


                            @if (session('error'))
                                <div class="alert alert-danger mt-3">
                                    {{ session('error') }}
                                </div>
                            @endif


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- [Page Specific JS] start -->
    <script src="{{ asset('admin-assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/world.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/world-merc.js') }}"></script>
    <script src="{{ asset('admin-assets/js/pages/dashboard-sales.js') }}"></script>
    <!-- [Page Specific JS] end -->

    <!-- Required JS -->
    <script src="{{ asset('admin-assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('admin-assets/js/script.js') }}"></script>
    <script src="{{ asset('admin-assets/js/theme.js') }}"></script>
    <script src="{{ asset('admin-assets/js/plugins/feather.min.js') }}"></script>
    <script>
        layout_change('light');
    </script>

    <script>
        layout_sidebar_change('light');
    </script>

    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        preset_change('preset-1');
    </script>

    <script>
        header_change('header-1');
    </script>


</body>
<!-- [Body] end -->

</html>

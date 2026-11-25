
<!doctype html>
<html lang="en">
<!-- [Head] start -->

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
    <!-- [ Sidebar Menu ] start -->
    @include('admin.includes.sidepanel')
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('admin.includes.header')
    <!-- [ Header ] end -->
    @yield('content')
    <!-- [ Main Content ] end -->
    @include('admin.includes.footer')


</body>
<!-- [Body] end -->

</html>
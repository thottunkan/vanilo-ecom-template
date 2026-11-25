
@extends('admin.layout.admin-layout')

@section('content')
<!-- [ Main Content ] start -->
<div class="pc-container">
    <div class="pc-content">
      <!-- [ Main Content ] start -->
      <div class="row">
        <div class="col-md-6 col-xl-3">
            @include('admin.widgets.dashboard.cards.received-orders')
        </div>
        <div class="col-md-6 col-xl-3">
            @include('admin.widgets.dashboard.cards.total-sale')
        </div>
        <div class="col-md-6 col-xl-3">
            @include('admin.widgets.dashboard.cards.total-revenue')
        </div>

        <div class="col-md-6 col-xl-3">
          @include('admin.widgets.dashboard.cards.total-profit')
        </div>
        <div class="col-md-6 col-xl-7">
          <div class="card">
            <div class="card-header">
              <h5>New Order From United States</h5>
            </div>
            <div class="card-body">
              <div id="world-map-markers" class="set-map" style="height: 365px"></div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-xl-5">
          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
              <h5>New Order From United States</h5>
              <div class="dropdown">
                <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="#"
                  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                    class="material-icons-two-tone f-18">more_vert</i></a>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="#">View</a>
                  <a class="dropdown-item" href="#">Edit</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="media align-items-center">
                <div class="avtar avtar-s bg-light-primary flex-shrink-0">
                  <i class="ph ph-money f-20"></i>
                </div>
                <div class="media-body ms-3">
                  <p class="mb-0 text-muted">Total Earnings</p>
                  <h5 class="mb-0">&#8377;249.95</h5>
                </div>
              </div>
              <div id="earnings-users-chart"></div>
            </div>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <div class="media align-items-center">
                    <div class="avtar avtar-s bg-grd-primary flex-shrink-0">
                      <i class="ph ph-money f-20 text-white"></i>
                    </div>
                    <div class="media-body ms-2">
                      <p class="mb-0 text-muted">Total Profit</p>
                      <h6 class="mb-0">&#8377;1,783</h6>
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="media align-items-center">
                    <div class="avtar avtar-s bg-grd-success flex-shrink-0">
                      <i class="ph ph-shopping-cart text-white f-20"></i>
                    </div>
                    <div class="media-body ms-2">
                      <p class="mb-0 text-muted">Product Sold</p>
                      <h6 class="mb-0">15,830</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4 col-sm-6">
            @include('admin.widgets.dashboard.cards.daily-sale')
        </div>
        <div class="col-md-4 col-sm-6">
            @include('admin.widgets.dashboard.cards.monthly-sale')
        </div>
        <div class="col-md-4 col-sm-12">
            @include('admin.widgets.dashboard.cards.yearly-sale')
        </div>
        <!-- Recent Orders start -->
        <div class="col-sm-12">
          <div class="card table-card">
            <div class="card-header">
              <h5>Recent Orders</h5>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                  @include('admin.widgets.dashboard.tables.recent-orders',['orders' => null] )
              </div>
            </div>
          </div>
        </div>
        <!-- Recent Orders end -->
      </div>
      <!-- [ Main Content ] end -->
    </div>
</div>

@endsection

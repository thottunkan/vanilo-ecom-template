@extends('admin.layout.admin-layout')

@section('title', 'Products')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'title' => 'Orders',
                'button_text' => '+Add',
                'url' => route('admin.orders.create'),
                'links' => ['Sales', 'Orders'],
            ])
            <a href="{{ route('admin.orders.products') }}">products</a>
            <!-- [ breadcrumb ] end -->
            <style>
                .table-responsive {
                    overflow-x: auto;
                    white-space: nowrap;
                }
            </style>
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Orders</h5>
                        </div>
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <div class="text-start">
                                    @include('admin.widgets.bulkdelete', [
                                        'route' => route('admin.brand.bulk.delete'),
                                        'selector' => '.order-checkbox',
                                        'buttonText' => 'Delete Selected',
                                    ])
                                    {{-- @include('admin.widgets.dashboard.tables.buttons', [
                                    'exportToPdfRoute' => null,
                                    'exportToExcelRoute' => null,
                                    'importExcelRoute' => null,
                                    'importCSVRoute' => null,
                                    'downloadSampleRoute' => null,
                                    ]) --}}
                                </div>
                                <div class="text-end">
                                    @include('admin.widgets.filters', [
                                        'formUrl' => null,
                                        'clearUrl' => null,
                                    ])
                                </div>
                            </div>
                            <br>
                            <div class="table table-responsive table-hover align-middle" id="tableWrapper">

                                <table class="table table-bordered table-striped align-middle" style="min-width: 2000px;">
                                    <thead>
                                        <tr>
                                            <th width="80">ID</th>
                                            <th>items</th>
                                            <th>order status</th>
                                            <th>grand total</th>
                                            <th>Tax percentage</th>
                                            <th>user email</th>
                                            <th>payment status</th>
                                            <th>transaction id</th>


                                            <th width="180">Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr>
                                                <td><input type="checkbox" name="" class="order-checkbox"
                                                        value=" {{ $order->id }}" id=""></td>
                                                <td>
                                                    @foreach ($order->items as $item)
                                                        {{ $item->name }}<br>
                                                    @endforeach
                                                </td>
                                                <th>{{ $order->status }}</th>
                                                <th>
                                                    @foreach ($order->items as $item)
                                                        {{ ($item->configuration['grand_total']) }}<br>
                                                    @endforeach
                                                </th>
                                                <th>
                                                    @foreach ($order->items as $item)
                                                        {{ ($item->configuration['percentage']) }}<br>
                                                    @endforeach
                                                </th>
                                                <th>
                                                    {{ $order->user->email }}
                                                </th>
                                                <th>null</th>
                                                <th>null</th>
                                                <th>
                                                    
                                                    <a href="" class="btn btn-warning btn-sm">
                                                        <i class="ti ti-edit"></i>
                                                    </a>

                                                    {{-- <form action="" method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this product?')">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form> --}}

                                                </th>
                                            </tr>
                                        @empty
                                            {{-- <tr>
                                                <td colspan="7" class="text-center">
                                                    No records found.
                                                </td>
                                            </tr> --}}
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>

    </div>
    {{-- <div class="mt-3">
        {{ $products->links() }}
    </div> --}}
    <script>
        document.getElementById('tableWrapper').addEventListener('wheel', function (e) {
            if (this.scrollWidth > this.clientWidth) {
                e.preventDefault();
                this.scrollLeft += e.deltaY;
            }
        }, {
            passive: false
        });
    </script>

@endsection
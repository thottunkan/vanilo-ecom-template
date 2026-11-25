@extends('admin.layout.admin-layout')

@section('title', 'Products')

@section('content')

    <div class="pc-container">
        <div class="pc-content">
            @include('admin.includes.page-header', [
                'title' => 'Products for add to cart',
                'button_text' => 'Create',
                'url' => route('admin.product.create'),
                'links' => ['Catalog', 'Products'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Products orders</h5>
                        </div>
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">
                                <div class="text-start">
                                    {{-- @include('admin.widgets.bulkdelete', [
                                    'route' => route('admin.product.products.bulk.delete'),
                                    'selector' => '.product-checkbox',
                                    'buttonText' => 'Delete Selected',
                                    ]) --}}
                                    {{-- @include('admin.widgets.dashboard.tables.buttons', [
                                    'exportToPdfRoute' => null,
                                    'exportToExcelRoute' => route('admin.product.export-to-excel'),
                                    'importExcelRoute' => route('admin.product.import-from-excel'),
                                    'importCSVRoute' => null,
                                    'downloadSampleRoute' => route('admin.product.download-import-excel-template'),
                                    ]) --}}
                                    <a href="#" class="btn btn-sm btn-primary">view cart</a>
                                </div>
                                <div class="text-end">
                                    @include('admin.widgets.filters', [
                                        'formUrl' => route('admin.product-filter.product-filter'),
                                        'clearUrl' => route('admin.product.index'),
                                        'is_stock' => true,
                                    ])
                                </div>
                            </div>

                            <div class="table-responsive" id="tableWrapper">

                                <table id="data-table"
                                    class="table table-bordered table-striped table-hover table-bordered align-middle"
                                    style="min-width:2200px;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>SKU</th>
                                            <th>Slug</th>
                                            <th>Subtitle</th>
                                            <th>Original Price</th>
                                            <th>Price</th>
                                            {{-- <th>Size</th>
                                            <th>Color</th>
                                            <th>Stock</th>
                                            <th>Description</th>
                                            <th>Length</th>
                                            <th>Width</th>
                                            <th>Height</th>
                                            <th>Weight</th>
                                            <th>Status</th>
                                            <th>Variants</th>
                                            <th>Created</th>
                                            <th>Updated</th> --}}
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="product-checkbox" value="{{ $product->id }}">
                                                </td>
                                                <td>
                                                    @if ($product->getFirstMediaUrl('images'))
                                                        <img src="{{ $product->getFirstMediaUrl('images') }}" width="60"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $product->name ?? 'No' }}
                                                </td>
                                                <td>
                                                    {{ $product->sku ?? 'No' }}
                                                </td>
                                                <td>
                                                    {{ $product->slug ?? 'No' }}
                                                </td>
                                                <td>
                                                    {{ $product->subtitle ?? 'No' }}
                                                </td>
                                                <td>
                                                    {{ number_format($product->original_price ?? 0, 2) }}
                                                </td>
                                                <td>
                                                    {{ number_format($product->price ?? 0, 2) }}
                                                </td>

                                                <td>

                                                    <button type="button" class="btn btn-primary btn-sm add-to-cart"
                                                        data-product-id="{{ $product->id }}" data-variant-id=""
                                                        data-quantity="1">
                                                        <i class="ti ti-shopping-cart"></i>
                                                        Add to Cart
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        document
            .getElementById('tableWrapper')
            .addEventListener('wheel', function (e) {
                if (this.scrollWidth > this.clientWidth) {
                    e.preventDefault();
                    this.scrollLeft += e.deltaY;
                }
            }, {
                passive: false
            });
    </script>

    <script>
        $(document).ready(function () {
            $('#data-table').DataTable({
                language: {
                    emptyTable: "No products found."
                },
                columnDefs: [{
                    orderable: false,
                    searchable: false,
                    targets: [0, 8]
                } // checkbox, image, variants, action
                ]
            });
        });
    </script>
    <script>
        window.routes = {
            addToCart: "{{ route('admin.orders.cart.add') }}",
            cartCount: "{{ route('admin.orders.cart.count') }}"
        };
    </script>
    {{-- <script src="{{ asset('app-scripts/shop/addtocart.js') }}"></script> --}}
    <script>
        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault();

            const button = $(this);
            const product_id = button.data('product-id');
            const variant_id = button.data('variant-id') || null;
            const quantity = button.data('quantity') || 1;

            button.prop('disabled', true);

            $.ajax({
                url: "{{ route('admin.orders.cart.add') }}",
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    "Accept": "application/json"
                },
                data: {
                    product_id: product_id,
                    variant_id: variant_id,
                    quantity: quantity
                },
                success: function (response) {

                    button.prop('disabled', false);

                    if (response.success) {
                        button
                            .removeClass("btn-primary")
                            .addClass("btn-success")
                            .html('<i class="ti ti-check"></i> Added');

                        if ($("#cart-count").length) {
                            $.get(window.routes.cartCount, function (res) {
                                $("#cart-count").text(res.count);
                            });
                        }
                        console.log(response);
                        //toastr.success(response.message);
                    } else {
                        //toastr.error(response.message || "Unable to add product.");
                    }
                },
                error: function (xhr) {

                    button.prop('disabled', false);

                    let message = "Something went wrong.";

                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors)
                                .flat()
                                .join("<br>");
                        }
                    }

                    //toastr.error(message);
                }
            });
        });
    </script>

@endsection
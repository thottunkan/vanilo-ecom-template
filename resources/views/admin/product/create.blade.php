@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Create Product',
                'links' => ['Catelog', 'Product', 'Create Product'],
            ])


            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

                <!-- [ sample-page ] start -->
                <div class="col-sm-12">

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Create Product</h5>
                                </div>

                            </div>

                        </div>
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <!-- Nav Tabs -->
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">

                                        <button class="nav-link active" id="nav-basic-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-basic" type="button" role="tab"
                                            aria-controls="nav-basic" aria-selected="true">
                                            Basic Info
                                        </button>

                                        <button class="nav-link" id="nav-optional-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-optional" type="button" role="tab"
                                            aria-controls="nav-optional" aria-selected="false">
                                            Optional Info
                                        </button>

                                        {{-- <button class="nav-link" id="nav-related-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-related" type="button" role="tab"
                                            aria-controls="nav-related" aria-selected="false">
                                            Related Products & Categories
                                        </button> --}}

                                    </div>
                                </nav>

                                <!-- Tab Content -->
                                <div class="tab-content mt-3" id="nav-tabContent">

                                    <!-- Basic Info -->
                                    <div class="tab-pane fade show active" id="nav-basic" role="tabpanel"
                                        aria-labelledby="nav-basic-tab">

                                        @include('admin.widgets.product-basic-info-widget')

                                    </div>

                                    <!-- Optional -->
                                    <div class="tab-pane fade" id="nav-optional" role="tabpanel"
                                        aria-labelledby="nav-optional-tab">

                                        {{-- Master Attributes --}}
                                        @include('admin.widgets.color-size-diamention-widget')

                                        {{-- Related Products --}}
                                        <div class="mb-3">
                                            @include('admin.widgets.related-products-widget', [
                                                'related_products' => $related_products ?? [],
                                            ])
                                        </div>

                                        {{-- Categories --}}
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h5 class="mb-0">Select Categories</h5>
                                            </div>

                                            <div class="card-body">
                                                @include('admin.widgets.add-category-widget')
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            @include('admin.widgets.custom-attribute-widget')
                                        </div>

                                        <hr>

                                        {{-- Variants --}}
                                        @include('admin.widgets.variants-widget')

                                    </div>

                                    <!-- Related Products & Categories -->
                                    <div class="tab-pane fade" id="nav-related" role="tabpanel"
                                        aria-labelledby="nav-related-tab">
                                        inside related
                                        {{-- Related Products --}}
                                        <div class="mb-3">
                                            @include('admin.widgets.related-products-widget', [
                                                'related_products' => $related_products ?? [],
                                            ])
                                        </div>

                                        {{-- Categories --}}
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h5 class="mb-0">Select Categories</h5>
                                            </div>

                                            <div class="card-body">
                                                @include('admin.widgets.add-category-widget')
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create Product
                                    </button>
                                    <a class="btn btn-secondary " href="{{ route('admin.product.index') }}">Cancel</a>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>


    <script>
        $(document).on('change', '#tax_category', function() {



            let categoryId = $(this).val();

            let taxRateSelect = $('#applicable_tax_rates');
            //taxRateSelect.html('<option value="">--choose--</option>');

            if (!categoryId) return;

            const taxRateRoute = "{{ route('admin.taxrate.get_taxrates', ':id') }}";
            let url = taxRateRoute.replace(':id', categoryId);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    //taxRateSelect.html('<option value="">--choose--</option>');

                    $.each(response.data, function(key, rate) {
                        taxRateSelect.append(`
                    <option value="${rate.id}">
                        ${rate.name} (${rate.rate}%)
                    </option>
                `);
                    });
                },
                error: function(xhr) {
                    console.error(xhr.responseText);

                }
            });

        });

        $(document).on('change', '#applicable_tax_rates', function() {
            //console.log( $(this).text());
            //$('#tax_rate').val( $(this).val())
        });
    </script>
    <script src="{{ asset('app-scripts/admin/product/product-create.js') }}"></script>

@endsection

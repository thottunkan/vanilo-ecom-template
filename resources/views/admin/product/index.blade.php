@extends('admin.layout.admin-layout')

@section('title', 'Products')

@section('content')

    <div class="pc-container">
        <div class="pc-content">
            @include('admin.includes.page-header', [
                'title' => 'Products',
                'button_text' => 'Create',
                'url' => route('admin.product.create'),
                'links' => ['Catalog', 'Products'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Products</h5>
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
                                        'route' => route('admin.product.products.bulk.delete'),
                                        'selector' => '.product-checkbox',
                                        'buttonText' => 'Delete Selected',
                                    ])
                                    @include('admin.widgets.dashboard.tables.buttons', [
                                        'exportToPdfRoute' => null,
                                        'exportToExcelRoute' => route('admin.product.export-to-excel'),
                                        'importExcelRoute' => route('admin.product.import-from-excel'),
                                        'importCSVRoute' => null,
                                        'downloadSampleRoute' => route(
                                            'admin.product.download-import-excel-template'),
                                    ])
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

                                <table id="data-table" class="table table-responsive table-hover align-middle"
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
                                            <th>Size</th>
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
                                            <th>Updated</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="product-checkbox"
                                                        value="{{ $product->id }}">
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
                                                    {{ $product->size ?? 'No' }}
                                                </td>
                                                <td>
                                                    {{ $product->color ?? 'No' }}
                                                </td>
                                                <td>
                                                    @if ($product->stock > 0)
                                                        <span class="fw-bold" style="color: green;"> In-Stock
                                                            ({{ $product->stock ?? 'field empty' }})</span>
                                                    @else
                                                        <span class="fw-bold" style="color: red;">Out of Stock
                                                            ({{ $product->stock ?? 'field empty' }})</span>
                                                    @endif

                                                </td>
                                                <td>
                                                    {{ Str::limit(strip_tags($product->description ?? ''), 50) }}
                                                </td>
                                                <td>{{ $product->length ?? 'No' }}</td>
                                                <td>{{ $product->width ?? 'No' }}</td>
                                                <td>{{ $product->height ?? 'No' }}</td>
                                                <td>{{ $product->weight ?? 'No' }}</td>
                                                <td>
                                                    @if ($product->state == 'active')
                                                        <span class="badge bg-success">
                                                            Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            {{ ucfirst($product->state ?? 'unknown') }}
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Variants --}}

                                                <td>

                                                    <div class="accordion" id="variantAccordion{{ $product->id }}">
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="heading{{ $product->id }}">
                                                                <button class="accordion-button collapsed" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#collapse{{ $product->id }}">
                                                                    View variants
                                                                    ({{ $product->variants->count() }})
                                                                </button>
                                                            </h2>

                                                            <div id="collapse{{ $product->id }}"
                                                                class="accordion-collapse collapse"
                                                                data-bs-parent="#variantAccordion{{ $product->id }}">
                                                                <div class="accordion-body">
                                                                    @forelse($product->variants as $variant)
                                                                        <table
                                                                            class="table table-sm table-bordered variant-table">
                                                                            <tr>
                                                                                <th>Color</th>
                                                                                <td>
                                                                                    {{ $variant->color ?? 'NO' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Size</th>
                                                                                <td>
                                                                                    {{ $variant->size ?? 'NO' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Price</th>
                                                                                <td>
                                                                                    {{ $variant->price ?? 'NO' }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Created</th>
                                                                                <td>
                                                                                    {{ $variant->created_at?->format('d M Y') }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Updated</th>
                                                                                <td>
                                                                                    {{ $variant->updated_at?->format('d M Y') }}
                                                                                </td>
                                                                            </tr>
                                                                        </table>

                                                                    @empty
                                                                        No Variants
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ $product->created_at?->format('d M Y') }}
                                                </td>
                                                <td>
                                                    {{ $product->updated_at?->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.product.edit', $product->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="ti ti-edit"></i>
                                                    </a>

                                                    <form action="{{ route('admin.product.delete', $product->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            onclick="return confirm('Are you sure you want to delete this item?')"
                                                            class="btn btn-danger btn-sm">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
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
            </div>

        </div>
    </div>

    <script>
        document
            .getElementById('tableWrapper')
            .addEventListener('wheel', function(e) {
                if (this.scrollWidth > this.clientWidth) {
                    e.preventDefault();
                    this.scrollLeft += e.deltaY;
                }
            }, {
                passive: false
            });
    </script>

    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                language: {
                    emptyTable: "No products found."
                },
                columnDefs: [{
                        orderable: false,
                        searchable: false,
                        targets: [0, 1, 17, 20]
                    } // checkbox, image, variants, action
                ]
            });
        });
    </script>

@endsection

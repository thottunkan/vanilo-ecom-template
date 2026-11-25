@extends('admin.layout.admin-layout')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.category.create'),
                'title' => 'Categories',
                'links' => ['Catelog', 'Product', 'Categories'],
            ])
            <!-- [ breadcrumb ] end -->

            <div class="row ">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Tabs Navigation -->
                            <ul class="nav  nav-tabs mb-3 white-tabs" id="categoryTabs" role="tablist">

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="table-view-tab" data-bs-toggle="tab"
                                        data-bs-target="#table-view" type="button" role="tab">
                                        Table View
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tree-view-tab" data-bs-toggle="tab"
                                        data-bs-target="#tree-view" type="button" role="tab">
                                        Tree View
                                    </button>
                                </li>
                            </ul>

                            <!-- Tabs Content -->
                            <div class="tab-content" id="categoryTabsContent">

                                <!-- Table View -->
                                <div class="tab-pane fade show active" id="table-view" role="tabpanel">
                                    <div class="d-flex justify-content-between">
                                        <div class="text-start">
                                            @include('admin.widgets.bulkdelete', [
                                                'route' => route('admin.category.bulk.delete'),
                                                'selector' => '.category-checkbox',
                                                'buttonText' => 'Delete Selected',
                                            ])
                                            @include('admin.widgets.dashboard.tables.buttons', [
                                                'exportToPdfRoute' => route('admin.category.export-to-pdf'),
                                                'exportToExcelRoute' => route('admin.category.export-to-excel'),
                                                'importExcelRoute' => route('admin.category.import-excel'),
                                                'importCSVRoute' => route('admin.category.import-excel'),
                                                'downloadSampleRoute' => route('admin.category.download-template-excel'),
                                            ])
                                        </div>
                                        <div class="text-end">
                                            @include('admin.widgets.filters', [
                                                'formUrl' => route('admin.category-filter.category-filter'),
                                                'clearUrl' => null,
                                            ])
                                        </div>
                                    </div>

                                    <table id="data-table" class="table table-responsive table-hover align-middle">

                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Parent Category</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @forelse($categories_for_tableview as $category)
                                                <tr>
                                                    <td><input type="checkbox" name="" class="category-checkbox"
                                                            value="{{ $category->id }}" id=""></td>

                                                    <td>
                                                        @if (!empty($category->image_path))
                                                            <img width="40" height="40" class="rounded"
                                                                src="{{ asset($category->image_path) }}"
                                                                alt="{{ $category->name }}">
                                                        @else
                                                            <span class="text-muted">No Image</span>
                                                        @endif
                                                    </td>

                                                    <td>{{ $category->name }}</td>

                                                    <td>
                                                        {{ $category->parent?->name ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $category->created_at?->format('d M Y') ?? '-' }}
                                                    </td>

                                                    <td>
                                                        {{ $category->updated_at?->format('d M Y') ?? '-' }}
                                                    </td>

                                                    <td>
                                                        <div class="d-flex gap-2">

                                                            <a href="{{ route('admin.category.edit', $category->id) }}"
                                                                class="btn btn-sm btn-warning">
                                                                <i class="ti ti-edit"></i>
                                                            </a>

                                                            <form method="POST"
                                                                action="{{ route('admin.category.delete', $category->id) }}"
                                                                onsubmit="return confirm('Are you sure you want to delete this item?')">

                                                                @csrf
                                                                @method('DELETE')

                                                                <button class="btn btn-sm btn-danger">
                                                                    <i class="ti ti-trash"></i>
                                                                </button>

                                                            </form>

                                                        </div>
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


                                <!-- Tree View -->
                                <div class="tab-pane fade" id="tree-view" role="tabpanel">

                                    <label class="fw-semibold mb-2">
                                        Category Tree
                                    </label>

                                    <ul class="list-unstyled ps-0">

                                        @include('admin.partials.category-tree', [
                                            'categories' => $categories,
                                            'selected_categories' => $selected_categories ?? [],
                                        ])

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    {{-- <div class="mt-3">
        {{ $products->links() }}
    </div> --}}

    <style>
        .white-tabs {
            background-color: #ffffff;
            border-bottom: 1px solid #dee2e6;
        }

        .white-tabs .nav-link {
            background-color: #ffffff;
            color: #212529;
            border: 1px solid transparent;
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
        }

        .white-tabs .nav-link:hover {
            background-color: #ffffff;
            border-color: #e9ecef #e9ecef #dee2e6;
            color: #0d6efd;
        }

        .white-tabs .nav-link.active {
            background-color: #ffffff;
            color: #0d6efd;
            border-color: #dee2e6 #dee2e6 #ffffff;
            border-bottom: 2px solid #0d6efd;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                columnDefs: [{
                        targets: 0,
                        orderable: false,
                        searchable: false
                    }, // checkbox
                    {
                        targets: 1,
                        orderable: false,
                        searchable: false
                    }, // image
                    {
                        targets: 6,
                        orderable: false,
                        searchable: false
                    } // actions
                ]
            });
        });
    </script>
@endsection
{{--
@include('admin.partials.category-tree', ['categories' => $categories]) --}}

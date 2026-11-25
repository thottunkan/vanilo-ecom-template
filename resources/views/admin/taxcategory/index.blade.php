@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.taxcategory.create'),
                'title' => 'Tax Categories',
                'links' => ['Sales', 'Tax', 'Tax Categories'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tax Categories</h5>
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
                            <div class="d-flex justify-content-between">
                                <div class="text-start">
                                    @include('admin.widgets.bulkdelete', [
                                        'route' => route('admin.brand.bulk.delete'),
                                        'selector' => '.tax-category-checkbox',
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

                            {{-- content --}}
                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">#</th>
                                        <th>name</th>
                                        <th>type</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th>Action</th>
                                        {{-- <th>Status</th>
                                        <th width="180">Actions</th> --}}
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($tax_categories as $tax_category)
                                        <tr>
                                            <td><input type="checkbox" name="" class="tax-category-checkbox"
                                                    id="" value=" {{ $tax_category->id }}"></td>
                                            <td>{{ $tax_category->name }}</td>
                                            <td>{{ $tax_category->type }}</td>
                                            <td>{{ $tax_category->created_at->format('D M Y') }}</td>
                                            <td>{{ $tax_category->updated_at->format('D M Y') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ route('admin.taxcategory.edit', $tax_category->id) }}"
                                                        class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></a>
                                                    &nbsp;&nbsp;
                                                    <form
                                                        action="{{ route('admin.taxcategory.delete', $tax_category->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this item?')">
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
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable();
        });
    </script>
@endsection

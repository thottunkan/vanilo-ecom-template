@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.blog-category.create'),
                'title' => 'Blog categories',
                'links' => ['Blog', 'Blog categories'],
            ])
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Blog categories</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-start">
                                @include('admin.widgets.bulkdelete', [
                                    'route' => route('admin.blog-category.bulk.delete'),
                                    'selector' => '.blog-categoroy-checkbox',
                                    'buttonText' => 'Delete Selected',
                                ])
                                {{-- @include('admin.widgets.dashboard.tables.buttons', [
                                    'exportToPdfRoute' => null,
                                    'exportToExcelRoute' => null,
                                    'importExcelRoute' => null,
                                    'importCSVRoute' => null,
                                    'downloadSampleRoute' => null
                                ]) --}}
                            </div>
                            <div class="text-end">
                                @include('admin.widgets.filters', [
                                    'formUrl' => route('admin.blog-category-filter.blog-category-filter'),
                                    'clearUrl' => null,
                                ])
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">
                                            #
                                            {{-- <input type="checkbox" name="" id=""> --}}
                                        </th>
                                        <th>name</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($blog_categories as $tr)
                                        <tr>
                                            <td><input type="checkbox" value="{{ $tr->id }}"
                                                    class="blog-categoroy-checkbox" name="" id=""></td>
                                            <td>{{ $tr->name }}</td>
                                            <td>
                                                {{ $tr->created_at->format('D M Y') }}
                                            </td>
                                            <td>
                                                {{ $tr->updated_at->format('D M Y') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.brand.edit', $tr->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.brand.delete', $tr->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this product?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm">
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
                            {{-- content --}}
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

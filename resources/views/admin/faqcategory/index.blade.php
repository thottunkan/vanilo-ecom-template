@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.faq-category.create'),
                'title' => 'FAQ Categories',
                'links' => ['FAQ Categories'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>FAQ Categories</h5>
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
                                        'route' => route('admin.faq.bulk.delete'),
                                        'selector' => '.faq-category-checkbox',
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
                                        'formUrl' => route('admin.faq-filter.faq-filter'),
                                        'clearUrl' => route('admin.faq.index'),
                                    ])
                                </div>
                            </div>
                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">
                                            #
                                        </th>
                                        <th>category name</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th>Status</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($faqcategory as $f)
                                        <tr>
                                            <td><input type="checkbox" class="faq-category-checkbox" name=""
                                                    value="{{ $f->id }}" id="">
                                            </td>
                                            <td>{{ $f->name ?? '' }}</td>
                                            
                                            <td>{{ $f->created_at->format('D M Y') }}</td>
                                             <td>{{ $f->updated_at->format('D M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ ($f->status ?? '') == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $f->status ?? '' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.faq-category.edit', $f->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.faq.delete', $f->id) }}" method="POST"
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

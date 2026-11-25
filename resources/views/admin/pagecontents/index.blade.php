@extends('admin.layout.admin-layout')

@section('title', 'Products')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'title' => 'Pages',
                'button_text' => null,
                'url' => route('admin.pagecontent.create'),
                'links' => ['Pages'],
            ])
            <!-- [ breadcrumb ] end -->
            {{-- <style>
                .table-responsive {
                    overflow-x: auto;
                    white-space: nowrap;
                }
            </style> --}}
            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Pages</h5>
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
                                        'selector' => '.page-checkbox',
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
                                        'formUrl' => route('admin.pagecontent-filter.pagecontent-filter'),
                                        'clearUrl' => route('admin.pagecontent.index'),
                                    ])
                                </div>
                            </div>

                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">#</th>
                                        <th>Name</th>
                                        <th>status</th>
                                        {{-- <th>created_at</th>
                                        <th>updated</th> --}}
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($pages as $p)
                                        <tr>
                                            <td> <input type="checkbox" name="" class="page-checkbox" id=""
                                                    value="{{ $p->id }}"> </td>
                                            <td>{{ $p->page_name ?? '' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ ($p->status ?? '') == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $p->status ?? '' }}
                                                </span>
                                            </td>
                                            <td>

                                                <a href="{{ route('admin.pagecontent.edit', $p->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                {{-- <form action="{{ route('admin.pagecontent.delete', $p->id) }}"
                                                    method="POST" class="d-inline">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Delete this policy?')">
                                                        Delete
                                                    </button>

                                                </form> --}}
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
    {{-- <div class="mt-3">
        {{ $products->links() }}
    </div> --}}
    <script>
        document.getElementById('tableWrapper').addEventListener('wheel', function(e) {
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
                columnDefs: [{
                    targets: 2, // Status column index (0-based)
                    searchable: true
                }]
            });
        });
    </script>

@endsection

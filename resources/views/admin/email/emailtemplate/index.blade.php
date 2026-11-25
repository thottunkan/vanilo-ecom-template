@extends('admin.layout.admin-layout')

@section('title', 'Products')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'title' => 'Email templates',
                'button_text' => 'Create email template',
                'url' => route('admin.email-template.create'),
                'links' => ['Email settings', 'Email templates'],
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
                            <h5>Email Templates</h5>
                        </div>
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            {{-- <div class="table-responsive" id="tableWrapper"> --}}

                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">#</th>
                                        <th>template name</th>
                                        <th>slug</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($email_templates as $et)
                                        <tr>
                                            <td>{{ $et->id }}</td>
                                            <td>
                                                {{ $et->name }}
                                            </td>
                                            <td>{{ $et->slug }}</td>
                                            <td>
                                                <a href="{{ route('admin.email-template.edit', $et->id) }}"
                                                    class="btn btn-sm btn-warning"><i class="ti ti-edit"></i></a>
                                                <form action="{{ route('admin.email-template.delete', $et->id) }}"
                                                    method="POST" class="d-inline">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Delete this template?')">
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
                            {{-- </div> --}}
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
                language: {
                    emptyTable: "No products found."
                },
                columnDefs: [{
                        orderable: false,
                        searchable: false,
                        targets: [0, 3]
                    } // checkbox, image, variants, action
                ]
            });
        });
    </script>

@endsection

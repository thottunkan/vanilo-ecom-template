@extends('admin.layout.admin-layout')
@section('title', 'Menus')
@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'title' => 'Menus',
                'button_text' => 'Create',
                'url' => route('admin.menu.create'),
                'links' => ['Menus'],
            ])

            <style>
                .drag-handle {
                    cursor: move;

                }

                .sortable-ghost {
                    opacity: 0.4;
                    background: #f1f1f1;
                }
            </style>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Menus</h5>
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
                                        'route' => route('admin.menu.bulk.delete'),
                                        'selector' => '.menu-checkbox',
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
                                        'formUrl' => route('admin.menu-filter.menu-filter'),
                                        'clearUrl' => route('admin.menu.index'),
                                    ])
                                </div>
                            </div>

                            <div class="table-responsive" id="tableWrapper">

                                <table id="data-table" class="table table-responsive table-hover align-middle">

                                    <thead>
                                        <tr>
                                            <th width="40">Drag</th>
                                            <th width="50">
                                                #
                                            </th>
                                            <th>Title</th>
                                            <th>URL</th>
                                            <th>Status</th>
                                            <th>created at</th>
                                            <th>updated at</th>
                                            <th width="150">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="sortable-menu">
                                        @forelse ($menus as $menu)
                                            <tr data-id="{{ $menu->id }}">

                                                <td class="drag-handle">
                                                    ☰
                                                </td>

                                                <td>
                                                    <input type="checkbox" class="menu-checkbox"
                                                        value="{{ $menu->id }}">
                                                </td>

                                                <td class="drag-handle">
                                                    {{ $menu->title }}
                                                </td>

                                                <td class="drag-handle">
                                                    {{ $menu->url }}
                                                </td>

                                                <td>
                                                    <span
                                                        class="badge {{ ($menu->status ?? '') == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $menu->status ?? '' }}
                                                    </span>
                                                </td>

                                                <td>
                                                    {{ $menu->created_at->format('D M Y') }}
                                                </td>
                                                <td>
                                                    {{ $menu->updated_at->format('D M Y') }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.menu.edit', $menu->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="ti ti-edit"></i>
                                                    </a>

                                                    <form action="{{ route('admin.menu.delete', $menu->id) }}"
                                                        method="POST" class="d-inline">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Delete this menu?')">
                                                            <i class="ti ti-trash"></i>
                                                        </button>

                                                    </form>
                                                </td>

                                            </tr>
                                        @empty
                                            {{-- <td class="text-center">
                                                    No records found.
                                                </td>
                                             --}}
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

    {{-- SortableJS --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Horizontal mouse wheel scrolling
            document.getElementById('tableWrapper').addEventListener('wheel', function(e) {
                if (this.scrollWidth > this.clientWidth) {
                    e.preventDefault();
                    this.scrollLeft += e.deltaY;
                }
            }, {
                passive: false
            });

            // Initialize Sortable
            new Sortable(document.getElementById('sortable-menu'), {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',

                onEnd: function() {

                    let order = [];

                    document.querySelectorAll('#sortable-menu tr').forEach(function(row, index) {
                        order.push({
                            id: row.dataset.id,
                            position: index + 1
                        });
                    });

                    $.ajax({
                        url: "{{ route('admin.menu.updateOrder') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },
                        success: function(response) {
                            console.log(response);
                            if (response.success) {
                                location.reload();
                                //alert('sort order updated successfully');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('Unable to update menu order.');
                        }
                    });

                }
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable();
        });
    </script>
@endsection

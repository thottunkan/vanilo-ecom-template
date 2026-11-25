@extends('admin.layout.admin-layout')

@section('title', 'Policies')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'title' => 'Policies',
                'button_text' => 'Create',
                'url' => route('admin.policy.create'),
                'links' => ['Policies'],
            ])

            <div class="row">
                <div class="col-sm-12">

                    <div class="card">

                        <div class="card-header">
                            <h5>Policies</h5>
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
                                        'selector' => '.policy-checkbox',
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
                                        'formUrl' => route('admin.policy-filter.policy-filter'),
                                        'clearUrl' => route('admin.policy.index'),
                                    ])
                                </div>
                            </div>

                            <div class="table-responsive" id="tableWrapper">

                                <table id="data-table" class="table table-responsive table-hover align-middle">

                                    <thead>
                                        <tr>
                                            <th width="50">
                                                Drag
                                            </th>
                                            <th width="50">
                                                #   
                                            </th>
                                            <th>title</th>
                                            <th>slug</th>
                                            <th>status</th>
                                            <th>created at</th>
                                            <th>updated at</th>
                                            <th width="180">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody id="sortable-policy">

                                        @forelse($policies as $policy)
                                            <tr data-id="{{ $policy->id }}">

                                                <td class="drag-handle">
                                                    ☰
                                                </td>

                                                <td>
                                                    <input type="checkbox" class="policy-checkbox"
                                                        value="{{ $policy->id }}">
                                                </td>

                                                <td>{{ $policy->title }}</td>

                                                <td>{{ $policy->slug }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ ($policy->status ?? '') == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $policy->status ?? '' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $policy->created_at->format('D M Y') }}
                                                </td>
                                                <td>
                                                    {{ $policy->updated_at->format('D M Y') }}
                                                </td>
                                                <td>

                                                    <a href="{{ route('admin.policy.edit', $policy->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="ti ti-edit"></i>
                                                    </a>

                                                    <form action="{{ route('admin.policy.delete', $policy->id) }}"
                                                        method="POST" class="d-inline">

                                                        @csrf
                                                        @method('DELETE')

                                                        <button class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Delete this policy?')">
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

    <style>
        .drag-handle {
            cursor: move;



            text-align: center;
        }

        .sortable-ghost {
            opacity: .5;
            background: #f8f9fa;
        }

        .sortable-chosen {
            background: #fff3cd;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Horizontal scrolling
            const tableWrapper = document.getElementById('tableWrapper');

            if (tableWrapper) {
                tableWrapper.addEventListener('wheel', function(e) {
                    if (this.scrollWidth > this.clientWidth) {
                        e.preventDefault();
                        this.scrollLeft += e.deltaY;
                    }
                }, {
                    passive: false
                });
            }

            // Sortable
            const sortableElement = document.getElementById('sortable-policy');

            if (!sortableElement) return;

            new Sortable(sortableElement, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',

                onEnd: function() {

                    let order = [];

                    document.querySelectorAll('#sortable-policy tr').forEach(function(row, index) {

                        order.push({
                            id: row.dataset.id,
                            position: index + 1
                        });

                    });

                    console.log(order);

                    $.ajax({

                        url: "{{ route('admin.policy.updateOrder') }}",
                        type: "POST",

                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },

                        success: function(response) {

                            console.log(response);

                            if (response.success) {
                                console.log('Order Updated');
                            }

                        },

                        error: function(xhr) {

                            console.log(xhr.responseText);
                            alert('Unable to update order.');

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

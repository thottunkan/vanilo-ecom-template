@extends('admin.layout.admin-layout')
@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.custom-js.create'),
                'title' => 'Custom js list',
                'links' => ['Desing', 'Custom js'],
            ])
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Custom js list</h5>
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
                                        'route' => route('admin.custom-js.bulk.delete'),
                                        'selector' => '.customjs-checkbox',
                                        'buttonText' => 'Delete Selected',
                                    ])
                                </div>
                                <div class="text-end">
                                    @include('admin.widgets.filters', [
                                        'formUrl' => route('admin.custom-js-filter.custom-js-filter'),
                                        'clearUrl' => route('admin.custom-js.index'),
                                    ])
                                </div>
                            </div>

                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">
                                           #
                                        </th>
                                        <th>script name</th>
                                        <th>slug</th>
                                        <th>status</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($list_customjs as $tr)
                                        <tr>
                                            <td><input type="checkbox" class="customjs-checkbox" value="{{ $tr->id }}" name=""
                                                    id=""></td>
                                            <td>{{ $tr->script_name ?? '' }}</td>
                                            <td>{{ $tr->slug ?? '' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ ($tr->is_active ?? '') == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $tr->is_active ?? '' }}
                                                </span>
                                            </td>
                                            <td>{{ $tr->created_at ?? '' }}</td>
                                            <td>{{ $tr->updated_at ?? '' }}</td>
                                            <td>
                                                <a href="{{ route('admin.custom-js.edit', $tr->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.custom-js.delete', $tr->id) }}" method="POST"
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
        $(document).ready(function () {
            $('#data-table').DataTable();
        });
    </script>
@endsection
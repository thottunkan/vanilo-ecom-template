@extends('admin.layout.admin-layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.discount.create'),
                'title' => 'Discount',
                'links' => ['Sales', 'Discount'],
            ])

            <div class="row">
                <div class="col-sm-12">



                    <div class="card">
                        <div class="card-header">
                            <h5>Discount List</h5>
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
                            <br>
                            <div class="table-responsive">
                                <table class="table table-responsive table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Name</th>
                                            <th>Option Key</th>
                                            <th>Option Value</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th width="180">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @forelse($discounts as $key => $discount)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $discount->name }}</td>
                                                <td>{{ $discount->option_key }}</td>
                                                <td>{{ $discount->option_value }}</td>
                                                <td>
                                                    @if ($discount->status == 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $discount->created_at->format('d M Y') }}</td>
                                                <td>

                                                    <a href="{{ route('admin.discount.edit', $discount->id) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="ti ti-edit"></i>
                                                    </a>

                                                    <form action="{{ route('admin.discount.delete', $discount->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this discount?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-danger">
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
@endsection

@extends('admin.layout.admin-layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.customer.create'),
                'title' => 'Customers',
                'links' => ['Customer', 'Customers'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Customers</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between">

                                <div class="text-start">
                                    @include('admin.widgets.bulkdelete', [
                                        'route' => route('admin.customer.bulk.delete'),
                                        'selector' => '.customer-checkbox',
                                        'buttonText' => 'Delete Selected',
                                    ])
                                    @include('admin.widgets.dashboard.tables.buttons', [
                                        'exportToPdfRoute' => route('admin.customer.export-to-pdf'),
                                        'exportToExcelRoute' => route('admin.customer.export-to-excel'),
                                        'importExcelRoute' => route('admin.customer.import-from-excel'),
                                        'importCSVRoute' => route('admin.customer.import-from-csv'),
                                        'downloadSampleRoute' => route('admin.customer.download-template-excel'),
                                    ])

                                </div>
                                <div class="text-end">
                                    @include('admin.widgets.filters', [
                                        'formUrl' => route('admin.customer-filter.customer-filter'),
                                        'clearUrl' => route('admin.customer.index'),
                                    ])
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="data-table" class="table table-responsive table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th width="60">#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Gender</th>
                                            <th>Status</th>
                                            <th>Login Count</th>
                                            <th>Last Login</th>
                                            <th width="180">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($customers as $customer)
                                            <tr>
                                                {{-- <td>{{ $loop->iteration + ($customers->firstItem() - 1) }}</td> --}}
                                                <td><input type="checkbox" name="" value="{{ $customer->id }}"
                                                        class="customer-checkbox" id=""></td>
                                                <td>
                                                    <strong>{{ $customer->name }}</strong>

                                                    @if ($customer->first_name || $customer->last_name)
                                                        <br>
                                                        <small class="text-muted">
                                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>{{ $customer->email }}</td>
                                                <td>{{ $customer->phone ?? '-' }}</td>
                                                <td>{{ $customer->gender ?? '-' }}</td>
                                                <td>
                                                    @if ($customer->is_active == 'active')
                                                        <span class="badge bg-success">{{ $customer->is_active }}</span>
                                                    @elseif ($customer->is_active == 'inactive')
                                                        <span class="badge bg-warning">{{ $customer->is_active }}</span>
                                                    @elseif ($customer->is_active == 'deactivated')
                                                        <span class="badge bg-danger">{{ $customer->is_active }}</span>
                                                    @else
                                                        <span
                                                            class="badge bg-secondary">{{ $customer->is_active ?? 'Unknown' }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $customer->login_count }}</td>
                                                <td>
                                                    {{ $customer->last_login_at ? \Carbon\Carbon::parse($customer->last_login_at)->format('d M Y h:i A') : '-' }}
                                                </td>
                                                <td>
                                                    {{-- <a href="{{ route('admin.customer.show', $customer->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="ti ti-eye"></i>
                                                    </a> --}}
                                                    <a href="{{ route('admin.customer.edit', $customer->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.customer.delete', $customer->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this customer?')">
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
                            </div>
                            {{--
                            <div class="mt-3">
                                {{ $customers->links() }}
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#data-table').DataTable();
        });
    </script>
@endsection
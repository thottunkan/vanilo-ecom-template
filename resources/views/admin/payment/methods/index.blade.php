@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.payment-method.create'),
                'title' => 'Payment Methods',
                'links' => ['Payment', 'Payment Methods'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Payment Methods</h5>
                        </div>

                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="60">
                                            <input type="checkbox">
                                        </th>
                                        <th>Name</th>
                                        <th>Gateway</th>
                                        {{-- <th>Zone ID</th> --}}
                                        <th>Status</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($payment_methods as $method)
                                        <tr>
                                            <td>
                                                <input type="checkbox">
                                            </td>

                                            <td>{{ $method->name }}</td>

                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $method->gateway }}
                                                </span>
                                            </td>

                                            {{-- <td>
                                                {{ $method->zone_id ?? '-' }}
                                            </td> --}}

                                            <td>
                                                @if ($method->is_enabled)
                                                    <span class="badge bg-success">
                                                        Enabled
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        Disabled
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.payment-method.edit', $method->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.payment-method.delete', $method->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this payment method?')">

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
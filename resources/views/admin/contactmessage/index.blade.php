@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Contact messages',
                'links' => ['Contact messages'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Contact messages</h5>
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
                            <div class="text-end">
                                @include('admin.widgets.filters', [
                                    'formUrl' => route('admin.brand-filter.brand-filter'),
                                    'clearUrl' => null,
                                ])
                            </div>
                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">
                                            #
                                        </th>
                                        <th>first name</th>
                                        <th>last name</th>
                                        <th>email</th>
                                        <th>message</th>
                                        <th>status</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {{-- @forelse($brands as $tr)
                                        <tr>
                                            <td><input type="checkbox" name="" id=""></td>
                                            <td>{{ $tr->name }}</td>
                                            <td>{{ $tr->created_at }}</td>
                                            <td>{{ $tr->updated_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.brand.edit', $tr->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>

                                                <form action="{{ route('admin.brand.delete', $tr->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this product?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                    @endforelse --}}
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

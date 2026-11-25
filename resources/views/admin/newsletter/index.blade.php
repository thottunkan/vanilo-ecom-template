@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => 'create(test)',
                'url' => route('admin.newsletter.create'),
                'title' => 'Newsletters',
                'links' => ['Newsletters'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Newsletters</h5>
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
                                        <th>user email</th>
                                        <th>subscription</th>
                                        <th>subscribed_at</th>
                                        <th>unsubscribed_at</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($newsletters as $tr)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="ids[]" value="{{ $tr->id }}">
                                            </td>

                                            <td>{{ $tr->email }}</td>

                                            <td>
                                                @if ($tr->is_subscribed)
                                                    <span class="badge bg-success">
                                                        Subscibed
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        Unubscibed
                                                    </span>
                                                @endif
                                            </td>

                                            <td>{{ $tr->subscribed_at ?? '-' }}</td>
                                            <td>{{ $tr->unsubscribed_at ?? '-' }}</td>
                                            <td>{{ $tr->created_at }}</td>
                                            <td>{{ $tr->updated_at }}</td>

                                            <td>
                                                <a href="{{ route('admin.newsletter.edit', $tr->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.newsletter.delete', $tr->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this newsletter subscriber?')">

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
                                            <td colspan="8" class="text-center">
                                                No newsletter subscribers found.
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

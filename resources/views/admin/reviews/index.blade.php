@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.review.create'),
                'title' => 'Reviews',
                'links' => ['Customer', 'Reviews'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Reviews</h5>
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
                                        'route' => route('admin.taxrate.bulk.delete'),
                                        'selector' => '.review-checkbox',
                                        'buttonText' => 'Delete Selected',
                                    ])
                                </div>
                                <div class="text-end">
                                    @include('admin.widgets.filters', [
                                        'formUrl' => null,
                                        'clearUrl' => null,
                                    ])
                                </div>
                            </div>

                            <table id="data-table" class="table table-responsive table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th width="80">
                                            #
                                        </th>
                                        <th>status</th>
                                        <th>user name</th>
                                        <th>user email</th>
                                        <th>product name</th>
                                        <th>rating</th>
                                        <th>comment</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($reviews as $r)
                                        <tr>
                                            <td><input type="checkbox" name="" value="{{ $r->id }}"  class="review-checkbox" id=""></td>
                                            <td>
                                                @if ($r->status == 'pending')
                                                    <span class="badge bg-warning">{{ ucfirst($r->status) }}</span>
                                                @elseif($r->status == 'approved')
                                                    <span class="badge bg-success">{{ ucfirst($r->status) }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ ucfirst($r->status) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $r->user->name ?? '' }}</td>
                                            <td>{{ $r->user->email ?? '' }}</td>
                                            <td>{{ $r->product->name ?? '' }}</td>
                                            <td>{{ $r->rating ?? '' }}</td>
                                            <td>{{ $r->comments ?? '' }}</td>
                                            <td>{{ $r->created_at ?? '' }}</td>
                                            <td>{{ $r->updated_at ?? '' }}</td>
                                            <td>

                                                <a href="{{ route('admin.review.edit', $r->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.review.delete', $r->id) }}" method="POST"
                                                    class="d-inline">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm"
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

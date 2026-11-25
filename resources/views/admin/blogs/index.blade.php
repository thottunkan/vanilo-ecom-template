@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => 'Create',
                'url' => route('admin.blog.create'),
                'title' => 'Blogs',
                'links' => ['Blog', 'Blogs'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Blogs</h5>
                        </div>

                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="text-start">
                                    @include('admin.widgets.bulkdelete', [
                                        'route' => route('admin.blog.bulk.delete'),
                                        'selector' => '.blog-checkbox',
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
                                            #
                                        </th>
                                        <th>banner</th>
                                        <th>title</th>
                                        <th>category</th>
                                        <th>status</th>
                                        <th>created at</th>
                                        <th>updated at</th>
                                        <th width="180">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($blogs as $b)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="blog-checkbox" value="{{ $b->id }}">
                                            </td>

                                            <td width="100">
                                                @if ($b->banner_image_path)
                                                    <img src="{{ asset('storage/' . $b->banner_image_path) }}"
                                                        width="80" class="img-thumbnail">
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>{{ $b->title }}</td>

                                            <td>{{ $b->category->name ?? '-' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ ($b->status ?? '') == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $b->status ?? '' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $b->created_at->format('D M Y') }}
                                            </td>
                                            <td>
                                                {{ $b->updated_at->format('D M Y') }}
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.blog.edit', $b->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <form action="{{ route('admin.blog.delete', $b->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this blog?')">
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
        $('#data-table').DataTable({
            //  ajax: '/banner/list',
            columns: [
                {
                    data: 'checkbox'
                },
                {
                    data: 'banner'
                },
                {
                    data: 'title'
                },
                {
                    data: 'category'
                },
                {
                    data: 'status'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                {
                    data: 'action'
                }
            ]
        });
    </script>
@endsection

@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => "Edit Home Page",
                'links' => ['Pages', 'Edit Home page'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Home Page</h5>
                        </div>

                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.pagecontent.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name') }}"
                                        placeholder="Enter discount name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        Create
                                    </button>

                                    <a href="{{ route('admin.pagecontent.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
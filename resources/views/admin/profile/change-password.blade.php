@extends('admin.layout.admin-layout')

@section('content')

<div class="pc-container">
    <div class="pc-content">

        @include('admin.includes.page-header', [
            'button_text' => null,
            'url' => null,
            'title' => 'Change Password',
            'links' => ['admin', 'admins', 'Change Password'],
        ])

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <h5>Change Password</h5>
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

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.profile.password.update', $admin->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Current Password <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        name="current_password"
                                        class="form-control"
                                        required
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        New Password <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        required
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Confirm New Password <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control"
                                        required
                                    >
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary">
                                Change Password
                            </button>

                            <a href="{{ route('admin.profile.edit', $admin->id) }}" class="btn btn-secondary">
                                Cancel
                            </a>

                        </form>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection
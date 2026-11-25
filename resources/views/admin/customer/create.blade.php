@extends('admin.layout.admin-layout')

@section('content')

<div class="pc-container">
    <div class="pc-content">

        @include('admin.includes.page-header', [
            'button_text' => null,
            'url' => null,
            'title' => 'Create Customer',
            'links' => ['Customer', 'Customers', 'Create Customer'],
        ])

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Create Customer</h5>
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

                        <form action="{{ route('admin.customer.store') }}" method="POST">
                            @csrf

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input
                                        type="text"
                                        name="first_name"
                                        class="form-control"
                                        value="{{ old('first_name') }}"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input
                                        type="text"
                                        name="last_name"
                                        class="form-control"
                                        value="{{ old('last_name') }}"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name') }}"
                                        required
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input
                                        type="email"
                                        name="email"
                                        class="form-control"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        required
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirm Password</label>
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input
                                        type="text"
                                        name="phone"
                                        class="form-control"
                                        value="{{ old('phone') }}"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        class="form-control"
                                        value="{{ old('date_of_birth') }}"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Language</label>
                                    <input
                                        type="text"
                                        name="language"
                                        class="form-control"
                                        value="{{ old('language', 'en') }}"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Currency</label>
                                    <input
                                        type="text"
                                        name="currency"
                                        class="form-control"
                                        value="{{ old('currency', 'INR') }}"
                                    >
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role</label>
                                    <input
                                        type="text"
                                        name="role"
                                        class="form-control"
                                        value="{{ old('role', 'customer') }}"
                                        readonly
                                    >
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Bio</label>
                                    <textarea
                                        name="bio"
                                        class="form-control"
                                        rows="4"
                                    >{{ old('bio') }}</textarea>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary">
                                Create Customer
                            </button>

                            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
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
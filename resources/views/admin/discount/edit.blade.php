@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Discount',
                'links' => ['Settings', 'Discount', 'Edit Discount'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Discount</h5>
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

                            <form action="{{ route('admin.discount.update', $discount->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $discount->name) }}" placeholder="Enter discount name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Discount type</label>
                                    <select name="option_key" class="form-select">
                                        <option value="">Select Discount Type</option>
                                        <option value="percentage"
                                            {{ old('option_key', $discount->option_key) == 'percentage' ? 'selected' : '' }}>
                                            Percentage
                                        </option>
                                        <option value="fixed_amount"
                                            {{ old('option_key', $discount->option_key) == 'fixed_amount' ? 'selected' : '' }}>
                                            Fixed Amount
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Option Value</label>
                                    <input type="text" name="option_value" class="form-control"
                                        value="{{ old('option_value', $discount->option_value) }}"
                                        placeholder="Example: 10 or 500">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Select Status</option>
                                        <option value="active"
                                            {{ old('status', $discount->status) == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            {{ old('status', $discount->status) == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>

                                    <a href="{{ route('admin.discount.index') }}" class="btn btn-secondary">
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

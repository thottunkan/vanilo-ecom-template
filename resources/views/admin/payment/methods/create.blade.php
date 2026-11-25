@extends('admin.layout.admin-layout')

@section('content')

<div class="pc-container">
    <div class="pc-content">

        @include('admin.includes.page-header', [
            'button_text' => null,
            'url' => null,
            'title' => 'Create Payment Method',
            'links' => ['Payment', 'Payment Methods', 'Create Payment Method'],
        ])

        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-header">
                        <h5>Create Payment Method</h5>
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

                        <form action="{{ route('admin.payment-method.store') }}" method="POST">
                            @csrf

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name') }}"
                                        required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gateway <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="gateway"
                                        class="form-control"
                                        value="{{ old('gateway') }}"
                                        placeholder="stripe, paypal, cod..."
                                        required>
                                </div>

                                {{-- <div class="col-md-6 mb-3">
                                    <label class="form-label">Zone ID</label>
                                    <input
                                        type="number"
                                        name="zone_id"
                                        class="form-control"
                                        value="{{ old('zone_id') }}">
                                </div> --}}

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Enabled</label>

                                    <div class="form-check mt-2">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="is_enabled"
                                            id="is_enabled"
                                            value="1"
                                            {{ old('is_enabled', 1) ? 'checked' : '' }}>

                                        <label class="form-check-label" for="is_enabled">
                                            Enable this payment method
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea
                                        name="description"
                                        rows="4"
                                        class="form-control">{{ old('description') }}</textarea>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Configuration (JSON)</label>

                                    <textarea
                                        name="configuration"
                                        rows="8"
                                        class="form-control"
                                        placeholder='{"key":"value"}'>{{ old('configuration', '{}') }}</textarea>

                                    <small class="text-muted">
                                        Enter valid JSON. Example:
                                        <code>{"merchant_id":"123","secret":"abc"}</code>
                                    </small>
                                </div>

                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>

                                <a href="{{ route('admin.payment-method.index') }}"
                                   class="btn btn-secondary">
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
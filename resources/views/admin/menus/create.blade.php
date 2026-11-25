@extends('admin.layout.admin-layout')

@section('title', 'Create Menu')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'title' => 'Create Menu',
                'button_text' => 'Back',
                'url' => route('admin.menu.index'),
                'links' => ['Menus', 'Create'],
            ])

            <div class="row">
                <div class="col">
                    <div class="card">

                        <div class="card-header">
                            <h5>Create Menu</h5>
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

                            <form action="{{ route('admin.menu.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">
                                        Title <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                        placeholder="Enter menu title">

                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        URL
                                    </label>

                                    <input type="text" name="url" class="form-control @error('url') is-invalid @enderror"
                                        value="{{ old('url') }}" placeholder="/about-us">

                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label for="status" class="fw-bold">
                                        Status
                                    </label>

                                    <select name="status" id="status" class="form-control">
                                        <option value="inactive" {{ old('status', $menu->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                        <option value="active" {{ old('status', $menu->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                    </select>

                                </div>


                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>

                                <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">
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
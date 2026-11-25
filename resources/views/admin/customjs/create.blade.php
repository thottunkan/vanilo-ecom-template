@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Custom JavaScript',
                'links' => ['Settings', 'Custom JavaScript'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Custom JavaScript</h5>
                        </div>
                        <form id="custom_js_form" action="{{ route('admin.custom-js.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                {{-- Validation Errors --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Script Name --}}
                                <div class="mb-3">
                                    <label for="script_name" class="fw-bold">
                                        Script Name
                                    </label>

                                    <input type="text" name="script_name" id="script_name" class="form-control"
                                        value="{{ old('script_name', $customJs->script_name ?? '') }}">
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label for="is_active" class="fw-bold">
                                        Status
                                    </label>

                                    <select name="is_active" id="is_active" class="form-control">
                                        <option value="inactive"
                                            {{ old('is_active', $customJs->is_active ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                        <option value="active"
                                            {{ old('is_active', $customJs->is_active ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                    </select>

                                </div>


                                {{-- JavaScript Editor --}}
                                <div class="mb-3">

                                    <label class="fw-bold mb-2">
                                        Write Script
                                    </label>
                                    <div id="js_editor"></div>
                                    <textarea id="custom_js" name="script" hidden>{{ old('script', $customJs->script ?? '') }}</textarea>

                                </div>

                            </div>

                            <div class="card-footer text-start">
                                <button type="submit" class="btn btn-primary">
                                    Save JavaScript
                                </button>
                                <a href="{{ route('admin.custom-js.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ext-language_tools.min.js"></script>
    <script src="{{ asset('app-scripts/admin/customjs/editorconfig.js') }}"></script>
    <script src="{{ asset('app-scripts/admin/customjs/submit.js') }}"></script>

    <style>
        #js_editor {
            width: 100%;
            height: 500px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            font-size: 14px;
        }
    </style>


@endsection

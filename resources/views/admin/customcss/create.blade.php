@extends('admin.layout.admin-layout')

@section('content')

<div class="pc-container">
    <div class="pc-content">

        @include('admin.includes.page-header', [
            'button_text' => null,
            'url' => null,
            'title' => 'Create Custom CSS',
            'links' => ['Design', 'Custom css', 'Create custom css'],
        ])

        <div class="row">
            <div class="col-sm-12">

                <div class="card">

                    <div class="card-header">
                        <h5>Create Custom CSS</h5>
                    </div>

                    <form id="custom_css_form" action="{{ route('admin.custom-css.store') }}" method="POST">
                        @csrf

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


                            {{-- Style Name --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Style Name
                                </label>

                                <input
                                    type="text"
                                    name="style_name"
                                    id="style_name"
                                    class="form-control"
                                    value="{{ old('style_name', $customCss->style_name ?? '') }}"
                                    placeholder="Enter style name"
                                    required
                                >
                            </div>


                            {{-- Status --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    Status
                                </label>

                                <select name="is_active" class="form-control">

                                    <option value="active"
                                        {{ old('is_active', $customCss->is_active ?? 'active') == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="inactive"
                                        {{ old('is_active', $customCss->is_active ?? '') == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>
                            </div>


                            {{-- CSS Editor --}}
                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Custom CSS
                                </label>

                                <div id="css_editor"></div>

                                <textarea
                                    id="custom_css"
                                    name="css"
                                    style="display:none;"
                                >{{ old('css', $customCss->css ?? '') }}</textarea>

                            </div>

                        </div>


                        <div class="card-footer text-start">

                            <button type="submit" class="btn btn-primary">
                                Save CSS
                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ace.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ext-language_tools.min.js"></script>


<style>
    #css_editor {
        width: 100%;
        height: 500px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
    }
</style>


<script src="{{ asset('app-scripts/admin/customcss/editorconfig.js') }}">
</script>
<script src="{{ asset('app-scripts/admin/customcss/submit.js') }}"></script>


@endsection
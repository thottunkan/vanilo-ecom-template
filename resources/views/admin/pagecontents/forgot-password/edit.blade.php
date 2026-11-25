@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Forgot password page',
                'links' => ['Pages', 'Edit forgot password page'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Forgot password Page</h5>
                        </div>
                        @php
                            $page_content = json_decode($forgot_password_page->page_contents, true) ?? [];

                          
                        @endphp
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

                            <form action="{{ route('admin.pagecontent.forgot-password.update', $forgot_password_page->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    @if (isset($page_content['forgot_password_image']))
                                        <img width="50" height="50"
                                            src="{{ asset('storage/' . $page_content['forgot_password_image']) }}"
                                            class="mb-2 d-block" alt="Image 3">
                                    @endif
                                    <label class="form-label">Image</label>
                                    <input type="file" name="forgot_password_image" class="form-control"
                                        value="{{ old('name') }}" >
                                </div>
                                {{-- <div class="mb-3">
                                    @if (isset($page_content['sign_up_logo']))
                                        <img width="50" height="50"
                                            src="{{ asset('storage/' . $page_content['sign_up_logo']) }}"
                                            class="mb-2 d-block" alt="Image 3">
                                    @endif
                                    <label class="form-label">Site Logo</label>
                                    <input type="file" name="sign_up_logo" class="form-control"
                                        value="{{ old('name') }}" placeholder="Enter discount name">
                                </div> --}}
                                <div class="mb-3">
                                    <label for="">Image Title</label>
                                    <input type="text" name="img_title" class="form-control" placeholder="Image title" value="{{ $page_content['img_title'] ?? '' }}" />
                                </div>
                                <div class="mb-3">
                                    <label for="">Image text</label>
                                    <input type="text" name="img_text" class="form-control" placeholder="Image text" value="{{ $page_content['img_text'] ?? '' }}" />
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>

                                    <select name="status" class="form-select">
                                        <option value="active"
                                            {{ old('status', $page_content['status'] ?? 'active') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="inactive"
                                            {{ old('status', $page_content['status'] ?? 'active') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        Update
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

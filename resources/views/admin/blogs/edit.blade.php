@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Blog',
                'links' => ['Catalog', 'Blog', 'Edit Blog'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Edit Blog</h5>
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

                            <form id="blogForm" action="{{ route('admin.blog.update', $blog->id) }}" method="POST"
                                enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <!-- Title -->
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" id="title" name="title" class="form-control"
                                        value="{{ old('title', $blog->title) }}" required>
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select id="blog_category_id" name="blog_category_id" class="form-control" required>

                                        <option value="">Select Category</option>

                                        @foreach ($blog_categories as $bc)
                                            <option value="{{ $bc->id }}"
                                                {{ old('blog_category_id', $blog->blog_category_id) == $bc->id ? 'selected' : '' }}>
                                                {{ $bc->name }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>

                                <!-- Current Banner -->
                                @if ($blog->banner_image_path)
                                    <div class="mb-3">
                                        <label class="form-label">Current Banner</label><br>

                                        <img src="{{ asset('storage/' . $blog->banner_image_path) }}" class="img-thumbnail"
                                            width="200">
                                    </div>
                                @endif

                                <!-- Banner -->
                                <div class="mb-3">
                                    <label class="form-label">Change Banner Image</label>
                                    <input type="file" name="banner_image" class="form-control" accept="image/*">

                                    <small class="text-muted">
                                        Leave empty to keep current image.
                                    </small>
                                </div>

                                <!-- Quill Editor -->
                                <div class="mb-3">
                                    <label class="form-label">Content</label>

                                    <div id="page_editor" style="height:400px;">
                                        {!! old('blog_content', $blog->blog_content) !!}
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="inactive"
                                            {{ old('status', $blog->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                        <option value="active"
                                            {{ old('status', $blog->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>

                                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>

                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var Font = Quill.import('formats/font');
        Font.whitelist = ['sans-serif', 'serif', 'monospace', 'arial', 'roboto'];
        Quill.register(Font, true);

        var quill = new Quill('#page_editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'font': Font.whitelist
                    }],
                    [{
                        'align': []
                    }],
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        $('#blogForm').on('submit', function(e) {

            e.preventDefault();

            let formData = new FormData(this);

            formData.append('blog_content', quill.root.innerHTML);

            $.ajax({

                url: "{{ route('admin.blog.update', $blog->id) }}",
                type: "POST", // Laravel will handle PUT using _method
                data: formData,
                processData: false,
                contentType: false,
                cache: false,

                success: function(response) {

                    if (response.status) {

                        alert('Blog updated successfully!');

                        // Optional
                        // window.location.href = "{{ route('admin.blog.index') }}";

                    } else {

                        alert(response.message || "Something went wrong.");

                    }

                },

                error: function(xhr) {

                    if (xhr.status == 422) {

                        let errors = xhr.responseJSON.errors;
                        let message = '';

                        $.each(errors, function(key, value) {
                            message += value[0] + "\n";
                        });

                        alert(message);

                    } else {

                        alert('Something went wrong.');

                    }

                }

            });

        });
    </script>

@endsection

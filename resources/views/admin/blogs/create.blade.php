@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Create Blog',
                'links' => ['Catalog', 'Blog', 'Create Blog'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Create Blog</h5>
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

                            <form id="blogForm" action="{{ route('admin.blog.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <!-- Blog Title -->
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title') }}" required>
                                </div>

                                <!-- Blog Category -->
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="blog_category_id" id="blog_category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        @foreach ($blog_categories as $bc)
                                            <option value="{{ $bc->id }}"
                                                {{ old('blog_category_id') == $bc->id ? 'selected' : '' }}>
                                                {{ $bc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Banner Image -->
                                <div class="mb-3">
                                    <label class="form-label">Banner Image</label>
                                    <input type="file" name="banner_image" class="form-control" accept="image/*">
                                </div>

                                <div class="mb-3">
                                    <label for="fw-semibold">Content</label>
                                    <div id="page_editor">
                                        <p>Hello World!</p>
                                        <p>Some initial <strong>bold</strong> text</p>
                                        <p><br /></p>
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

                                <!-- Buttons -->
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        Create
                                    </button>

                                    <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">
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
    </script>
    <script>
        $('#blogForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            // Append Quill content
            formData.append('blog_content', quill.root.innerHTML);

            $.ajax({
                url: "{{ route('admin.blog.store') }}",
                type: "POST",
                data: formData,
                processData: false, // Required for FormData
                contentType: false, // Required for FormData
                cache: false,
                success: function(response) {
                    console.log(response);

                    if (response.status) {

                        alert('Blog created successfully!');
                        // Optional:
                        // window.location.href = "{{ route('admin.blog.index') }}";
                    } else {
                        if (response.message && response.message.includes("Duplicate entry")) {
                            alert("Duplicate entry slug");
                        } else {
                            alert(response.message || "Something went wrong.");
                        }
                    }
                },

                error: function(xhr) {
                    console.log(xhr.responseJSON);

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let message = "";

                        $.each(errors, function(key, value) {
                            message += value[0] + "\n";
                        });

                        alert(message);
                    } else {
                        alert("Something went wrong!");
                    }
                }
            });
        });
    </script>
@endsection

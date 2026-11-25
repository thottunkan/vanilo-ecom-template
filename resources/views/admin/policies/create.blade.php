@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Create Policy   ',
                'links' => ['Policy', 'Create policy'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create Policy</h5>
                        </div>
                        <div class="card-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- content --}}

                            <form id="pageForm" action="{{ route('admin.policy.store') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="fw-semibold">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" required>
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label for="is_active" class="fw-bold">
                                        Status
                                    </label>

                                    <select name="status" id="status" class="form-control">
                                        <option value="inactive"
                                            {{ old('is_active', $policy->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                        <option value="active"
                                            {{ old('is_active', $policy->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                    </select>

                                </div>

                                <div class="mb-3">
                                    <label for="fw-semibold">content</label>
                                    <div id="page_editor">
                                        <p>Hello World!</p>
                                        <p>Some initial <strong>bold</strong> text</p>
                                        <p><br /></p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                    <a href="{{ route('admin.policy.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
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
        $('#pageForm').on('submit', function(e) {
            e.preventDefault();

            let title = $('#title').val();
            let content = quill.root.innerHTML;
            let status = $('#status').val();
            $.ajax({
                url: "{{ route('admin.policy.store') }}",
                type: "POST",
                data: {
                    title: title,
                    content: content,
                    status:status
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    if (response.status) {
                        alert('Page created successfully!');
                    } else {
                        if (response.message && response.message.includes(
                                "Duplicate entry")) {
                            alert("Duplicate entry slug");
                        } else {
                            alert(response.message || "Something went wrong.");
                        }

                    }

                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Something went wrong!');
                }
            });
        });
    </script>

@endsection

@extends('admin.layout.admin-layout')

@section('content')
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Email Template',
                'links' => ['Email Templates', 'Edit'],
            ])

            <div class="row">
                <div class="col-sm-12">

                    <div class="card">

                        <div class="card-header">
                            <h5>Create Email Template</h5>
                        </div>

                        <form id="emailForm"  method="POST">
                            @csrf


                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Template Name</label>
                                    <input type="text" id="name" name="name" class="form-control" required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">Email Subject</label>
                                    <input type="text" id="subject" name="subject" class="form-control" required>

                                    @error('subject')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="fw-semibold">Email body</label>
                                    <div id="page_editor" style="height: 300px;">
                                        write your enail here...
                                    </div>
                                </div>

                                {{-- 
                            <div class="mb-3">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select">
                                    <option value="1" {{ $template->status ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="0" {{ !$template->status ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div> --}}

                            </div>
                            
                            <div class="card-footer text-start">
                                <button class="btn btn-primary">
                                    Save Changes
                                </button>
                                <a href="{{ route('admin.email-template.index') }}" class="btn btn-secondary" >cancel</a>
                            </div>

                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- CKEditor --}}
    {{-- <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script> --}}
    {{-- CKEditor --}}

    
    <script>
        var Font = Quill.import('formats/font');
        Font.whitelist = ['sans-serif', 'serif', 'monospace', 'arial'];
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
        $('#emailForm').on('submit', function(e) {
            e.preventDefault();
         
            let name = $('#name').val();
            let body = quill.root.innerHTML;
            let subject = $('#subject').val();
            $.ajax({
                url: "{{ route('admin.email-template.store') }}",
                type: "POST",
                data: {
                    name: name,
                    body: body,
                    subject: subject,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Something went wrong!');
                }
            });
        });
    </script>

@endsection

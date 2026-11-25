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
                        <h5>Edit Email Template</h5>
                    </div>

                    <form id="emailForm" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Template Name</label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control"
                                    value="{{ $email_template->name }}"
                                    required>

                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Subject</label>
                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    class="form-control"
                                    value="{{ $email_template->subject }}"
                                    required>

                                @error('subject')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email Body</label>

                                <div id="page_editor" style="height:300px;">
                                    {!! $email_template->body !!}
                                </div>

                                @error('body')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                        <div class="card-footer text-start">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>

                            <a href="{{ route('admin.email-template.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

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

    $('#emailForm').on('submit', function(e) {
        e.preventDefault();

        let name = $('#name').val();
        let subject = $('#subject').val();
        let body = quill.root.innerHTML;

        $.ajax({
            url: "{{ route('admin.email-template.update', $email_template->id) }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "PUT",
                name: name,
                subject: subject,
                body: body
            },
            success: function(response) {
                alert('Template updated successfully.');

                // Optional redirect
                // window.location.href = "{{ route('admin.email-template.index') }}";
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Something went wrong!');
            }
        });
    });
</script>

@endsection
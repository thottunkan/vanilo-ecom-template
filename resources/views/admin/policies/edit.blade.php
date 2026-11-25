@extends('admin.layout.admin-layout')

@section('content')

<div class="pc-container">
    <div class="pc-content">

        @include('admin.includes.page-header', [
            'button_text' => null,
            'url' => null,
            'title' => 'Edit Policy',
            'links' => ['Policy', 'Edit policy'],
        ])

        <div class="row">
            <div class="col-sm-12">

                <div class="card">
                    <div class="card-header">
                        <h5>Edit Policy</h5>
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


                        <form id="pageForm">

                            @csrf

                            <div class="mb-3">
                                <label for="title" class="fw-bold">
                                    Title
                                </label>

                                <input 
                                    type="text"
                                    value="{{ old('title', $policy->title ?? '') }}"
                                    name="title"
                                    id="title"
                                    class="form-control"
                                    required>
                            </div>


                            <div class="mb-3">
                                <label for="status" class="fw-bold">
                                    Status
                                </label>

                                <select name="status" id="status" class="form-control">

                                    <option value="inactive"
                                        {{ old('status', $policy->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                    <option value="active"
                                        {{ old('status', $policy->status ?? '') == 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                </select>
                            </div>


                            <div class="mb-3">

                                <label class="fw-bold">
                                    Content
                                </label>

                                <div id="page_editor">
                                    {!! $policy->content ?? '' !!}
                                </div>

                            </div>


                            <div class="mb-3">

                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>

                                <a href="{{ route('admin.policy.index') }}" 
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


<script>

    var Font = Quill.import('formats/font');

    Font.whitelist = [
        'sans-serif',
        'serif',
        'monospace',
        'arial',
        'roboto'
    ];

    Quill.register(Font, true);
    var quill = new Quill('#page_editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{
                    font: Font.whitelist
                }],
                [{
                    align: []
                }],
                [{
                    header: [1,2,3,false]
                }],
                [
                    'bold',
                    'italic',
                    'underline',
                    'strike'
                ],
                [{
                    color: []
                },{
                    background: []
                }],
                [{
                    list:'ordered'
                },{
                    list:'bullet'
                }],
                [
                    'link',
                    'image'
                ],
                [
                    'clean'
                ]
            ]
        }
    });



    $('#pageForm').on('submit', function(e){
        e.preventDefault();
        let title = $('#title').val();
        let content = quill.root.innerHTML;
        let status = $('#status').val();

        $.ajax({
            url: "{{ route('admin.policy.update', $policy->id) }}",
            type: "PUT",
            data: {
                _token: "{{ csrf_token() }}",
                title: title,
                content: content,
                status: status
            },

            success:function(response){
                alert('Policy updated successfully!');
                window.location.href = "{{ route('admin.policy.index') }}";
            },

            error:function(xhr){
                console.log(xhr.responseText);
                alert('Something went wrong!');
            }
        });

    });


</script>

@endsection
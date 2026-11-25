@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Contact us Page',
                'links' => ['Pages', 'Edit Contact us page'],
            ])
            @php
                $page_content = json_decode($contact_us_page->page_contents, true) ?? [];
            @endphp
            <div class="row">
                <div class="col-sm-12">

                    <div class="card">

                        <div class="card-header">
                            <h5>Edit Contact Us Page</h5>
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


                            <form action="{{ route('admin.pagecontent.contact-us.update', $contact_us_page->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <!-- Section 1 -->
                                <div class="border rounded p-3 mb-4">

                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text" name="section_1_title" class="form-control"
                                            value="{{ old('section_1_title', $page_content['section_1_title'] ?? '') }}"
                                            placeholder="Enter Section 1 Title">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tagline</label>
                                        <input type="text" name="section1_tagline" class="form-control"
                                            value="{{ old('section1_tagline', $page_content['section1_tagline'] ?? '') }}"
                                            placeholder="Enter Section 1 tagline">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">

                                            <option value="active"
                                                {{ $page_content['status'] == 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="inactive"
                                                {{ $page_content['status'] == 'inactive' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>


                                </div>

                            


                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>

                                <a href="{{ route('admin.pagecontent.index') }}" class="btn btn-secondary">
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

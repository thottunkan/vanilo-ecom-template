@extends('admin.layout.admin-layout')
@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Blog Page',
                'links' => ['Pages', 'Edit Blog page'],
            ])

            @php
                $page_content = json_decode($blog_page->page_contents, true) ?? [];
                $section1 = $page_content['section1'] ?? [];
            @endphp

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Edit {{ $blog_page->title ?? '' }} Page</h5>
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

                            <form action="{{ route('admin.pagecontent.blog.update', $blog_page->id) }}" method="POST"
                                enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                <h4>Section 1</h4>

                                {{-- Breadcrumb --}}
                                <div class="mb-3">
                                    <label class="form-label">Breadcrumb</label>

                                    <input type="text" name="section1[breadcrumb]" class="form-control"
                                        value="{{ old('section1.breadcrumb', $section1['breadcrumb'] ?? '') }}"
                                        placeholder="Enter Breadcrumb eg: Home, Blogs">
                                </div>
                               
                                {{-- Banner Image --}}
                                <div class="mb-3">
                                    <label class="form-label">Banner</label>
                                    @if (!empty($section1['banner_image']))
                                        <img width="100" height="60" src="{{ asset('storage/' . $section1['banner_image']) }}"
                                            class="mb-2 d-block" alt="Banner">
                                    @endif
                                    <input type="file" name="section1[banner_image]" class="form-control">
                                </div>

                                {{-- Title --}}
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
 
                                    <input type="text" name="section1[title]" class="form-control"
                                        value="{{ old('section1.title', $section1['title'] ?? '') }}"
                                        placeholder="Enter title">
                                </div>

                                {{-- Highlighted Text --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Section 1 Highlighted Text 1
                                    </label>

                                    <input type="text" name="section1[highlighted_text_1]" class="form-control"
                                        value="{{ old('section1.highlighted_text_1', $section1['highlighted_text_1'] ?? '') }}"
                                        placeholder="Enter highlighted text">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        Section 1 Highlighted Text 2
                                    </label>

                                    <input type="text" name="section1[highlighted_text_2]" class="form-control"
                                        value="{{ old('section1.highlighted_text_2', $section1['highlighted_text_2'] ?? '') }}"
                                        placeholder="Enter highlighted text">
                                </div>

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label class="form-label">Status</label>

                                    <select name="section1[status]" class="form-select">
                                        <option value="active" {{ old('section1.status', $section1['status'] ?? 'active') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="inactive" {{ old('section1.status', $section1['status'] ?? 'active') == 'inactive' ? 'selected' : '' }}>
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
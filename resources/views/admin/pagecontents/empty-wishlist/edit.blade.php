@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => "Edit empty wishlist Page",
                'links' => ['Pages', 'Edit empty wishlist page'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Empty wishlist</h5>
                        </div>
                        @php
                            $page_content = json_decode($empty_wishlist_page->page_contents, true) ?? [];
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

                            <form action="{{ route('admin.pagecontent.empty-wishlist.update', $empty_wishlist_page->id) }}"
                                method="POST" enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                @if (
                                        isset($page_content['section1']['image']) &&
                                        !empty($page_content['section1']['image'])
                                    )
                                    <img width="50" height="50"
                                        src="{{ asset('storage/' . $page_content['section1']['image']) }}" class="mb-2 d-block"
                                        alt="Section 8 Image">
                                @endif

                                <div class="mb-3">
                                    <label class="form-label">Image</label>

                                    <input type="file" name="section1[image]" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="section1[title]" class="form-control"
                                        value="{{ $page_content['section1']['title'] ?? '' }}" }}"
                                        placeholder="Enter title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Text</label>
                                    <input type="text" name="section1[text]" class="form-control"
                                        value="{{ $page_content['section1']['text'] ?? '' }}" placeholder="Enter text">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Button Text</label>
                                    <input type="text" name="section1[btn_text]" class="form-control"
                                        value="{{ $page_content['section1']['btn_text'] ?? '' }}"
                                        placeholder="Enter button text">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Button url</label>
                                    <input type="text" name="section1[btn_url]" class="form-control"
                                        value="{{ $page_content['section1']['btn_url'] ?? '' }}"
                                        placeholder="Enter button url">
                                </div>

                                {{-- ========================================= --}}
                                {{-- STATUS --}}
                                {{-- ========================================= --}}

                                <div class="mb-4">

                                    <label class="form-label">
                                        Status
                                    </label>

                                    <select class="form-select" name="status">

                                        <option value="active" {{ old('status', $page_content['status'] ?? 'active') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>

                                        <option value="inactive" {{ old('status', $page_content['status'] ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>

                                    </select>

                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        update
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
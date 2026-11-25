@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Home Page',
                'links' => ['Pages', 'Edit Home Page'],
            ])

            @php
                $page_content = json_decode($home_page->page_contents, true) ?? [];
            @endphp

            <div class="row">
                <div class="col-md-12">

                    <div class="card">

                        <div class="card-header">
                            <h5>Edit Home Page</h5>
                        </div>

                        <div class="card-body">

                            {{-- Validation Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('admin.pagecontent.home.update', $home_page->id) }}" method="POST"
                                enctype="multipart/form-data">

                                @csrf
                                @method('PUT')

                                {{-- ========================================= --}}
                                {{-- SECTION 1: MAIN BANNER --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 1: Main Banner</h5>
                                    </div>

                                    <div class="card-body">

                                        <div class="mb-3">
                                            @if (isset($page_content['section1']['hero_image']) && !empty($page_content['section1']['hero_image']))
                                                <img width="50" height="50"
                                                    src="{{ asset('storage/' . $page_content['section1']['hero_image']) }}"
                                                    class="mb-2 d-block" alt="Hero Image">
                                            @endif

                                            <label class="form-label">Hero Image</label>
                                            <input type="file" class="form-control" name="section1[hero_image]">
                                        </div>

                                        <div class="mb-3">
                                            <input type="text" class="form-control"
                                                value="{{ $page_content['section1']['hero_title'] ?? '' }}"
                                                name="section1[hero_title]" placeholder="Hero title">
                                        </div>

                                        <div class="mb-3">
                                            <input type="text" class="form-control"
                                                value="{{ $page_content['section1']['hero_text'] ?? '' }}"
                                                name="section1[hero_text]" placeholder="Hero text">
                                        </div>

                                        <div class="mb-3">
                                            <input type="text" class="form-control"
                                                value="{{ $page_content['section1']['hero_btn_text'] ?? '' }}"
                                                name="section1[hero_btn_text]" placeholder="Hero button text">
                                        </div>

                                        <div class="mb-3">
                                            <input type="text" class="form-control"
                                                value="{{ $page_content['section1']['hero_btn_url'] ?? '' }}"
                                                name="section1[hero_btn_url]" placeholder="Hero button URL">
                                        </div>

                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 2: ICONS --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 2: Icon</h5>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">

                                            @for ($i = 1; $i <= 4; $i++)

                                                <div class="col">

                                                    {{-- Icon --}}
                                                    @if (
                                                            isset($page_content['section2']['icon' . $i]) &&
                                                            !empty($page_content['section2']['icon' . $i])
                                                        )
                                                        <img width="50" height="50"
                                                            src="{{ asset('storage/' . $page_content['section2']['icon' . $i]) }}"
                                                            class="mb-2 d-block" alt="Icon {{ $i }}">
                                                    @endif

                                                    <div class="mb-3">
                                                        <label class="form-label">Icon {{ $i }}</label>

                                                        <input type="file" name="section2[icon{{ $i }}]" class="form-control">
                                                    </div>

                                                    {{-- Title --}}
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" placeholder="Title {{ $i }}"
                                                            name="section2[title{{ $i }}]"
                                                            value="{{ $page_content['section2']['title' . $i] ?? '' }}">
                                                    </div>

                                                    {{-- Text --}}
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" placeholder="Text {{ $i }}"
                                                            name="section2[text{{ $i }}]"
                                                            value="{{ $page_content['section2']['text' . $i] ?? '' }}">
                                                    </div>

                                                </div>

                                            @endfor

                                        </div>
                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 3 --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 3</h5>
                                    </div>

                                    <div class="card-body">

                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Title"
                                                name="section3[title]"
                                                value="{{ $page_content['section3']['title'] ?? '' }}">
                                        </div>

                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Text" name="section3[text]"
                                                value="{{ $page_content['section3']['text'] ?? '' }}">
                                        </div>

                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 4 --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 4</h5>
                                    </div>

                                    <div class="card-body">

                                        {{-- Banner Image --}}
                                        @if (
                                                isset($page_content['section4']['banner_image']) &&
                                                !empty($page_content['section4']['banner_image'])
                                            )
                                            <img width="50" height="50"
                                                src="{{ asset('storage/' . $page_content['section4']['banner_image']) }}"
                                                class="mb-2 d-block" alt="Banner Image">
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Banner Image</label>

                                            <input type="file" name="section4[banner_image]" class="form-control">
                                        </div>

                                        {{-- Title --}}
                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Title"
                                                name="section4[title]"
                                                value="{{ $page_content['section4']['title'] ?? '' }}">
                                        </div>

                                        {{-- Text --}}
                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Text" name="section4[text]"
                                                value="{{ $page_content['section4']['text'] ?? '' }}">
                                        </div>

                                        {{-- Button Text --}}
                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Button text"
                                                name="section4[btn_text]"
                                                value="{{ $page_content['section4']['btn_text'] ?? '' }}">
                                        </div>

                                        {{-- Button URL --}}
                                        <div class="mb-3">
                                            <input type="text" class="form-control" placeholder="Button URL"
                                                name="section4[btn_url]"
                                                value="{{ $page_content['section4']['btn_url'] ?? '' }}">
                                        </div>

                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 5 --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 5</h5>
                                    </div>

                                    <div class="card-body">

                                        {{-- Image --}}
                                        @if (
                                                isset($page_content['section5']['image']) &&
                                                !empty($page_content['section5']['image'])
                                            )
                                            <img width="50" height="50"
                                                src="{{ asset('storage/' . $page_content['section5']['image']) }}"
                                                class="mb-2 d-block" alt="Section 5 Image">
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Image</label>

                                            <input type="file" name="section5[image]" class="form-control">
                                        </div>

                                        {{-- Title --}}
                                        <div class="mb-3">
                                            <input class="form-control" type="text" name="section5[title]"
                                                placeholder="Title" value="{{ $page_content['section5']['title'] ?? '' }}">
                                        </div>

                                        {{-- Button Text --}}
                                        <div class="mb-3">
                                            <input class="form-control" type="text" name="section5[btn_text]"
                                                placeholder="Button text"
                                                value="{{ $page_content['section5']['btn_text'] ?? '' }}">
                                        </div>

                                        {{-- Button URL --}}
                                        <div class="mb-3">
                                            <input class="form-control" type="text" name="section5[btn_url]"
                                                placeholder="Button URL"
                                                value="{{ $page_content['section5']['btn_url'] ?? '' }}">
                                        </div>

                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 6 --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 6</h5>
                                    </div>

                                    <div class="card-body">

                                        {{-- Title --}}
                                        <div class="mb-3">
                                            <input type="text" name="section6[title]" class="form-control"
                                                placeholder="Title" value="{{ $page_content['section6']['title'] ?? '' }}">
                                        </div>

                                        {{-- Text --}}
                                        <div class="mb-3">
                                            <input type="text" name="section6[text]" class="form-control" placeholder="Text"
                                                value="{{ $page_content['section6']['text'] ?? '' }}">
                                        </div>

                                    </div>
                                </div>

                                {{-- section 7 gallery --}}
                                {{-- ========================================= --}}
                                {{-- SECTION 8 --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 8</h5>
                                    </div>

                                    <div class="card-body">

                                        {{-- Image --}}
                                        @if (
                                                isset($page_content['section8']['image']) &&
                                                !empty($page_content['section8']['image'])
                                            )
                                            <img width="50" height="50"
                                                src="{{ asset('storage/' . $page_content['section8']['image']) }}"
                                                class="mb-2 d-block" alt="Section 8 Image">
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Image</label>

                                            <input type="file" name="section8[image]" class="form-control">
                                        </div>

                                        {{-- Text --}}
                                        <div class="mb-3">
                                            <input type="text" name="section8[text]" class="form-control" placeholder="Text"
                                                value="{{ $page_content['section8']['text'] ?? '' }}">
                                        </div>

                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 9 --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 9</h5>
                                    </div>

                                    <div class="card-body">

                                        {{-- Title --}}
                                        <div class="mb-3">
                                            <input type="text" name="section9[title]" class="form-control"
                                                placeholder="Title" value="{{ $page_content['section9']['title'] ?? '' }}">
                                        </div>

                                        {{-- Text --}}
                                        <div class="mb-3">
                                            <input type="text" name="section9[text]" class="form-control" placeholder="Text"
                                                value="{{ $page_content['section9']['text'] ?? '' }}">
                                        </div>

                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 10: ICON TRAY --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 10</h5>
                                    </div>

                                    <div class="card-body">

                                        <div class="row">

                                            @for ($i = 1; $i <= 4; $i++)

                                                <div class="col">

                                                    {{-- Icon --}}
                                                    @if (
                                                            isset($page_content['section10']['icon' . $i]) &&
                                                            !empty($page_content['section10']['icon' . $i])
                                                        )
                                                        <img width="50" height="50"
                                                            src="{{ asset('storage/' . $page_content['section10']['icon' . $i]) }}"
                                                            class="mb-2 d-block" alt="Icon {{ $i }}">
                                                    @endif

                                                    <div class="mb-3">
                                                        <label class="form-label">Image {{ $i }}</label>

                                                        <input type="file" name="section10[icon{{ $i }}]" class="form-control">
                                                    </div>

                                                    {{-- Title --}}
                                                    <div class="mb-3">
                                                        <input type="text" name="section10[title{{ $i }}]" class="form-control"
                                                            placeholder="Title {{ $i }}"
                                                            value="{{ $page_content['section10']['title' . $i] ?? '' }}">
                                                    </div>

                                                    {{-- Text --}}
                                                    <div class="mb-3">
                                                        <input type="text" name="section10[text{{ $i }}]" class="form-control"
                                                            placeholder="Text {{ $i }}"
                                                            value="{{ $page_content['section10']['text' . $i] ?? '' }}">
                                                    </div>

                                                </div>

                                            @endfor

                                        </div>

                                    </div>
                                </div>

                                {{-- ========================================= --}}
                                {{-- SECTION 11 --}}
                                {{-- ========================================= --}}

                                <div class="card mb-4">

                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Section 11</h5>
                                    </div>

                                    <div class="card-body">

                                        {{-- Image --}}
                                        @if (
                                                isset($page_content['section11']['image']) &&
                                                !empty($page_content['section11']['image'])
                                            )
                                            <img width="50" height="50"
                                                src="{{ asset('storage/' . $page_content['section11']['image']) }}"
                                                class="mb-2 d-block" alt="Section 11 Image">
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Image</label>

                                            <input type="file" name="section11[image]" class="form-control">
                                        </div>

                                        {{-- Title --}}
                                        <div class="mb-3">
                                            <input type="text" name="section11[title]" class="form-control"
                                                placeholder="Title" value="{{ $page_content['section11']['title'] ?? '' }}">
                                        </div>

                                        {{-- Text --}}
                                        <div class="mb-3">
                                            <input type="text" name="section11[text]" class="form-control"
                                                placeholder="Text" value="{{ $page_content['section11']['text'] ?? '' }}">
                                        </div>

                                    </div>
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


                                {{-- ========================================= --}}
                                {{-- ACTION BUTTONS --}}
                                {{-- ========================================= --}}

                                <div class="d-flex gap-2">

                                    <button type="submit" class="btn btn-primary">
                                        Update Home Page
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
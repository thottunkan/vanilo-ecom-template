@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit About us Page',
                'links' => ['Pages', 'Edit About us page'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit {{ $about_us_page->page_name ?? '' }} Page</h5>
                        </div>
                        @php
                            $page_content = json_decode($about_us_page->page_contents, true) ?? [];
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

                            <form action="{{ route('admin.pagecontent.about-us.update', $about_us_page->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#section1" aria-expanded="true" aria-controls="collapseOne">
                                                <h5>Section 1</h5>
                                            </button>
                                        </h2>
                                        <div id="section1" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="section_1_title" class="form-control"
                                                        value="{{ $page_content['section_1_title'] ?? '' }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Text</label>
                                                    <input type="text" name="section_1_text" class="form-control"
                                                        value="{{ $page_content['section_1_text'] ?? '' }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Button text</label>
                                                    <input type="text" name="section_1_btntext" class="form-control"
                                                        value="{{ $page_content['section_1_btntext'] ?? '' }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Button url</label>
                                                    <input type="text" name="section_1_btnurl" class="form-control"
                                                        value="{{ $page_content['section_1_btnurl'] ?? '' }}">
                                                </div>

                                                <div class="mb-3">
                                                    @if (isset($page_content['section_1_image']))
                                                        <img width="50" height="50"
                                                            src="{{ asset('storage/' . $page_content['section_1_image']) ?? '' }}"
                                                            alt="section 1 image" srcset="">
                                                    @endif

                                                    <label class="form-label">Image</label>
                                                    <input type="file" name="section_1_image" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#section2" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                <h5>Section 2</h5>
                                            </button>
                                        </h2>
                                        <div id="section2" class="accordion-collapse collapse" aria-labelledby="section2"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">

                                                <div class="row">
                                                    <div class="col">
                                                        @if (isset($page_content['section2_icon1']) && !empty($page_content['section2_icon1']))
                                                            <img width="50" height="50"
                                                                src="{{ asset('storage/' . $page_content['section2_icon1']) }}"
                                                                class="mb-2 d-block" alt="Image 3">
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label"> Image</label>
                                                            <input type="file" name="section2_icon1" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_title1" class="form-control"
                                                                value="{{ old('section_2_title1', $page_content['section_2_title1'] ?? '') }}"
                                                                placeholder="Enter title">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_text1" class="form-control"
                                                                value="{{ old('section_2_text1', $page_content['section_2_text1'] ?? '') }}"
                                                                placeholder="Enter text">
                                                        </div>

                                                    </div>
                                                    <div class="col">
                                                        @if (isset($page_content['section2_icon2']) && !empty($page_content['section2_icon2']))
                                                            <img width="50" height="50"
                                                                src="{{ asset('storage/' . $page_content['section2_icon2']) }}"
                                                                class="mb-2 d-block" alt="Image 3">
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label"> Image</label>
                                                            <input type="file" name="section2_icon2" class="form-control">

                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_title2" class="form-control"
                                                                value="{{ old('section_2_title2', $page_content['section_2_title2'] ?? '') }}"
                                                                placeholder="Enter title">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_text2" class="form-control"
                                                                value="{{ old('section_2_text2', $page_content['section_2_text2'] ?? '') }}"
                                                                placeholder="Enter text">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        @if (isset($page_content['section2_icon3']) && !empty($page_content['section2_icon3']))
                                                            <img width="50" height="50"
                                                                src="{{ asset('storage/' . $page_content['section2_icon3']) }}"
                                                                class="mb-2 d-block" alt="Image 3">
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label"> Image</label>
                                                            <input type="file" name="section2_icon3" class="form-control">

                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_title3" class="form-control"
                                                                value="{{ old('section_2_title2', $page_content['section_2_title3'] ?? '') }}"
                                                                placeholder="Enter title">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_text3" class="form-control"
                                                                value="{{ old('section_2_text3', $page_content['section_2_text3'] ?? '') }}"
                                                                placeholder="Enter text">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        @if (isset($page_content['section2_icon4']) && !empty($page_content['section2_icon4']))
                                                            <img width="50" height="50"
                                                                src="{{ asset('storage/' . $page_content['section2_icon4']) }}"
                                                                class="mb-2 d-block" alt="Image 3">
                                                        @endif
                                                        <div class="mb-3">
                                                            <label class="form-label"> Image</label>
                                                            <input type="file" name="section2_icon4" class="form-control">

                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_title4" class="form-control"
                                                                value="{{ old('section_2_title2', $page_content['section_2_title4'] ?? '') }}"
                                                                placeholder="Enter title">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section_2_text4" class="form-control"
                                                                value="{{ old('section_2_text3', $page_content['section_2_text4'] ?? '') }}"
                                                                placeholder="Enter text">
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#section3" aria-expanded="false"
                                                aria-controls="collapseThree">
                                                <h5>Section 3</h5>
                                            </button>
                                        </h2>
                                        <div id="section3" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                @if (isset($page_content['section2_image']) && !empty($page_content['section2_image']))
                                                    <img width="50" height="50"
                                                        src="{{ asset('storage/' . $page_content['section2_image']) }}"
                                                        class="mb-2 d-block" alt="Image 3">
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label"> Image</label>
                                                    <input type="file" name="section2_image" class="form-control">

                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="section_3_title" class="form-control"
                                                        value="{{ old('section_3_title', $page_content['section_3_title'] ?? '') }}"
                                                        placeholder="Enter Section 3 Title">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Text</label>
                                                    <input type="text" name="section_3_text" class="form-control"
                                                        value="{{ old('section_3_text', $page_content['section_3_text'] ?? '') }}"
                                                        placeholder="Enter Section 3 Subtitle">
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#section4" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                <span class="h5 mb-0">Section 4</span>
                                            </button>
                                        </h2>

                                        <div id="section4" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                            data-bs-parent="#accordionExample">

                                            <div class="accordion-body">
                                                @if (isset($page_content['section4_image']) && !empty($page_content['section4_image']))
                                                    <img width="50" height="50"
                                                        src="{{ asset('storage/' . $page_content['section4_image']) }}"
                                                        class="mb-2 d-block" alt="Image 3">
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label"> Image</label>
                                                    <input type="file" name="section4_image" class="form-control">
                                                </div>

                                                <!-- Title -->
                                                <div class=" mb-3">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="section_4_title" class="form-control"
                                                        value="{{ old('section_4_title', $page_content['section_4_title'] ?? '') }}"
                                                        placeholder="Enter Section 4 Title">
                                                </div>

                                                <!-- Text 1 -->
                                                <div class=" mb-3">
                                                    <label class="form-label">Text 1</label>
                                                    <input type="text" name="section_4_text" class="form-control"
                                                        value="{{ old('section_4_text1', $page_content['section_4_text'] ?? '') }}"
                                                        placeholder="Enter Section 4 Text 1">
                                                </div>

                                                <!-- Image 1 -->
                                                <div class=" mb-3">
                                                    <label class="form-label">Button text</label>
                                                    <input type="text" name="section_4_btntext" class="form-control"
                                                        value="{{ old('section_4_btntext', $page_content['section_4_btntext'] ?? '') }}"
                                                        placeholder="Enter buttun text">
                                                </div>
                                                <div class=" mb-3">
                                                    <label for="">Button url</label>
                                                    <input type="text" name="section_4_btnurl" class="form-control"
                                                        value="{{ old('section_4_btnurl', $page_content['section_4_btnurl'] ?? '') }}"
                                                        placeholder="Enter button url">
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                    {{-- section 5 vison and mission --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#section4" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                <span class="h5 mb-0">Section 5: mission and vision</span>
                                            </button>
                                        </h2>

                                        <div id="section4" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="mb-3">
                                                            @if (isset($page_content['section5_icon1']) && !empty($page_content['section5_icon1']))
                                                                <img width="50" height="50"
                                                                    src="{{ asset('storage/' . $page_content['section5_icon1']) }}"
                                                                    class="mb-2 d-block" alt="Image 3">
                                                            @endif
                                                            <input type="file" name="section5_icon1" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section5_title1" class="form-control">
                                                        </div>
                                                        <div class="mb-3">
                                                            <input type="text" name="section5_text1" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="mb-3">
                                                        @if (isset($page_content['section5_icon2']) && !empty($page_content['section5_icon2']))
                                                            <img width="50" height="50"
                                                                src="{{ asset('storage/' . $page_content['section5_icon2']) }}"
                                                                class="mb-2 d-block" alt="Image 3">
                                                        @endif
                                                        <input type="file" name="section5_icon2" class="form-control">

                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="text" name="section5_title2" class="form-control">
                                                    </div>
                                                    <div class="mb-3">
                                                        <input type="text" name="section5_text2" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSix">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#section6" aria-expanded="false"
                                                aria-controls="collapseSix">
                                                <span class="h5 mb-0">Section 6</span>
                                            </button>
                                        </h2>

                                        <div id="section6" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                            data-bs-parent="#accordionExample">

                                            <div class="accordion-body">
                                                @if (isset($page_content['section6_image']) && !empty($page_content['section6_image']))
                                                    <img width="50" height="50"
                                                        src="{{ asset('storage/' . $page_content['section6_image']) }}"
                                                        class="mb-2 d-block" alt="Image 6">
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label">Image</label>
                                                    <input type="file" name="section6_image" class="form-control">
                                                </div>

                                                <!-- Title -->
                                                <div class="mb-3">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="section_6_title" class="form-control"
                                                        value="{{ old('section_6_title', $page_content['section_6_title'] ?? '') }}"
                                                        placeholder="Enter Section 6 Title">
                                                </div>

                                                <!-- Text 1 -->
                                                <div class="mb-3">
                                                    <label class="form-label">Text 1</label>
                                                    <input type="text" name="section_6_text" class="form-control"
                                                        value="{{ old('section_6_text', $page_content['section_6_text'] ?? '') }}"
                                                        placeholder="Enter Section 6 Text">
                                                </div>

                                                <!-- Button Text -->
                                                <div class="mb-3">
                                                    <label class="form-label">Button text</label>
                                                    <input type="text" name="section_6_btntext" class="form-control"
                                                        value="{{ old('section_6_btntext', $page_content['section_6_btntext'] ?? '') }}"
                                                        placeholder="Enter button text">
                                                </div>

                                                <!-- Button URL -->
                                                <div class="mb-3">
                                                    <label for="">Button url</label>
                                                    <input type="text" name="section_6_btnurl" class="form-control"
                                                        value="{{ old('section_6_btnurl', $page_content['section_6_btnurl'] ?? '') }}"
                                                        placeholder="Enter button url">
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingSix">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#section6" aria-expanded="false"
                                                aria-controls="collapseSix">
                                                <span class="h5 mb-0">Section 7</span>
                                            </button>
                                        </h2>

                                        <div id="section6" class="accordion-collapse collapse" aria-labelledby="headingSix"
                                            data-bs-parent="#accordionExample">

                                            <div class="accordion-body">
                                                @if (isset($page_content['section7_image']) && !empty($page_content['section7_image']))
                                                    <img width="50" height="50"
                                                        src="{{ asset('storage/' . $page_content['section7_image']) }}"
                                                        class="mb-2 d-block" alt="Image 6">
                                                @endif
                                                <div class="mb-3">
                                                    <label class="form-label">Image</label>
                                                    <input type="file" name="section7_image" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <input type="text" name="section_7_title" class="form-control"
                                                        value="{{ old('section_7_title', $page_content['section_7_title'] ?? '') }}"
                                                        placeholder="Enter title">
                                                </div>
                                                <div class="mb-3">
                                                    <input type="text" name="section_7_text" class="form-control"
                                                        value="{{ old('section_7_text', $page_content['section_7_text'] ?? '') }}"
                                                        placeholder="Enter text">
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>



                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">

                                        <option value="active" {{ $page_content['status'] == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive" {{ $page_content['status'] == 'inactive' ? 'selected' : '' }}>
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
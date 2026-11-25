@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Create Color',
                'links' => ['Design', 'Color', 'Create color'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create Color</h5>
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

                            <form action="{{ route('admin.color.store') }}" method="post">
                                @csrf
                                <div class="row g-3">

                                    <div class="col-md-3">
                                        <label for="h1" class="fw-semibold">h1 Color</label>
                                        <input type="color" name="h1" id="h1"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="h2" class="fw-semibold">h2 Color</label>
                                        <input type="color" name="h2" id="h2"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="h3" class="fw-semibold">h3 Color</label>
                                        <input type="color" name="h3" id="h3"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="h4" class="fw-semibold">h4 Color</label>
                                        <input type="color" name="h4" id="h4"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="h5" class="fw-semibold">h5 Color</label>
                                        <input type="color" name="h5" id="h5"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="h6" class="fw-semibold">h6 Color</label>
                                        <input type="color" name="h6" id="h6"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="p" class="fw-semibold">p Color</label>
                                        <input type="color" name="p" id="p"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="span" class="fw-semibold">span Color</label>
                                        <input type="color" name="span" id="span"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="strong" class="fw-semibold">strong Color</label>
                                        <input type="color" name="strong" id="strong"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="em" class="fw-semibold">em Color</label>
                                        <input type="color" name="em" id="em"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="small" class="fw-semibold">small Color</label>
                                        <input type="color" name="small" id="small"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="mark" class="fw-semibold">mark Color</label>
                                        <input type="color" name="mark" id="mark"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="blockquote" class="fw-semibold">blockquote Color</label>
                                        <input type="color" name="blockquote" id="blockquote"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="code" class="fw-semibold">code Color</label>
                                        <input type="color" name="code" id="code"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="pre" class="fw-semibold">pre Color</label>
                                        <input type="color" name="pre" id="pre"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="a" class="fw-semibold">Link Color</label>
                                        <input type="color" name="a" id="a"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="img" class="fw-semibold">Image Border Color</label>
                                        <input type="color" name="img" id="img"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="ul" class="fw-semibold">ul Color</label>
                                        <input type="color" name="ul" id="ul"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="ol" class="fw-semibold">ol Color</label>
                                        <input type="color" name="ol" id="ol"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="table" class="fw-semibold">table Color</label>
                                        <input type="color" name="table" id="table"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="video" class="fw-semibold">video Color</label>
                                        <input type="color" name="video" id="video"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="audio" class="fw-semibold">audio Color</label>
                                        <input type="color" name="audio" id="audio"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                    <div class="col-md-3">
                                        <label for="button" class="fw-semibold">button Color</label>
                                        <input type="color" name="button" id="button"
                                            class="form-control form-control-color w-100" value="#ffffff">
                                    </div>

                                </div>

                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                    <a class="btn btn-secondary" href="{{ route('admin.brand.index') }}">Cancel</a>
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

@endsection

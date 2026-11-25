@extends('admin.layout.admin-layout')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Create FAQ',
                'links' => ['Create FAQ'],
            ])

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-header">
                            <h5>Create FAQ</h5>
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

                            <form action="{{ route('admin.faq.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- <div class="mb-3">
                                <label class="form-label">Banner Image</label>
                                <input type="file"
                                       name="banner_image"
                                       class="form-control">
                            </div> --}}

                                <div class="mb-3">
                                    <label class="form-label">FAQ Question</label>
                                    <input type="text" name="faq_qn" class="form-control" value="{{ old('faq_qn') }}"
                                        required>
                                </div>



                                <div class="mb-3">
                                    <label class="form-label">FAQ Answer</label>
                                    <textarea name="faq_ans" class="form-control" rows="5" required>{{ old('faq_ans') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">FAQ Category</label>
                                    <select name="category_id" id="" class="form-control">
                                        @foreach ($faqcategory as $fc)
                                            <option value="{{ $fc->id }}">{{ $fc->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="inactive"
                                            {{ old('status', $faq->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                        <option value="active"
                                            {{ old('status', $faq->status ?? '') == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        Create
                                    </button>

                                    <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary">
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

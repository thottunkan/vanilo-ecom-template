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
                            <h5>Create FAQ Category</h5>
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

                            <form action="{{ route('admin.faq-category.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- <div class="mb-3">
                                <label class="form-label">Banner Image</label>
                                <input type="file"
                                       name="banner_image"
                                       class="form-control">
                            </div> --}}

                                <div class="mb-3">
                                    <label class="form-label">Category name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('category_name') }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="inactive"
                                            {{ old('status', $faqcategory->status ?? '') == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                        <option value="active"
                                            {{ old('status', $faqcategory->status ?? '') == 'active' ? 'selected' : '' }}>
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

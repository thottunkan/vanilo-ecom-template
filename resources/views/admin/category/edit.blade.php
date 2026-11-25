@extends('admin.layout.admin-layout')

@section('content')
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Category',
                'links' => ['Catelog', 'Product', 'Edit category'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Category</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.category.update', $category->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @method('PUT')
                                @csrf
                                <div class="card-body">

                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" value="{{ $category->name . ' ' . $category->parent_id ?? '' }}"
                                            name="name" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Parent Category</label>

                                        <select name="parent_id" class="form-control">
                                            <option value="">--None--</option>

                                            @foreach ($categories as $c)
                                                <option value="{{ $c->id }}"
                                                    {{ ($c->id ?? null) == $category->parent_id ? 'selected' : '' }}>
                                                    {{ $c->name . ' ' . $c->id }}
                                                </option>
                                            @endforeach

                                        </select>

                                    </div>
                                    <div class="mb-3">
                                        <img width="50" height="50"
                                            src="{{ asset($category->image_path) ?? '' }}" alt="category image"
                                            srcset="">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">image</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                    </div>


                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-primary">
                                        Update
                                    </button>
                                    <a href="{{ route('admin.category.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
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
    {{-- <div class="mt-3">
            {{ $products->links() }}
        </div> --}}
@endsection

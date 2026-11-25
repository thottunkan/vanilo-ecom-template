@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit Tax Category',
                'links' => ['Sales', 'Tax', 'Edit tax category'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit Tax Category</h5>
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
                            <form action="{{ route('admin.taxcategory.update', $tax_category->id) }}" method="POST">
                                @method('POST')
                                @csrf

                                <div class="mb-3">
                                    <label for="">Category type</label>

                                    <select name="type" class="form-control">
                                        @foreach ($types as $type)
                                            <option value="{{ strtolower($type) }}"
                                                {{ strtolower($tax_category->type) === strtolower($type) ? 'selected' : '' }}>
                                                {{ str_replace('_', ' ', $type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Category name</label>
                                    <input type="text" value="{{ $tax_category->name }}" name="name"
                                        class="form-control">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
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

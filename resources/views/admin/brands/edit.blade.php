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
                'links' => ['Sales', 'Tax rates', 'Edit tax category'],
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
                            <form action="{{ route('admin.brand.update',$brand->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="mb-3">
                                    <label for="fw-semibold">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $brand->name ?? '' }}" required>
                                </div>
                              
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a class="btn btn-secondary" href="{{ route('admin.brand.index') }}">Cancel</a>
                                </div>

                            </form>
                            {{-- content --}}

                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

@endsection

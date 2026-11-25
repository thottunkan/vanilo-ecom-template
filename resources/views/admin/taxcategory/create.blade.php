@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'is_visible' => true,
                'title' => 'Create Tax Category',
                'links' => ['Sales', 'Tax', 'Create Tax category'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create Tax Category</h5>
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
                            <form action="{{ route('admin.taxcategory.store') }}" method="POST">
                                @method('POST')
                                @csrf
                                <div class="mb-3">
                                    <label for="">Category type</label>
                                    <select name="type" id="" class="form-control">
                                        @foreach ($types as $type)
                                            <option value="{{ strtolower($type)  }}">{{  str_replace('_', ' ', $type)  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Category name</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="mb-3">
                                 <button type="submit" class="btn btn-primary">Create</button>
                                 <a class="btn btn-secondary" href="{{ route('admin.taxcategory.index') }}">Cancel</a>
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

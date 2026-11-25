@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Create Tax Rate',
                'links' => ['Sales', 'Tax', 'Create tax rate'],
            ])
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create Tax Rate</h5>
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

                            <form action="{{ route('admin.taxrate.store') }}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="fw-semibold">Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="fw-semibold">Tax category</label>
                                    <select name="tax_category_id" id="" class="form-control">
                                        @foreach ($tax_categories as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fw-semibold">Tax Zone</label>
                                    <select name="zone_id" id="" class="form-control">
                                       
                                        @foreach ($zones as $z)
                                            <option value="{{ $z->id }}">{{ $z->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="fw-semibold">Rate</label>
                                    <input type="text" name="rate" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                    <a class="btn btn-secondary" href="{{ route('admin.taxrate.index') }}">Cancel</a>
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

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
                            <form action="{{ route('admin.taxrate.update',$tax_rate->id) }}" method="post">
                                @method('PUT')
                                @csrf
                                <div class="mb-3">
                                    <label for="fw-semibold">Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $tax_rate->name ?? '' }}" required>
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
                                    <input type="text" name="rate" value="{{ $tax_rate->rate ?? ""}}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Update</button>
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

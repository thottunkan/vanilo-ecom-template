@extends('admin.layout.admin-layout')

@section('title', 'Create Menu')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'title' => 'Create Review',
                'button_text' => 'Back',
                'url' => route('admin.review.index'),
                'links' => ['Menus', 'Create'],
            ])

            <div class="row">
                <div class="col-md-8">
                    <div class="card">

                        <div class="card-header">
                            <h5>Create Review</h5>
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

                            <form action="{{ route('admin.review.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="">rating</label>
                                    <input type="text" name="rating" id="">
                                </div>

                                <div class="mb-3">
                                    <label for="">comment</label>
                                    <input type="text" name="comments" id="">
                                </div>

                                <div class="mb-3">
                                    <label for="">product_id</label>
                                    <input type="text" name="product_id" id="">
                                </div>

                                <div class="mb-3">
                                    <label for="">user_id</label>
                                    <input type="text" name="user_id" id="">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Save
                                </button>

                                <a href="{{ route('admin.review.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

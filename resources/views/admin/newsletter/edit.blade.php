@extends('admin.layout.admin-layout')

@section('content')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->

            @include('admin.includes.page-header', [
                'button_text' => null,
                'url' => null,
                'title' => 'Edit newsletter',
                'links' => ['Newsletter', 'Edit newsletter'],
            ])
            <!-- [ breadcrumb ] end -->

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Edit newsletter</h5>
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

                            <form action="{{ route('admin.newsletter.update', $newsletter->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <input type="email" name="email" value="{{ old('email', $newsletter->email) }}"
                                        class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <select name="is_subscribed" class="form-control">
                                        <option value="1"
                                            {{ old('is_subscribed', $newsletter->is_subscribed) == 1 ? 'selected' : '' }}>
                                            Subscribe
                                        </option>
                                        <option value="0"
                                            {{ old('is_subscribed', $newsletter->is_subscribed) == 0 ? 'selected' : '' }}>
                                            Unsubscribe
                                        </option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
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

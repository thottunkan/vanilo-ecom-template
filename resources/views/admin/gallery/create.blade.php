```blade
@extends('admin.layout.admin-layout')

@section('title', 'Create Gallery')

@section('content')

    <div class="pc-container">
        <div class="pc-content">

            @include('admin.includes.page-header', [
                'title' => 'Add Gallery Images',
                'button_text' => 'Back',
                'url' => null,
                'links' => ['Gallery'],
            ])

            <div class="row">
                <div class="col">
                    <div class="card">

                        <div class="card-header">
                            <h5>Add Gallery Images</h5>
                        </div>

                        @php
                            /*
                             * The images field may already be an array if
                             * the model has a JSON cast.
                             *
                             * If it is still a JSON string, decode it.
                             */
                            if (is_array($gallery->images ?? null)) {
                                $galleryImages = $gallery->images;
                            } elseif (is_string($gallery->images ?? null)) {
                                $galleryImages = json_decode($gallery->images, true) ?? [];
                            } else {
                                $galleryImages = [];
                            }
                        @endphp

                        <div class="card-body">

                            {{-- Validation Errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form
                                action="{{ route('admin.gallery.store') }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >
                                @csrf
                                @method('PUT')
                                {{-- 
                                    Do NOT use @method('PUT') here if this is
                                    the store route for creating a gallery.
                                --}}

                                {{-- Gallery Images --}}
                                @for ($i = 1; $i <= 16; $i++)

                                    @php
                                        $imageKey = 'gallery_image' . $i;
                                        $imagePath = $galleryImages[$imageKey] ?? null;
                                    @endphp

                                    <div class="mb-4">

                                        {{-- Existing Gallery Image --}}
                                        @if (!empty($imagePath))
                                            <div class="mb-2">
                                                <img
                                                    src="{{ asset('storage/' . $imagePath) }}"
                                                    width="100"
                                                    height="100"
                                                    class="d-block rounded border"
                                                    style="object-fit: cover;"
                                                    alt="Gallery Image {{ $i }}"
                                                >
                                            </div>
                                        @endif

                                        <label
                                            for="{{ $imageKey }}"
                                            class="form-label fw-bold"
                                        >
                                            Gallery Image {{ $i }}
                                        </label>

                                        <input
                                            type="file"
                                            name="{{ $imageKey }}"
                                            id="{{ $imageKey }}"
                                            class="form-control"
                                            accept="image/*"
                                        >

                                        @error($imageKey)
                                            <div class="text-danger mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                @endfor

                                {{-- Status --}}
                                <div class="mb-3">
                                    <label
                                        for="status"
                                        class="form-label fw-bold"
                                    >
                                        Status
                                    </label>

                                    <select
                                        name="status"
                                        id="status"
                                        class="form-control"
                                    >
                                        <option
                                            value="inactive"
                                            {{ old('status', $gallery->status ?? 'inactive') === 'inactive' ? 'selected' : '' }}
                                        >
                                            Inactive
                                        </option>

                                        <option
                                            value="active"
                                            {{ old('status', $gallery->status ?? '') === 'active' ? 'selected' : '' }}
                                        >
                                            Active
                                        </option>
                                    </select>

                                    @error('status')
                                        <div class="text-danger mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- Submit --}}
                                <div class="mt-4">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Save
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
```

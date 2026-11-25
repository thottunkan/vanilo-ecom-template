{{-- <ul class="list-group list-group-flush">

    @foreach ($categories as $category)

        @php
            $selected = in_array($category->id, $selected_categories ?? []);
        @endphp

        <li class="list-group-item border-0 py-1">

            <div class="form-check">

                <input 
                    class="form-check-input" 
                    type="checkbox" 
                    value="{{ $category->id }}" 
                    name="taxons[]" 
                    id="category_{{ $category->id }}"
                    {{ $selected ? 'checked' : '' }}
                >

                <label 
                    class="form-check-label" 
                    for="category_{{ $category->id }}"
                >
                    {{ $category->name }}
                </label>

            </div>


            @if ($category->children && $category->children->isNotEmpty())

                <div class="ms-4 mt-1 border-start border-2 ps-3">

                    @include('admin.partials.category-tree', [
                        'categories' => $category->children,
                        'selected_categories' => $selected_categories ?? []
                    ])

                </div>

            @endif

        </li>

    @endforeach

</ul> --}}

@foreach ($categories as $category)
    @php
        $selected = in_array($category->id, $selected_categories ?? []);
    @endphp

    <li class="py-1 list-unstyled">
        <div class="form-check">
            <input 
                class="form-check-input" 
                type="checkbox" 
                value="{{ $category->id }}" 
                name="taxons[]" 
                id="category_{{ $category->id }}"
                {{ $selected ? 'checked' : '' }}
            >
            <label class="form-check-label" for="category_{{ $category->id }}">
                {{ $category->name }}
            </label>
        </div>

        {{-- Standard unstyled list with a clean border for children --}}
        @if ($category->children && $category->children->isNotEmpty())
            <ul class="list-unstyled ms-4 mt-1 border-start border-2 ps-3">
                @include('admin.partials.category-tree', [
                    'categories' => $category->children,
                    'selected_categories' => $selected_categories ?? []
                ])
            </ul>
        @endif
    </li>
@endforeach
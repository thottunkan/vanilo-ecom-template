                            @foreach ($categories as $category)
                                @php
                                    $selected = in_array($category->slug, (array) request('categories', []));
                                @endphp

                                <li class="py-1 list-unstyled">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="categories[]"
                                            value="{{ $category->slug }}" id="category_{{ $category->id }}"
                                            @checked($selected)>

                                        <label class="form-check-label d-flex justify-content-between w-100"
                                            for="category_{{ $category->id }}">
                                            <span>{{ $category->name }}</span>
                                            <span class="text-muted">({{ $category->product_count }})</span>
                                        </label>
                                    </div>

                                    @if ($category->children && $category->children->isNotEmpty())
                                        <ul class="list-unstyled ms-4 mt-1 border-start border-2 ps-3">
                                            @foreach ($category->children as $child)
                                                @php
                                                    $childSelected = in_array(
                                                        $child->slug,
                                                        (array) request('categories', []),
                                                    );
                                                @endphp

                                                <li class="py-1 list-unstyled">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="categories[]" value="{{ $child->slug }}"
                                                            id="category_{{ $child->id }}"
                                                            @checked($childSelected)>

                                                        <label
                                                            class="form-check-label d-flex justify-content-between w-100"
                                                            for="category_{{ $child->id }}">
                                                            <span>{{ $child->name }}</span>
                                                            <span
                                                                class="text-muted">({{ $child->product_count }})</span>
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach

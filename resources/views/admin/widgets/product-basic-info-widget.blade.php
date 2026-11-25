                                        <div class="mb-3">
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Sub title</label>
                                            <input type="text" class="form-control" name="subtitle"
                                                value="{{ old('name') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>SKU</label>
                                            <input type="text" class="form-control" name="sku"
                                                value="{{ old('sku') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Stock</label>
                                            <input type="text" class="form-control" name="stock"
                                                value="{{ old('stock') }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Price</label>
                                            <input type="number" class="form-control" step="0.01" name="price"
                                                value="{{ old('price') }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label>Status</label>
                                            <select name="state" class="form-control" id="">
                                                <option value="draft">Draft</option>
                                                <option selected value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Tax Category</label>
                                            <select name="tax_category_id" class="form-control" id="tax_category">
                                                <option selected value="">--choose--</option>
                                                @foreach ($tax_categories as $tc)
                                                    <option value="{{ $tc->id }}">{{ $tc->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Applicable Tax Rates</label>
                                            <select name="tax_rate_id" class="form-control" id="applicable_tax_rates">
                                            </select>
                                        </div>
                                        <input type="text" hidden id="tax_rate" name="tax_rate">

                                        <div class="mb-3">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description">{{ old('description') }}</textarea>
                                        </div>

                                        {{-- IMAGES --}}
                                        <hr>
                                        <div class="mb-3">
                                            <label>Images</label>
                                            <input type="file" class="form-control" name="images[]" multiple
                                                accept="image/*">
                                        </div>
                                        <div class="mb-3">
                                            <label>
                                                <input type="checkbox" class="form-check-input"
                                                    name="is_thumbnail_image_required" id="is_thumbnail_image_required">
                                                Need Thumbnail Images?
                                            </label>

                                            <input type="file" class="form-control mt-2" id="thumbnail_images"
                                                name="thumbnail_images[]" multiple accept="image/*"
                                                style="display: none;">
                                        </div>

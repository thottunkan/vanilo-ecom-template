<label>
    <input type="checkbox" class="form-check-input" name="is_variant_required" id="is_variant_required">
    Need variants?
</label>

<br><br>

<div id="variants-section" style="display: none;">
    <h4>Variants</h4> <br>
    <div id="variants-wrapper">

        {{-- <div class="variant-item row g-3 align-items-end mb-3 p-3 border rounded">

                                            <div class="col-md-3">
                                                <label class="form-label">Price</label>
                                                <input type="number" class="form-control" name="variants[0][price]"
                                                    placeholder="Price">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Stock</label>
                                                <input type="number" class="form-control" name="variants[0][stock]"
                                                    placeholder="Stock">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Color</label>
                                                <input type="text" class="form-control" name="variants[0][color]"
                                                    placeholder="Color">
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Size</label>
                                                <input type="text" class="form-control" name="variants[0][size]"
                                                    placeholder="Size">
                                            </div>

                                            <div class="col-md-3">
                                                <button type="button" class="btn btn-danger w-100"
                                                    onclick="removeVariant(this)">
                                                    <i class="ph ph-trash"></i>
                                                    Remove
                                                </button>
                                            </div>

                                        </div> --}}

    </div>

    <button type="button" class="btn btn-primary" onclick="addVariant()">
        Add Variant
    </button>
</div>

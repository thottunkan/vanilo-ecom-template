<label>
    <input type="checkbox" class="form-check-input" name="is_custom_attributes_required" id="is_custom_attributes_required">
    Need custom attributes
</label>

<div id="custom-attributes-section" style="display: none;" class="mt-3">

    <div id="attributes-wrapper">

        {{-- <div class="attribute-item row g-3 align-items-end mb-3">

                                                <div class="col-md-5">
                                                    <label class="form-label">Attribute Name</label>
                                                    <input type="text" class="form-control" name="attributes[0][name]"
                                                        placeholder="e.g. Material">
                                                </div>

                                                <div class="col-md-5">
                                                    <label class="form-label">Attribute Value</label>
                                                    <input type="text" class="form-control" name="attributes[0][value]"
                                                        placeholder="e.g. Cotton">
                                                </div>

                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger w-100"
                                                        onclick="removeCustomAttribute(this)">
                                                        Remove
                                                    </button>
                                                </div>

                                            </div> --}}

    </div>

    <button type="button" class="btn btn-primary" onclick="addAttribute()">
        Add Attribute
    </button>

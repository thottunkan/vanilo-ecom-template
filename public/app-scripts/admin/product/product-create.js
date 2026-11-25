let variantIndex = 1;

function addVariant() {

    const wrapper = document.getElementById('variants-wrapper');

    const html = `
    <div class="variant-item row g-3 align-items-end mb-3 p-3 border rounded">

        <div class="col-md-3">
            <label class="form-label">Price</label>
            <input 
                type="number"
                class="form-control"
                name="variants[${variantIndex}][price]"
                placeholder="Price"
                required>
        </div>


        <div class="col-md-2">
            <label class="form-label">Stock</label>
            <input 
                type="number"
                class="form-control"
                name="variants[${variantIndex}][stock]"
                placeholder="Stock">
        </div>


        <div class="col-md-2">
            <label class="form-label">Color</label>
            <input 
                type="text"
                class="form-control"
                name="variants[${variantIndex}][color]"
                placeholder="Color">
        </div>


        <div class="col-md-2">
            <label class="form-label">Size</label>
            <input 
                type="text"
                class="form-control"
                name="variants[${variantIndex}][size]"
                placeholder="Size">
        </div>


        <div class="col-md-3">

            <button 
                type="button"
                class="btn btn-danger w-100"
                onclick="removeVariant(this)">
                <i class="ph ph-trash"></i>
                Remove
            </button>

        </div>

    </div>
    `;


    wrapper.insertAdjacentHTML('beforeend', html);

    variantIndex++;
}



function removeVariant(button) {

    const variant = button.closest('.variant-item');

    const variants = document.querySelectorAll('.variant-item');


    // keep at least one variant
    // if (variants.length === 1) {
    //     alert('At least one variant is required.');
    //     return;
    // }


    variant.remove();
}

$(document).ready(function () {
    $('#is_thumbnail_image_required').on('change', function () {
        if ($(this).is(':checked')) {
            $('#thumbnail_images').show();
        } else {
            $('#thumbnail_images').hide().val(''); // Clear selected files
        }
    });
});

$(document).ready(function () {

    $('#is_variant_required').on('change', function () {

        if ($(this).is(':checked')) {
            $('#variants-section').show();
        } else {
            $('#variants-section').hide();

            // Optional: Clear all inputs
            $('#variants-section').find('input').val('');
        }

    });

});

// custom attribute code
$(document).ready(function () {

    $('#is_custom_attributes_required').on('change', function () {

        if ($(this).is(':checked')) {
            $('#custom-attributes-section').slideDown();
        } else {
            $('#custom-attributes-section').slideUp();
            $('#custom-attributes-section').find('input').val('');
        }

    });

});

let attributeIndex = 1;

// Make function global
window.addAttribute = function () {

    let html = `
        <div class="attribute-item row g-3 align-items-end mb-3">

            <div class="col-md-5">
                <input type="text"
                       class="form-control"
                       name="attributes[${attributeIndex}][name]"
                       placeholder="Attribute Name">
            </div>

            <div class="col-md-5">
                <input type="text"
                       class="form-control"
                       name="attributes[${attributeIndex}][value]"
                       placeholder="Attribute Value">
            </div>

            <div class="col-md-2">
                <button type="button"
                        class="btn btn-danger w-100"
                        onclick="removeCustomAttribute(this)">
                    Remove
                </button>
            </div>

        </div>
    `;

    $('#attributes-wrapper').append(html);
    attributeIndex++;
};

// Make function global
function removeCustomAttribute(button) {
    const attribute = button.closest('.attribute-item');
    attribute.remove();
}
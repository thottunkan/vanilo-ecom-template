$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': window.csrfToken
    }
});

$(document).on('click', '#add-to-cart', function (e) {
    e.preventDefault();

    const button = $(this);

    const product_id = button.data('product-id');
    const variant_id = button.data('variant-id') || null;
    const quantity = button.data('quantity') || 1;
    console.log(quantity);
    button.prop('disabled', true);

    const formData = new FormData();

    formData.append('_token', window.csrfToken);
    formData.append('master_product_id', product_id);
    formData.append('variant_id', variant_id ?? null);
    formData.append('quantity', quantity);

    $.ajax({
        url: window.routes.addToCart,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,

        success: function (response) {
            button.prop('disabled', false);

            if (response.success) {
                button
                    .removeClass('btn-primary')
                    .addClass('btn-success')
                    .html('<i class="ti ti-check"></i> Added');

                if ($('#cart-count').length) {
                    $.get(window.routes.cartCount, function (res) {
                        $('#cart-count').text(res.count);
                    });
                }
            } else {
                console.log(response.message || 'Unable to add product.');
            }
        },

        error: function (xhr) {
            button.prop('disabled', false);

            let message = 'Something went wrong.';

            if (xhr.status === 419) {
                message = 'Your session has expired. Please refresh the page and try again.';
            } else if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                if (xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
            }

            console.error(message);
        }
    });
});
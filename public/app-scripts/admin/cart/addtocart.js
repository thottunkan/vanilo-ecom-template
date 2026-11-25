$(document).on('click', '.add-to-cart', function (e) {
    
   
    const button = $(this);
    const product_id = button.data('product-id');
    const variant_id = button.data('variant-id') || null;
    const quantity = button.data('quantity') || 1;
    button.prop('disabled', true);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        url: window.routes.addToCart,
        type: 'POST',
        data: {
            product_id: product_id,
            variant_id: variant_id,
            quantity: quantity
        },
        success: function (response) {
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

                toastr.success(response.message);
            } else {

                button.prop('disabled', false);
                alert('unable to add product');
                //toastr.error(response.message || 'Unable to add product.');
            }
        },

        error: function (xhr) {
            button.prop('disabled', false);
            let message = 'Something went wrong.';
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                if (xhr.responseJSON.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
            }
            toastr.error(message);
        }
    });
});


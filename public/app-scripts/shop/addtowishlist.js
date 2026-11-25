(function () {
    function refreshWishlistCount() {
        if (!window.shopRoutes || !window.shopRoutes.wishlistCount) {
            console.error('wishlistCount route is not defined on window.shopRoutes');
            return;
        }

        $.ajax({
            url: window.shopRoutes.wishlistCount,
            type: 'GET',
            dataType: 'json',
            cache: false,
            headers: { Accept: 'application/json' },
            success: function (response) {
                console.log('Wishlist count response:', response);
                const count = parseInt(response.count, 10) || 0;
                $('#wishlist-count').text(count);
            },
            error: function (xhr) {
                console.error('Wishlist count error:', xhr.status, xhr.responseText);
            }
        });
    }

    $(document).on('click', '.add-to-wishlist', function (e) {
        e.preventDefault();

        const button = $(this);
      
        
        const productId = button.data('product-id');
        const variantId = button.data('variant-id') || null;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        //button.prop('disabled', true);

        const formData = new FormData();
        formData.append('product_id', productId);
        if (variantId !== null) {
            formData.append('variant_id', variantId);
        }

        $.ajax({
            url: window.shopRoutes.addToWishlist,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                Accept: 'application/json'
            },
            success: function (response) {
                console.log('Add wishlist response:', response);
              //  button.prop('disabled', false);

                // Call this unconditionally on a successful HTTP response
                // so the count stays correct even if response.success is missing/false
                refreshWishlistCount();

                if (response.status) {
                    button
                        // .removeClass('btn-outline-danger ')
                        // .addClass('btn-danger')
                        .html(response.message);
                } else {
                     button
                        // .removeClass('btn-danger ')
                        // .addClass('btn-outline-danger')
                        .html(response.message);
                }
            },
            error: function (xhr) {
                button.prop('disabled', false);
                console.error('Add wishlist error:', xhr.responseJSON || xhr);
            }
        });
    });
})();
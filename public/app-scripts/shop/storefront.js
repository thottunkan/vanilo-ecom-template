(function () {
  const csrf = () =>
    window.csrfToken ||
    document.querySelector('meta[name="csrf-token"]')?.content ||
    '';

  const refreshCount = () =>
    fetch(window.shopRoutes.cartCount, {
      headers: {
        Accept: 'application/json',
      },
    })
      .then((r) => r.json())
      .then((d) => {
        const cartCount = document.getElementById('cart-count');

        if (cartCount) {
          cartCount.textContent = d.count ?? 0;
        }
      })
      .catch(() => { });

  $(document).on('click', '.js-add-cart', function () {
    const b = $(this);
    const id = b.data('product-id');
    const variant = b.data('variant-id') || null;
    const qty = parseInt($('#quantity').val(), 10) || 1;
    //const qty = Number(b.data('quantity') || 1);
    console.log('qty:', qty);
    b.prop('disabled', true);

    $.ajax({
      url: window.shopRoutes.addToCart,
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf(),
        Accept: 'application/json',
      },
      contentType: 'application/json',
      data: JSON.stringify({
        master_product_id: id,
        variant_id: variant,
        quantity: qty,
      }),
    })
      .done((response) => {
        console.log('res:',response.message);
        if(response.status){
            b.text(response.message);
        }else{
            b.text('Login first');
        }
        
        refreshCount();
        setTimeout(() => {
          b.text('Add to cart').prop('disabled', false);
        }, 1200);
      })
      .fail((x) => {
        alert(
          x.responseJSON?.message ||
          'Unable to add product.'
        );

        b.prop('disabled', false);
      });
  });

  // $(document).on('click', '.js-add-wishlist', function () {
  //   const b = $(this);
  //   $.ajax({
  //     url: window.shopRoutes.addWishlist,
  //     method: 'POST',
  //     headers: {
  //       'X-CSRF-TOKEN': csrf(),
  //       Accept: 'application/json',
  //     },
  //     data: {
  //       product_id: b.data('product-id'),
  //       variant_id: null
  //     },
  //   })
  //     .done(() => {
  //       b.text('♥ Added');
  //       refreshCount();

  //     })
  //     .fail((x) => {
  //       alert(
  //         x.status === 401
  //           ? 'Please login first.'
  //           : (
  //             x.responseJSON?.message ||
  //             'Unable to update wishlist.'
  //           )
  //       );
  //     });
  // });

})();

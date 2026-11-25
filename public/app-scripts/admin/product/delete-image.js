// $(document).on('click', '.delete-image', function () {

//     if (!confirm('Are you sure you want to delete this image?')) {
//         return;
//     }

//     let button = $(this);
//     let productId = button.data('product');
//     let imageId = button.data('image');

//     let url =
//         "{{ route('admin.product.delete_product_image', ['product_id' => '__PRODUCT__', 'image_id' => '__IMAGE__']) }}";
//     url = url.replace('__PRODUCT__', productId)
//         .replace('__IMAGE__', imageId);

//     $.ajax({
//         url: url,
//         type: 'DELETE',
//         data: {
//             _token: "{{ csrf_token() }}"
//         },
//         success: function (response) {
//             button.closest('.col-md-2').fadeOut(300, function () {
//                 $(this).remove();
//             });

//             alert(response.message);
//         },
//         error: function (xhr) {
//             alert(xhr.responseJSON?.message ?? 'Failed to delete image.');
//         }
//     });

// });

$(document).on('click', '.delete-image', function () {

    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }

    let button = $(this);

    let productId = button.data('product');
    let imageId = button.data('image');

    let url = window.deleteImageConfig.urlTemplate
        .replace('__PRODUCT__', productId)
        .replace('__IMAGE__', imageId);

    $.ajax({
        url: url,
        type: 'DELETE',
        data: {
            _token: window.deleteImageConfig.csrf
        },
        success: function (response) {
            button.closest('.col-md-2').fadeOut(300, function () {
                $(this).remove();
            });

            alert(response.message);
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.message ?? 'Failed to delete image.');
        }
    });

});

//delete-thumbnail-image
$(document).on('click', '.delete-thumbnail-image', function (e) {
    e.preventDefault();

    if (!confirm('Are you sure you want to delete this image?')) {
        return;
    }

    const button = $(this);
    const productId = button.data('product');
    const imageId = button.data('image');

    const url = window.deleteThumbnailImageConfig.urlTemplate
        .replace('__PRODUCT__', productId)
        .replace('__IMAGE__', imageId);

    button.prop('disabled', true);

    $.ajax({
        url: url,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': window.deleteThumbnailImageConfig.csrf
        },
        success: function (response) {
            button.closest('.col-md-2').fadeOut(300, function () {
                $(this).remove();
            });

            alert(response.message || 'Image deleted successfully.');
        },
        error: function (xhr) {
            button.prop('disabled', false);

            let message = 'Failed to delete image.';

            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            alert(message);
        }
    });
});
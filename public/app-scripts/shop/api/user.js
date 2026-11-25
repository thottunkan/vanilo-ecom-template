function getCurrentUser(onSuccess, onError) {
    $.ajax({
        url: '/api/user',
        type: 'GET',
        headers: apiHeaders(),
        success: function (response) {
            if (response.status) {
                localStorage.setItem(
                    'user',
                    JSON.stringify(response.data)
                );
            }
            if (typeof onSuccess === 'function') {
                onSuccess(response);
            }
        },
        error: function (xhr) {
            const error = handleApiError(xhr);
            if (typeof onError === 'function') {
                onError(error);
            }
        }
    });
}


function changePassword(
    currentPassword,
    password,
    passwordConfirmation,
    onSuccess,
    onError
) {
    $.ajax({
        url: '/api/change-password',
        type: 'POST',
        headers: apiHeaders(),
        data: {
            current_password: currentPassword,
            password: password,
            password_confirmation: passwordConfirmation
        },
        success: function (response) {

            if (typeof onSuccess === 'function') {
                onSuccess(response);
            }
        },
        error: function (xhr) {
            const error = handleApiError(xhr);
            if (typeof onError === 'function') {
                onError(error);
            }
        }
    });
}
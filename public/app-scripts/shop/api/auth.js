function registerUser(formData, onSuccess, onError) {
    $.ajax({
        url: '/api/register',
        type: 'POST',
        headers: apiHeaders(),
        data: formData,
        success: function (response) {
            if (response.status) {
                saveAuthData(response.data);
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


function loginUser(email, password, onSuccess, onError) {
    $.ajax({
        url: '/api/login',
        type: 'POST',
        headers: apiHeaders(),
        data: {
            email: email,
            password: password
        },
        success: function (response) {
            if (response.status) {
                saveAuthData(response.data);
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
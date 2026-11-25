function getApiToken() {
    return localStorage.getItem('api_token');
}

function apiHeaders() {
    const token = getApiToken();

    const headers = {
        'Accept': 'application/json'
    };

    if (token) {
        headers['Authorization'] = 'Bearer ' + token;
    }

    return headers;
}

function handleApiError(xhr) {
    if (xhr.status === 401) {
        localStorage.removeItem('api_token');
        localStorage.removeItem('user');
    }

    if (xhr.responseJSON) {
        return xhr.responseJSON;
    }

    return {
        status: false,
        message: 'Something went wrong. Please try again.',
        errors: null
    };
}

function saveAuthData(data) {
    if (data.token) {
        localStorage.setItem('api_token', data.token);
    }

    if (data.user) {
        localStorage.setItem(
            'user',
            JSON.stringify(data.user)
        );
    }
}

function logoutUser() {
    localStorage.removeItem('api_token');
    localStorage.removeItem('user');
}

function getStoredUser() {
    const user = localStorage.getItem('user');

    return user ? JSON.parse(user) : null;
}

function isAuthenticated() {
    return !!localStorage.getItem('api_token');
}
$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        // Create FormData from form element
        const formData = new FormData(form);
        loginUser(
            formData,
            function (response) {
                if (response.status) {
                    console.log(response);
                    alert(response.message);
                    window.location.href = '/';
                } else {
                    alert(response.message);
                }
            },
            function (error) {
                console.log(error);
                if (error.errors) {
                    console.log(error.errors);
                }
                alert(error.message);
            }
        );
    });
});
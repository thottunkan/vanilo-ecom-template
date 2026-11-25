$(document).ready(function () {
    $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        registerUser(
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
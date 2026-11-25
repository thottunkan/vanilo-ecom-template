// document.addEventListener("DOMContentLoaded", function () {
//     document.getElementById("custom_css_form").addEventListener("submit", function () {
//         alert('called');
//         textarea.value = editor.getValue();
//     });

// });
form.addEventListener("submit", function () {

    textarea.value = editor.getValue();

    console.log(
        "CSS Submitted:",
        textarea.value
    );

});

document.addEventListener("DOMContentLoaded", function () {

    const textarea = document.getElementById("custom_css");
    const form = document.getElementById("custom_css_form");

    const editor = ace.edit("css_editor");

    editor.setTheme("ace/theme/textmate");

    editor.session.setMode("ace/mode/css");

    editor.setValue(
        textarea.value || "",
        -1
    );


    editor.setOptions({
        fontSize: "14px",
        showPrintMargin: false,
        wrap: true,
        tabSize: 4,
        useSoftTabs: true,
        highlightActiveLine: true,
        enableBasicAutocompletion: true,
        enableLiveAutocompletion: true,
        enableSnippets: true
    });


    const languageTools = ace.require(
        "ace/ext/language_tools"
    );


    editor.completers = [
        languageTools.textCompleter,
        languageTools.keyWordCompleter,
        languageTools.snippetCompleter
    ];


    editor.session.on("change", function () {
        textarea.value = editor.getValue();
    });




});
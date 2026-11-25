document.addEventListener("DOMContentLoaded", function () {

    window.textarea = document.getElementById("custom_js");
    window.editor = ace.edit("js_editor");

    editor.setTheme("ace/theme/textmate");
    editor.session.setMode("ace/mode/javascript");

    editor.setValue(textarea.value || "//write your script", -1);

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

    ace.require("ace/ext/language_tools");
    editor.completers = [
        ace.require("ace/ext/language_tools").textCompleter,
        ace.require("ace/ext/language_tools").keyWordCompleter,
        ace.require("ace/ext/language_tools").snippetCompleter
    ];
});
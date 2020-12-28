import "../css/c9-admin.scss";

const $ = window.jQuery;

$(function () {
    $("body.block-editor-page").addClass("folded");
    $(".c9-color-picker").wpColorPicker();
});

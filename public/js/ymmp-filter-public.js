(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */




})(jQuery);

function filter_input_text($input1_id, $input2_Class) {
    var input, filter, ul, li, i, txtValue, $exist;

    input = document.getElementById($input1_id);

    filter = input.value.toUpperCase();
    ul = document.getElementById($input2_Class);
    li = ul.getElementsByTagName("input");
    for (i = 0; i < li.length; i++) {

        txtValue = li[i].getAttribute("data-name");

        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            li[i].parentElement.classList.remove("hidden");
        } else {
            li[i].parentElement.classList.add("hidden");
        }

    }
    $exist = ul.querySelector("label:not(.hidden)");
    if ($exist == null) {
        input.nextElementSibling.classList.remove("hidden");
    } else {
        input.nextElementSibling.classList.add("hidden");
    }
}

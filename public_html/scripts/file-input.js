/*
    file-input.js

    Copyright Stefan Fisk 2012.
*/

app = typeof app != 'undefined' ? app : {};

(function($) {
    $(function() {
        'use strict';

        $('input[type="file"]').change(function(event) {
            var $input = $(event.delegateTarget);
            var $label = $('[for="' + $input.attr('name') + '"]');

            var filename = $input.val().split(/(\\|\/)/g).pop();

            $label.text(filename);
        });
    });
})($);

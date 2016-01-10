/*
    select.js

    Copyright Stefan Fisk 2012
*/

$(function($) {
    'use strict';

    // $('.select-wrapper .chrome').click(function() {
    //     $(this).siblings('select').click();
    // });

    $('.select-wrapper select')
        .change(function() {
            var val = $('option:selected', this).text();
            $(this).siblings('.text-container').find('.text').text(val);
        })
        .change();
});

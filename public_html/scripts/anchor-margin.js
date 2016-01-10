/*global window */
/*
    anchor-margin.js

    Copyright Stefan Fisk 2012
*/

$(function($) {
    'use strict';

    var margin = 50;

    $('a[href*=#]').click(function(event) {
        if (window.location.pathname.replace(/^\//,'') != this.pathname.replace(/^\//,'') || window.location.hostname != this.hostname) {
            return;
        }

        event.preventDefault();

        scrollToNamedAnchor(this.hash.slice(1));
    });

    var scrollToNamedAnchor = function(name) {
        var $target = $('#' + name + ', [name=' + name +']');
        var scrollTop = $target.offset().top - margin;

        $('body, html').animate({
            scrollTop: scrollTop
        }, 'fast', function() {
            window.location.hash = '#' + name;
            $('body, html').scrollTop(scrollTop);
        });
    };

    $(window).load(function() {
        if (window.location.hash) {
            scrollToNamedAnchor(window.location.hash.slice(1));
        }
    });
});

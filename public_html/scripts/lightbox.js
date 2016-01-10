/*
    lightbox.js

    Copyright Stefan Fisk 2012.
*/

app = typeof app != 'undefined' ? app : {};

$(function() {
    var $lightbox = $('<div class="lightbox"><div class="overlay"></div><div class="box"></div></div>');
    var $overlay = $lightbox.find('.overlay');
    var $box = $lightbox.find('.box');
    var isOpen = false;

    app.lightbox = {};

    app.lightbox.open = function(url) {
        if (isOpen) {
            return;
        }

        var part = 'lightbox=1';

        url = (url.indexOf('?') != -1 ? url.split('?')[0]+'?'+part+'&'+url.split('?')[1] : (url.indexOf('#') != -1 ? url.split('#')[0]+'?'+part+'#'+ url.split('#')[1] : url+'?'+part));

        $box.empty().append('<iframe src="' + url + '"></iframe>');
        $lightbox
        .stop(true)
        .css({
            opacity: 0
        })
        .appendTo('body')
        .fadeTo('fast', 1);

        $('body').css('overflow', 'hidden');

        isOpen = true;
    };
    app.lightbox.close = function() {
        if (!isOpen) {
            return;
        }

        try {
            if ($lightbox.find('iframe')[0].contentWindow.app.user.isGuest !== app.user.isGuest) {
                window.location.reload();
                return;
            }
        }
        catch (exception) {
        }

        $lightbox
        .stop(true)
        .fadeTo('fast', 0, function() {
            $lightbox.detach();
            $box.empty();
        });

        $('body').css('overflow', '');

        isOpen = false;
    };

    $overlay.click(app.lightbox.close);
    $(document).keydown(function(e) {
        var code = e.keyCode ? e.keyCode : e.which;
        if (27 === code) {
            if (window.parent && window.parent.app && window.parent.app.lightbox) {
                window.parent.app.lightbox.close();
            } else {
                app.lightbox.close();
            }
        }
    });

    app.lightbox.back = function() {
        if ($('section:first').is(':not(.inactive)')) {
            return;
        }

        $('section:not(.inactive)')
        .addClass('inactive')
        .prev('section')
        .removeClass('inactive');


        if ($('section:first').is('.inactive')) {
            $('nav .back').show();
            $('nav .next').show();
            $('nav .submit').hide();
        } else {
            $('nav .back').hide();
        }
    };
    app.lightbox.next = function() {
        if ($('section:last').is(':not(.inactive)')) {
            return;
        }

        $('section:not(.inactive)')
        .addClass('inactive')
        .next('section')
        .removeClass('inactive');

        if ($('section:last').is('.inactive')) {
            $('nav .back').show();
            $('nav .submit').hide();
        } else {
            $('nav .next').hide();
            $('nav .submit').show();
        }
    };
});

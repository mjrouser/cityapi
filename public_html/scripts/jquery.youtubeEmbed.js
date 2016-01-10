
(function($) { $(function($) {
    'use strict';

    var youtubeCom = /youtube\.com\/watch.+v=([\w\-]+)&?/;
    var youtuBe = /youtu\.be\/([\w\-]+)&?/;

    $('.youtube-embed').each(function() {
        var $this = $(this);

        var matches = youtubeCom.exec($this.text()) || youtuBe.exec($this.text());

        if (!matches) {
            return true;
        }

        var $iframe = $('<iframe class="youtube-embed" width="1280" height="720" frameborder="0" allowfullscreen />')
            .attr('src', 'http://www.youtube.com/embed/' + matches[1]);

        $iframe.insertAfter($this);
    });
});})($);

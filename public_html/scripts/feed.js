/*
    feed.js

    Copyright Stefan Fisk 2012.
*/

app = typeof app != 'undefined' ? app : {};

$(function() {
    app.feed = {};

    app.feed.follow = function(feedId) {
        var that = this;

        $.ajax({
            url: '/feed/follow?id=' + encodeURIComponent(feedId),
            success: function(data) {
                console.log(arguments);
                $(that).toggle(!data).siblings('.unfollow').toggle(data);
            },
            dataType: 'json'
        });
    };
    app.feed.unfollow = function(feedId) {
        var that = this;

        $.ajax({
            url: '/feed/unfollow?id=' + encodeURIComponent(feedId),
            success: function(data) {
                $(that).toggle(!data).siblings('.follow').toggle(data);
            },
            dataType: 'json'
        });
    };
});

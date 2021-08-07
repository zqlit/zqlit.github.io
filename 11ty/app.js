$(function() {
    init();
});
function init() {
    $("article img:not(.noImageLightBox)").each(function(i) {
        if (!this.parentNode.href) {
            $(this).wrap('<a class="post-imgLink" href="' + this.src + '" data-caption="' + this.alt + '"></a>');
        }
    });
    $("article .photosets").each(function() {
        var count = $(this).find("a.post-imgLink").length;
        switch (count) {
        case 1:
            $(this).addClass("photosets-1");
            break;
        case 2:
            $(this).addClass("photosets-2");
            break;
        case 4:
            $(this).addClass("photosets-4");
            break;
        default:
            $(this).addClass("photosets-3");
        }
    });
    if ($(".post-imgLink").length > 0) {
        lightGallery(document.getElementsByTagName('article')[0], {
            selector: '.post-imgLink',
            share: false,
            showThumbByDefault: false,
            autoplayControls: false
        });
    }
}

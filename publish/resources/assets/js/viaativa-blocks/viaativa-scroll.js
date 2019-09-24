function gup( name, url ) {
    if (!url) url = location.href;
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( url );
    return results == null ? null : results[1];
}


$('.scroller-item').each(function() {

    $(this).click(function() {
        //console.log("."+$(this).data('scroll-to'));

        if($("#"+$(this).data('scroll-to')).length)
        {
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#"+$(this).data('scroll-to')).offset().top - $(window).height()/2,
            }, 1000);
        } else
        {
            $(this).attr('href',$(this).data('go-to')+'?scroll='+$(this).data('scroll-to'));
        }


    });
})

$( document ).ready(function() {
    var target = gup('scroll', window.location.href);
    if (target != null) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#" + target).offset().top - $(window).height() / 2,
        }, 1000);
    }
})
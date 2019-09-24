$('.style-me').each(function() {
    var $this = $(this)
    var css = $this.data('css')
    var resultCss = "";
    for(var i in css)
    {
        resultCss += i+"{"
        for(var j in css[i])
        {
            resultCss+= j+":"+css[i][j]+";";
        }
        resultCss += "}"
    }
    $('head').append('<style>'+resultCss+'</style>')
});
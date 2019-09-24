/**
 * Uso:
 *
 * Para o elemento que você vai precisar de um hover, adiciona a classe "hover-me"
 * Para os elementos que vai trocar alguma classe, é passado um json para o data-hover.
 * Para os elementos que vai ficar invisível antes do hover, é passado o seletor em um array no data-hover-invisible
 * Para os elementos que irá ficar invisível depois do hover, é passado o seletor em um array no data-hover-visible
 *
 * OBS:
 *  - todos os jsons precisam ser passados em ' não em ".
 *  - data-hover podem ser utilizados nos filhos da classe hover-me
 *
 * Example: *
 * <div  class="block hover-me"
 data-hover-in='{"background":"#12005e","border-radius":"0"}'
 data-hover-out='{"background":"#ffffff","border-radius":"10px"}'
 data-hover-visible='[".img-hover-in"]'
 data-hover-invisible='[".img-hover-out", ".teste"]'
 >
 *
 */


$.fn.viaativaHoverTarget = function () {
    var _self$ = $(this);

    function init() {
        hoverEvent();
        hoverHide('hover-invisible');
    }

    function hoverEvent() {
        _self$.hover(
            function () {
                var $main = $(this);
                updateCss(this,$main.data('target'));
                //hoverShow('hover-invisible');
                //hoverHide('hover-visible');
            },
            function () {
                var $main = $(this);
                updateCss(this,$main.data('target'), 'hover-out');
                //hoverShow('hover-visible');
                //hoverHide('hover-invisible');
            }
        )
    }

    function updateCss(element,target, dataType = "hover-in") {

        var _childrens$ = $(element).find(`[data-${dataType}]`),
            _parentHoverData = $(element).data(dataType);

        if(typeof _parentHoverData !== "undefined")
            $(target).css(_parentHoverData);
            $('#main-menu').attr('data-hovering',target);
        if($(target).hasClass('hovering'))
        {
            $(target).removeClass('hovering');
        } else if(dataType == "hover-in") {
            $(target).addClass("hovering");
        }

        _childrens$.each(function (i, el) {
            var el$ = $(el),
                _hoverData = el$.data(dataType);

            if(typeof _hoverData !== "undefined")
                $(el$).css(_hoverData);
        });
    }

    function hoverShow(dataSelector) {
        var hideEls = _self$.data(dataSelector);
        $(hideEls).each(function (i, selector) {
            $(selector).css('opacity', '').css('visibility', '').css('display', '')
        })
    }

    function hoverHide(dataSelector) {
        var hideEls = _self$.data(dataSelector);
        $(hideEls).each(function (i, selector) {
            $(selector).css('opacity', 0).css('visibility', 'hidden').css('display', 'none')
        });
    }

    init();

};

$('.hover-target').viaativaHoverTarget();


$(function(){
    if($(this).scrollTop() >= 90){
        $('.mega-menu-display').addClass('scrolled');
    }else{
        $('.mega-menu-display').removeClass('scrolled');
    }
    $(window).scroll(function(){
        if($(this).scrollTop() >= 90){
            $('.mega-menu-display').addClass('scrolled');
        }else{
            $('.mega-menu-display').removeClass('scrolled');
        }
    });
});


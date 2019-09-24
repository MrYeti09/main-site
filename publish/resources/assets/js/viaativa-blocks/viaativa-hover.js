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


$.fn.viaativaHoverMe = function () {
    var _self$ = $(this);

    function init() {
        hoverEvent();
        hoverHide('hover-invisible');
    }

    function hoverEvent() {
        _self$.hover(
            function () {
                updateCss(this);
                hoverShow('hover-invisible');
                hoverHide('hover-visible');
            },
            function () {
                updateCss(this, 'hover-out');
                hoverShow('hover-visible');
                hoverHide('hover-invisible');
            }
        )
    }

    function updateCss(element, dataType = "hover-in") {
        var _childrens$ = $(element).find(`[data-${dataType}]`),
            _parentHoverData = $(element).data(dataType);

        if(typeof _parentHoverData !== "undefined")
            $(element).css(_parentHoverData);

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
            _self$.find(selector).css('opacity', '').css('visibility', '').css('display', '')
        })
    }

    function hoverHide(dataSelector) {
        var hideEls = _self$.data(dataSelector);
        $(hideEls).each(function (i, selector) {
            _self$.find(selector).css('opacity', 0).css('visibility', 'hidden').css('display', 'none')
        });
    }

    init();

};

$('.hover-me').each(function() {
    $(this).viaativaHoverMe();
})

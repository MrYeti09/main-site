$(function () {
    $('body').on('click', '.btn-select-icon', function () {
        var iconSelector$ = $(`.icons-wrapper-${$(this).data('form-name')}`).find('.icon-selector');
        if(!iconSelector$.hasClass('hidden')){
            iconSelector$.addClass('hidden');
        }else{
            $(`.icons-wrapper`).find('.icon-selector').addClass('hidden');
            iconSelector$.removeClass('hidden');
        }
    });

    $('body').on('change', '.filter-icons', function () {
        var iconsWrapper$ = $(`.icons-wrapper-${$(this).data('form-name')}`);
        iconsWrapper$.find('.icon-wrapper').show();
        var classVal = $(this).val();
        if (classVal.length) {
            iconsWrapper$.find(`.icon-wrapper:not(.${classVal})`).hide();
        }
    });

    $('body').on('click', '.icon-wrapper', function () {
        var classVal = $(this).data('form-value');
        var iconsWrapper$ = $(`.icons-wrapper-${$(this).data('form-name')}`);
        var iconPreview$ = iconsWrapper$.find('.icon-preview');
        $(`input[name="${$(this).data('form-name')}"]`).val(classVal);
        iconPreview$.attr('class', '').addClass("icon-preview").addClass(classVal);
        iconsWrapper$.find('.icon-selector').addClass('hidden');
    });
});

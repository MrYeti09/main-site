$(function(){
    var header4 = $('.header-4');
    $('body').css('background-image',"url('"+header4.data('body')+"')")
    if($(this).scrollTop() >= 60){
        header4.addClass('scrolled');
        header4.css('background-color',header4.data('color-scroll'))
    }else{
        header4.removeClass('scrolled');
        header4.css('background-color',header4.data('color-normal'))
    }
    $(window).scroll(function(){

        if($(this).scrollTop() >= 60){
            header4.addClass('scrolled');

            header4.css('background-color',header4.data('color-scroll'))
        }else{
            header4.removeClass('scrolled');
            header4.css('background-color',header4.data('color-normal'))
        }
    });
});
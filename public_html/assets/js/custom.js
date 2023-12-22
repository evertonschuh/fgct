

jQuery(document).ready(function(){

    var url = window.location;
    

    var element = $('.menu-inner .menu-item a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).parent();


    if (element.is('li')) {
        console.log();
        element.addClass('active');
        if(element.parent('.menu-sub').parent('.menu-item').length > 0){
            element.parent('.menu-sub').parent('.menu-item').addClass('active').addClass('open');
        }
    }

    


});
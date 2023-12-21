

jQuery(document).ready(function(){

    var url = window.location;
    

    var element = $('.menu-inner .menu-item a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).parent();


    if (element.is('li')) {
        element.addClass('active');
    }


});
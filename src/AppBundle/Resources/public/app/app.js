$(document).ready(function(){
    $('#nav').affix({
          offset: {
            top: 0
          }
    });	

    $('#sidebar').affix({
          offset: {
            top: 20
          }
    });
    
    $("#main-sidebar-toggle").click(function(){
        if ($('#main-sidebar .full-block').hasClass('sm')) {
            $('#main-body').removeClass('main-body-lg main-body-default').addClass('main-body-sm');
            $('#main-sidebar .full-block').removeClass('sm default').addClass('lg', {duration: 100});
            $('#main-sidebar-toggle').removeClass('sidebar-toggle-open').addClass('sidebar-toggle-close');
        } else {
            $('#main-body').removeClass('main-body-sm main-body-default').addClass('main-body-lg');
            $('#main-sidebar .full-block').removeClass('lg default').addClass('sm', {duration: 50});
            $('#main-sidebar-toggle').removeClass('sidebar-toggle-close').addClass('sidebar-toggle-open');
        }
    });  
});
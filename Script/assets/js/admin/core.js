(function ($) {
    'use strict';
    var wn = $(window);
    /*============================================
     PAGE PRE LOADER
	 ==============================================*/

    wn.on('load', function () {
        $('#page-loader').fadeOut(500);
    });

    /*============================================
     APP MENU
	 ==============================================*/

    var treeviewMenu = $('.app-menu');
    var sideBar=$('[data-toggle="sidebar"]');
    var app=$('.app');
    // Toggle Sidebar
    sideBar.on('click',function (event) {
        event.preventDefault();
        app.toggleClass('sidenav-toggled');
    });

    // Activate sidebar treeview toggle
    $("[data-toggle='treeview']").on('click',function (event) {
        event.preventDefault();
        if (!$(this).parent().hasClass('is-expanded')) {
            treeviewMenu.find("[data-toggle='treeview']").parent().removeClass('is-expanded');
        }
        $(this).parent().toggleClass('is-expanded');
    });

    // Set initial active toggle
    $("[data-toggle='treeview.'].is-expanded").parent().toggleClass('is-expanded');
})(jQuery);
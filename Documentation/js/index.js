(function ($) {
  'use strict';
  var nav = $('#nav');
  nav.affix({
    offset: {
      top: $('#nav').offset().top,
      bottom: ($('footer').outerHeight(true) + $('.application').outerHeight(true)) + 40
    }
  });

  var navlink = $('#nav a')
  navlink.on('click',function(e){
    e.preventDefault();
    var aid = $(this).attr("href");
    $('html,body').animate({scrollTop: $(aid).offset().top},'slow');
  });
  

  SyntaxHighlighter.defaults['toolbar'] = false;
    SyntaxHighlighter.all();
})(jQuery);
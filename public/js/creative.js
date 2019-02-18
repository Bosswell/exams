(function($) {
  "use strict"; // Start of use strict

  // Smooth scrolling using jQuery easing
  $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      if (target.length) {
        $('html, body').animate({
          scrollTop: (target.offset().top)
        }, 1000, "easeInOutExpo");
        return false;
      }
    }
  });

  // Closes responsive menu when a scroll trigger link is clicked
  $('.js-scroll-trigger').click(function() {
    $('.navbar-collapse').collapse('hide');
  });

  // Activate scrollspy to add active class to navbar items on scroll
  $('body').scrollspy({
    target: '#mainNav',
    offset: 57
  });

  // Collapse Navbar
  var navbarCollapse = function() {
    if ($("#mainNav").offset().top > 100) {
      $("#mainNav").addClass("navbar-shrink");
      $(".logo-full").css("display","none");
      $(".logo-mark").css("display","block");
    } else {
      $("#mainNav").removeClass("navbar-shrink");
      $(".logo-full").css("display","block");
      $(".logo-mark").css("display","none");
    }
  };
  // Collapse now if page is not at top
  navbarCollapse();
  // Collapse the navbar when page is scrolled
  $(window).scroll(navbarCollapse);

  // Scroll reveal calls
  window.sr = ScrollReveal();
  sr.reveal('.sr-icons', {
    duration: 600,
    scale: 0.3,
    distance: '0px'
  }, 200);
  sr.reveal('.sr-button', {
    duration: 1000,
    delay: 200
  });
  sr.reveal('.sr-contact', {
    duration: 600,
    scale: 0.3,
    distance: '0px'
  }, 300);


  let $footerList = $('#footer .mobile-clickable');

  $footerList.on('click', function () {
      let $item = $(this);
      let $icon = $item.find('.mobile-icon');
      let $element = $item.parent().find('.expand-footer');

      if ($item.attr('data-expanded') === 'true') {
          // Hide
          $icon.text('+');
          $element.hide();
          $item.attr('data-expanded', 'false');
      } else {
          // Show
          $icon.text('×');
          $element.show();
          $item.attr('data-expanded', 'true');
      }
      
  });

})(jQuery); // End of use strict


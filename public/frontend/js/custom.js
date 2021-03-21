( function( $ ) {

  "use strict";

  $(document).ready(function($){

    // Search in header.
    if( $('.search-icon').length > 0 ) {
      $('.search-icon').on('click', function(e){
        e.preventDefault();
        $('.search-box-wrap').slideToggle();
      });
    }

    // Trigger mobile menu.
    $('#mobile-trigger').sidr({
      timing: 'ease-in-out',
      speed: 500,
      source: '#mob-menu',
      renaming: false,
      name: 'mob-menu'
    });

    $('#mob-menu').find( '.sub-menu,.flat-mega-memu' ).before( '<span class="dropdown-toggle"><strong class="dropdown-icon"></strong>' );

    $('#mob-menu').find( '.dropdown-toggle').on('click',function(e){
      e.preventDefault();
      $(this).next('.sub-menu,.flat-mega-memu').slideToggle();
      $(this).toggleClass( 'toggle-on' );
    });

    // Fixed header.
    $(window).on('scroll', function() {
      if( $(window).scrollTop() > $('body').offset().top && !($('body').hasClass('sticky-header'))){
        $('body').addClass('sticky-header');
      }

      else if ( 0 === $(window).scrollTop() ) {
        $('body').removeClass('sticky-header');
      }
    });

    // Slick carousel column 3.
    $(".iteam-col-3.section-carousel-enabled").slick({
      dots: true,
      infinite: true,
      slidesToShow: 3,
      slidesToScroll: 3,
      dots: false,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ],
      prevArrow: '<span data-role="none" class="slick-prev" tabindex="0"><i class="fa fa-angle-left" aria-hidden="true"></i></span>',
      nextArrow : '<span data-role="none" class="slick-next" tabindex="0"><i class="fa fa-angle-right" aria-hidden="true"></i></span>'
    });

    // Slick carousel column 4
    $(".iteam-col-4.section-carousel-enabled").slick({
      dots: true,
      infinite: true,
      slidesToShow: 4,
      slidesToScroll: 4,
      dots: false,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
            infinite: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ],
      prevArrow: '<span data-role="none" class="slick-prev" tabindex="0"><i class="fa fa-angle-left" aria-hidden="true"></i></span>',
      nextArrow : '<span data-role="none" class="slick-next" tabindex="0"><i class="fa fa-angle-right" aria-hidden="true"></i></span>'
    });

    // Slick carousel column 7
    $(".iteam-col-7.section-carousel-enabled").slick({
      dots: true,
      infinite: true,
      slidesToShow: 7,
      slidesToScroll: 7,
      dots: false,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 4,
            infinite: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        }
      ],
      prevArrow: '<span data-role="none" class="slick-prev" tabindex="0"><i class="fa fa-angle-left" aria-hidden="true"></i></span>',
      nextArrow : '<span data-role="none" class="slick-next" tabindex="0"><i class="fa fa-angle-right" aria-hidden="true"></i></span>'
    });
    // Implement go to top.
    var $scroll_obj = $( '#btn-scrollup' );
    $( window ).on('scroll',function(){
      if ( $( this ).scrollTop() > 100 ) {
        $scroll_obj.fadeIn();
      } else {
        $scroll_obj.fadeOut();
      }
    });

    $scroll_obj.on('click', function(){
      $( 'html, body' ).animate( { scrollTop: 0 }, 600 );
      return false;
    });

  });

} )( jQuery );


'use strict';

var $ = jQuery.noConflict();

var HB = {
  init: function(){
    this.nav.init();
    this.ui.init();
  },

  nav: {
    init: function() {
      this._header();
    },

    _header: function() {
      $('.js-search-trigger').click(function() {
        var $form = $('#searchform');
        $form.fadeToggle();

        if($form.is(':visible')) {
          $form.find('input').focus();
        }
      });
    }

  },

  ui: {
    init: function() {
      this._slideUp();
      this._slider();
    },

    _slideUp: function() {
      $('.js-slide-title').hover(function() {
        $(this).toggleClass('active');
      });
    },

    _slider: function() {
      $('.bx-slider').bxSlider({
        minSlides: 8,
        maxSlides: 10,
        slideWidth: 150,
        slideMargin: 10,
        pager: false,
        nextText: '',
        prevText: ''
      });
    }

  }

};

$(window).load(function() {
  HB.init();
});

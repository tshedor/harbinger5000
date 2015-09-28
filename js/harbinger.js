'use strict';

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
      jQuery('.js-search-trigger').click(function() {
        var jQueryform = jQuery('#searchform');
        jQueryform.fadeToggle();

        if(jQueryform.is(':visible')) {
          jQueryform.find('input').focus();
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
      jQuery('.js-slide-title').hover(function() {
        jQuery(this).toggleClass('active');
      });
    },

    _slider: function() {
      jQuery('.bx-slider').bxSlider({
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

jQuery(window).load(function() {
  HB.init();
});

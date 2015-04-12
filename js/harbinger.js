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
    },

    _slideUp: function() {
      $('.js-slide-title').hover(function() {
        $(this).toggleClass('active');
      });
    }

  }

};

$(window).load(function() {
  HB.init();
});

'use strict';

var $ = jQuery.noConflict();

var HB = {
  init: function(){
    this.nav.init();
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

  }

};

$(window).load(function() {
  HB.init();
});

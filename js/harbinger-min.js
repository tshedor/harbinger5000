"use strict";var $=jQuery.noConflict(),HB={init:function(){this.nav.init()},nav:{init:function(){this._header()},_header:function(){$(".js-search-trigger").click(function(){var i=$("#searchform");i.fadeToggle(),i.is(":visible")&&i.find("input").focus()})}}};$(window).load(function(){HB.init()});
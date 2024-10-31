// ==================================================================
// OHV plugin
// Author: Dmitry Tsurcan
// Version: 1.0.0
// ==================================================================

jQuery(function($) {
  var OpenHouseVideoLoader = function() {

    var _prefix = 'ohv-';
    var debug = false;

    var _log = function(message) {
      if (debug) {
        if (typeof message == 'string') {
          console.log('OHV LOG: ' + message);
        } else {
          console.log('OHV LOG:');
          console.dir(message);
        }
      }
    }

    // Loading element
    $.fn.loading = function(options) {
      var settings = $.extend({
        message: WPOpenHouseVideo.strings['Loading...'],
        action: 'show',
        wrapperClass: _prefix + 'loading',
        wrappePreHiderClass: _prefix + 'loading-pre-hide',
        innerClass: _prefix + 'loading-inner'
      }, options);

      return this.each(function() {
        var $el = $(this);

        if (settings.action == 'show') {
          $el.addClass(settings.wrapperClass);
          if (!$el.find('.' + settings.innerClass).length) {
            $el.append($('<div class="' + settings.innerClass + '" />').html($('<span />')));
            $el.find('.' + settings.innerClass + ' > span').append($('<img src="' + WPOpenHouseVideo.loadingUrl + '" alt="' + WPOpenHouseVideo.strings['Loading...'] + '" />'));
            $el.find('.' + settings.innerClass + ' > span').append(settings.message);
          }
        } else {
          $el.find('.' + settings.innerClass).addClass(settings.wrappePreHiderClass);
          setTimeout(function() {
            $el.find('.' + settings.innerClass).remove();
            $el.removeClass(settings.wrapperClass);
          }, 300);
        }
      });
    }
    // Unloading element
    $.fn.unloading = function() {
      return this.each(function() {
        var $el = $(this);
        $el.loading({action: 'hide'});
      });
    }

    var handleEvents = function() {
      // Check message frem iframe
      if (window.addEventListener) {
        window.addEventListener("message", function(e) {
          setIframeHeight(e.source, e.data);
        }, false);
      } else {
        window.attachEvent("onmessage", function(e) {
          setIframeHeight(e.source, e.data);
        });
      }

      $('.' + _prefix + 'iframe').each(function(){
        $(this).loading();
      });
    }

    // Set dynamic iframe's height
    var setIframeHeight = function(source, height) {
      $('.' + _prefix + 'iframe').each(function(){
        var $this = $(this);
        var $iframe = $this.find('> iframe');
        if ($iframe.get(0).contentWindow === source) {
          $iframe.css({height: height});
          $this.unloading();
        }
      });
    }

    return {
      init: function() {
        _log('Init JS OHV Loader v' + WPOpenHouseVideo.version);

        handleEvents();
      }
    }
  }();

  OpenHouseVideoLoader.init();
});

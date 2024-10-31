// ==================================================================
// OHV plugin
// Author: Dmitry Tsurcan
// Version: 1.0.0
// ==================================================================

jQuery(function($) {
  var OpenHouseVideo = function() {

    var _prefix = 'ohv-';
    var $userBlock = $('#' + _prefix + 'user-block');
    var $popupBlock = $('#' + _prefix + 'popup');
    var $listingBlock = $('#' + _prefix + 'listing-block');
    var $previewBlock = $('#' + _prefix + 'listing-preview');
    var $version = $('select[name="' + _prefix + 'listing-version"]');
    var $versionAlert = $('#' + _prefix + 'version-message');
    var $notifyAlert = $('#' + _prefix + 'notify-message');

    var isAjax = false;
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

    var delay = (function() {
      var timer = 0;
      return function(callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
      };
    })();

    // Alert element
    $.fn.alert = function(options) {
      var settings = $.extend({
        status: 'default',
        action: 'show',
        message: '',
        hideAfter: 0
      }, options);

      var statuses = ['default', 'danger', 'success', 'warning'];

      return this.each(function() {
        var $el = $(this);

        if (settings.message != '') {
          $el.find('span').html(settings.message);
        }

        // Set status
        $(statuses).each(function(i, status) {
          $el.removeClass(_prefix + 'alert-' + status);
        });
        $el.addClass(_prefix + 'alert-' + settings.status);

        // Show/Hide
        if (settings.action == 'hide') {
          $el.addClass(_prefix + 'display-none');
        } else {
          $el.removeClass(_prefix + 'display-none');
        }

        if (settings.hideAfter > 0) {
          setTimeout(function() {
            $el.addClass(_prefix + 'display-none');
          }, settings.hideAfter);
        }
      });
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

    // Group buttons
    $.fn.buttons = function(options) {
      var settings = $.extend({
        activeClass: _prefix + 'active',
        buttonClass: _prefix + 'btn',
        changed: function() {}
      }, options);

      return this.each(function() {
        var $el = $(this);

        $el.find('.' + settings.buttonClass).each(function() {
          var $button = $(this);
          $button.off('click').on('click', function(e) {
            e.preventDefault();

            $el.find('.' + settings.activeClass).removeClass(settings.activeClass);
            $el.find('input').prop('checked', false);
            $button.addClass(settings.activeClass);
            $button.find('input').prop('checked', true);

            if ($.isFunction(settings.changed)) {
              settings.changed.call(this);
            }
          });
        });
      });
    }

    // Search listings
    $.fn.search = function(options) {
      var settings = $.extend({
        done: function() {}
      }, options);

      return this.each(function() {
        var $input = $(this);

        $input.on('keyup', function() {
          delay(function() {
            if ($.isFunction(settings.done)) {
              settings.done.call(this);
            }
          }, 300);
        });
      });
    }

    var handleEvents = function() {
      $(document).on('click', '[data-toggle="' + _prefix + 'logout"]', function(e) {
        e.preventDefault();

        _log('Logout');

        updateToken(null, function() {
          location.reload();
        });
      });

      $('[data-toggle="' + _prefix + 'buttons"]').buttons();

      // Check message frem iframe
      if (window.addEventListener) {
        window.addEventListener("message", function(e) {
          listing.setIframeHeight(e.source, e.data);
        }, false);
      } else {
        window.attachEvent("onmessage", function(e) {
          listing.setIframeHeight(e.source, e.data);
        });
      }
    }

    // Validate form
    var validate = function(options) {
      var $form = $(options.id);
      if (!$form.length)
        return;

      var requiredFields = options.required;
      var isValid = true;
      var messages = [];
      var data = [];
      var size = 0
      $(requiredFields).each(function(i, requiredField) {
        var val = $form.find('[name="' + _prefix + requiredField + '"]').val();
        if (!!!val && (val.trim().length == 0)) {
          isValid = false;
          messages[messages.length] = requiredField + ' is required';
        } else {
          data[requiredField] = val;
        }
        size++;
      });

      if (size !== requiredFields.length) {
        isValid = false;
        messages[messages.length] = 'A problem with size of fields';
      }

      messages = messages.join("<br />");

      return {status: isValid, messages: messages, data: data};
    }

    // Update Token in WP DB
    var updateToken = function(token, callback) {
      callback = callback || function() {};

      $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
          action: 'update_token',
          _wpnonce: WPOpenHouseVideo.update_token,
          token: token
        },
        complete: function() {
          callback();
        }
      });
    }

    // Get API's info
    var info = function() {
      // Send Ajax request
      var sendAjax = function() {
        $.ajax({
          url: ajaxurl,
          type: 'GET',
          dataType: 'json',
          data: {
            action: 'get_info',
            _wpnonce: WPOpenHouseVideo.get_info
          },
          success: function(response) {
            if (response) {
              if (response.status && response.status == 'success') {
                // if (response.data.version !== WPOpenHouseVideo.version) {
                //   $versionAlert.alert({
                //     status: 'danger',
                //     message: WPOpenHouseVideo.strings['The plugin you are using is outdated. You can download the new one '] + $('<div />').html($('<a href="' + WPOpenHouseVideo.pluginUrl + '">').text(WPOpenHouseVideo.strings['here'])).html() + '.'
                //   });
                // }
                if (response.data.show_notify == 'true') {
                  $notifyAlert.alert({status: 'warning', message: response.data.notify_message});
                }
              }
            }
          }
        });
      };

      return {
        init: function() {
          sendAjax();
        }
      }
    }();

    // Login FORM
    var login = function() {
      var options = {
        id: '#' + _prefix + 'form-login',
        required: ['email', 'password']
      };

      var $form = null;

      // Send Ajax request
      var sendAjax = function(data) {
        if (isAjax)
          return;

        var params = {};
        params = $.extend({}, params, data);

        var $submit = $form.find('button[type="submit"]');
        var submit_default = $submit.text();

        $.ajax({
          url: WPOpenHouseVideo.loginApiUrl,
          type: 'POST',
          data: params,
          beforeSend: function() {
            isAjax = true;

            $submit.prop('disabled', true);
            $submit.text(WPOpenHouseVideo.strings['Processing...']);
          },
          error: function(response) {
            if (response) {
              var responseJSON = response.responseJSON;

              $form.find('.' + _prefix + 'alert').alert({status: 'danger', message: responseJSON.error, hideAfter: 5000});
            }
          },
          success: function(response) {
            if (response) {
              if (response.status && response.status == 'failed') {
                $form.find('.' + _prefix + 'alert').alert({status: 'danger', message: response.message, hideAfter: 5000});
              }

              if (response.token) {
                var token = response.token;
                updateToken(token, function() {
                  location.reload();
                });

                $form.find('.' + _prefix + 'alert').alert({status: 'success', message: 'Logged successfully', hideAfter: 5000});
              }
            }
          },
          complete: function() {
            isAjax = false;

            $submit.text(submit_default);
            $submit.prop('disabled', false);
          }
        });
      }

      // Handle form Events
      var handleEvents = function() {
        // Submit form
        var $submit = $form.find('button[type="submit"]');
        $submit.on('click', function(e) {
          e.preventDefault();

          _log('Login');

          var v = validate(options);
          if (!v.status) {
            $form.find('.' + _prefix + 'alert').alert({status: 'danger', message: v.messages, hideAfter: 3000});

            return;
          }

          $form.find('.' + _prefix + 'alert').alert({action: 'hide'});

          sendAjax(v.data);
        });
      }

      return {
        init: function() {
          $form = $(options.id);
          if (!$form.length)
            return;

          _log('Init Login form');

          handleEvents();
        }
      }
    }();

    // User
    var user = function() {
      // Ajax get user data
      var getData = function(callback) {
        callback = callback || function() {};

        if (isAjax)
          return;

        $.ajax({
          url: ajaxurl,
          type: 'GET',
          dataType: 'json',
          data: {
            action: 'get_user',
            _wpnonce: WPOpenHouseVideo.get_user
          },
          beforeSend: function() {
            isAjax = true;
          },
          error: function(response) {
            user.logout();
          },
          success: function(response) {
            if (response) {
              if (response.status && response.status == 'success') {
                callback(response.data);
              } else
              if (response.status && response.status == 'failed') {
                $userBlock.find('.' + _prefix + 'alert').alert({status: 'danger', message: response.message});

                setTimeout(function() {
                  $userBlock.unloading();
                }, 10);
              } else {
                user.logout();
              }
            }
          },
          complete: function() {
            isAjax = false;
          }
        });
      }

      // Show User data
      var showUserData = function() {
        $userBlock.loading();
        getData(function(fields) {
          var usertTplClass = _prefix + 'user-tpl';
          var usertContentClass = _prefix + 'user-inner-block';
          var userContentTpl = $userBlock.find('.' + usertTplClass).html();

          var with_pic = false;
          for (var key in fields) {
            userContentTpl = userContentTpl.replace(new RegExp("#{" + key + "}", "g"), fields[key]);
            if (key == 'pic' && fields[key]) {
              with_pic = true;
            }
          }

          $userBlock.find('.' + usertContentClass).html(userContentTpl);
          $userBlock.find('.' + _prefix + 'user-' + (
            with_pic
            ? 'pic'
            : 'icon')).show();
          setTimeout(function() {
            $userBlock.unloading();
          }, 10);
        });
      }

      var logout = function()
      {
        updateToken(null, function() {
          location.reload();
        });
      }

      return {
        logout: logout,
        init: function() {
          if (!$userBlock.length)
            return;
          if (!WPOpenHouseVideo.token)
            return;

          showUserData();

          _log('Init User');
        }
      }
    }();

    // Listing
    var listing = function() {
      var listingsData = null;
      var ltClass = _prefix + 'lt-item';
      var toggleLtStatus = '[data-toggle="' + _prefix + 'listing-status"]';
      var toggleLtSelect = '[data-toggle="' + _prefix + 'listing-select"]';
      var toggleLtSearch = '[data-toggle="' + _prefix + 'listing-search"]';
      var toggleLtOrder = '[data-toggle="' + _prefix + 'listing-order"]';

      var handleEvents = function() {
        // Click for selecting
        $listingBlock.off('click', toggleLtSelect).on('click', toggleLtSelect, function(e) {
          e.preventDefault();

          var $el = $(this).closest('.' + _prefix + 'lt-item');

          _log('Selected - ' + $el.attr('data-id'));

          $listingBlock.find('.' + _prefix + 'selected').removeClass(_prefix + 'selected');
          $el.addClass(_prefix + 'selected');

          popup.enableActions();
        });

        // Init statuse's filter
        $(toggleLtStatus).buttons({changed: setFilters});

        $(toggleLtSearch).search({done: setFilters});

        $(toggleLtOrder).off('change').on('change', function() {
          setFilters();
        });
      };

      // Ajax get listings data
      var getData = function(callback) {
        callback = callback || function() {};

        if (isAjax)
          return;

        $.ajax({
          url: ajaxurl,
          type: 'GET',
          dataType: 'json',
          data: {
            action: 'get_listings',
            _wpnonce: WPOpenHouseVideo.get_listings
          },
          beforeSend: function() {
            isAjax = true;
          },
          error: function(response) {
            user.logout();
          },
          success: function(response) {
            if (response) {
              if (response.status && response.status == 'success') {
                listingsData = response.data;

                callback(response.data);
              } else
              if (response.status && response.status == 'failed') {
                $popupBlock.find('.' + _prefix + 'alert').alert({status: 'danger', message: response.message});
                $popupBlock.find('.' + _prefix + 'logout-block').show();

                setTimeout(function() {
                  $listingBlock.unloading();
                }, 10);
              } else {
                user.logout();
              }
            }
          },
          complete: function() {
            isAjax = false;
          }
        });
      };

      // Show listing data
      var showListingData = function() {
        $listingBlock.loading();
        getData(function(listings) {
          var ltTplClass = _prefix + 'lt-item-tpl';
          var $empty = $('<div class="' + _prefix + 'listing-empty" />');
          $empty.append($('<p class="' + _prefix + 'listing-empty-p" />').text(WPOpenHouseVideo.strings['Empty']));
          $listingBlock.append($empty);

          var isEmpty = true;
          for (var key in listings) {
            isEmpty = false;
            var listing = listings[key];

            var ltItemContentTpl = $('.' + ltTplClass).html();
            for (var _key in listing) {
              var value = listing[_key];
              if (_key == 'city')
                value += ', ';

              ltItemContentTpl = ltItemContentTpl.replace(new RegExp("#{" + _key + "}", "g"), value);
            }

            $listingBlock.append(ltItemContentTpl);
          }

          if (isEmpty)
            $listingBlock.addClass(_prefix + 'is-empty');

          setFilters();

          $listingBlock.unloading();
        });
      };

      // Get Selected listing
      var getSelected = function() {
        var $lt = $listingBlock.find('.' + _prefix + 'selected');

        if (!$lt.length)
          return false;

        var ltSelected = listingsData.filter(function(listingData) {
          return (listingData.id == $lt.attr('data-id'));
        });
        ltSelected = ltSelected[0];

        return ltSelected;
      };

      // Parse listing's data to shortcode
      var parse = function() {
        var lt = getSelected();
        if (!lt)
          return;

        var result = new String('');

        var address = lt.street + ' ' + lt.city + ' ' + lt.state + ' ' + lt.postal;
        var url = $version.val() == 'standard'
          ? lt.urls.shorten.url
          : lt.urls.shorten.mc_url;

        result += '[';
        result += 'ohv_listing';
        result += ' src="' + url + '"';
        result += ']';

        return result;
      };

      // Show Live Preview
      var livePreview = function() {
        $popupBlock.addClass(_prefix + 'show-preview');

        var lt = getSelected();
        if (!lt)
          return;

        var url = $version.val() == 'standard'
          ? lt.urls.original.url
          : lt.urls.original.mc_url;

        $previewBlock.find('.' + _prefix + 'listing-preview-content').html($('<span class="' + _prefix + 'iframe"><iframe width="598px" height="100px" src="' + url + '" frameborder="0" allowfullscreen></iframe></span>'));
        $previewBlock.find('.' + _prefix + 'iframe').each(function() {
          var $this = $(this);
          $this.loading();

          // var $iframe = $this.find('> iframe');
          // $iframe.load(function() {
          //   setTimeout(function() {
          //      Unloading if something happened
          //     $this.unloading();
          //   }, 1000);
          // });
        });
      };

      // Set dynamic iframe's height
      var setIframeHeight = function(source, height) {
        $previewBlock.find('.' + _prefix + 'iframe').each(function() {
          var $this = $(this);
          var $iframe = $this.find('> iframe');
          if ($iframe.get(0).contentWindow === source) {
            $iframe.css({height: height});
            $this.unloading();
          }
        });
      }

      // Check if need to show empty message
      var checkEmpty = function(status) {
        if (!$listingBlock.find('.' + ltClass + '-' + status + ':visible').length || !$listingBlock.find('.' + ltClass).length) {
          $listingBlock.addClass(_prefix + 'is-empty');
        } else {
          $listingBlock.removeClass(_prefix + 'is-empty');
        }
      }

      // Set listing's status filter
      var setStatusFilter = function(status) {
        var statuses = WPOpenHouseVideo.listingStatuses;
        for (var i in statuses) {
          $listingBlock.removeClass(_prefix + 'filter-' + statuses[i].title.toLowerCase());
        }
        $listingBlock.addClass(_prefix + 'filter-' + status);

        checkEmpty(status);
      }

      // Set listing's search filter
      var setSearchFilter = function(term) {
        $listingBlock.find('.' + ltClass).each(function() {
          var str = $(this).attr('data-title');
          var re = new RegExp(term, "gi");

          if (re.test(str)) {
            $(this).removeClass('ohv-display-none');
          } else {
            $(this).addClass('ohv-display-none');
          }
        });

        // show/hide empty message
        var status = $(toggleLtStatus).find('input:checked').val();
        checkEmpty(status);
      }

      var setOrderFilter = function(order) {
        $listingBlock.find('.' + ltClass).sort(function(a, b) {
          if (order == 1) {
            return ($(a).attr('data-created_at') < $(b).attr('data-created_at'));
          }
          if (order == 2) {
            return ($(a).attr('data-created_at') > $(b).attr('data-created_at'));
          }
          if (order == 3) {
            return ($(a).attr('data-title') > $(b).attr('data-title'));
          }
          if (order == 4) {
            return ($(a).attr('data-title') < $(b).attr('data-title'));
          }
        }).appendTo($listingBlock);
      }

      // Set Filters
      var setFilters = function() {
        var status = $(toggleLtStatus).find('input:checked').val();
        var term = $(toggleLtSearch).val();
        var order = $(toggleLtOrder).val();

        _log('status: ' + status + ', term: ' + term + ', order: ' + order);

        setStatusFilter(status);
        setSearchFilter(term);
        setOrderFilter(order);
      };

      return {
        parse: parse,
        livePreview: livePreview,
        setFilters: setFilters,
        setIframeHeight: setIframeHeight,
        init: function() {
          if (!$listingBlock.length)
            return;
          if (!WPOpenHouseVideo.token)
            return;

          showListingData();
          handleEvents();

          _log('Init Listing');
        }
      }
    }();

    // Popup
    var popup = function() {
      var toggleLivePreview = '[data-toggle="' + _prefix + 'live-preview"]';
      var toggleInsertShortcode = '[data-toggle="' + _prefix + 'insert-shortcode"]';
      var toggleHome = '[data-toggle="' + _prefix + 'listings-home"]';

      var handleEvents = function() {
        // Open popup
        $(document).on('click', '[data-toggle="' + _prefix + 'popup"]', function(e) {
          e.preventDefault();

          _log('Open popup');

          // Save the target
          window.ohv_target = $(this).data('target');
          // Open magnificPopup
          $(this).magnificPopup({
            type: 'inline',
            alignTop: true,
            removalDelay: 300,
            mainClass: 'mfp-fade',
            callbacks: {
              open: function() {
                // Change z-index
                $('body').addClass(_prefix + 'mfp-shown');

                listing.init();
              },
              close: function() {
                // Change z-index
                $('body').removeClass(_prefix + 'mfp-shown');

                $popupBlock.removeClass(_prefix + 'show-preview');
                $previewBlock.find('.' + _prefix + 'listing-preview-content').empty();
                $listingBlock.empty();

                popup.disableActions();
              }
            }
          }).magnificPopup('open');
        });

        // Open preview
        $(document).on('click', toggleLivePreview, function(e) {
          _log('Preview');
          // Prevent default action
          e.preventDefault();
          // Show Lisve Preview
          listing.livePreview();
        });

        // Insert shortcode
        $(document).on('click', toggleInsertShortcode, function(e) {
          _log('Insert shortcode');
          // Prepare data
          var shortcode = listing.parse();
          // Close popup
          $.magnificPopup.close();
          // Prevent default action
          e.preventDefault();
          // Save original activeeditor
          window.ohv_wpActiveEditor = window.wpActiveEditor;
          // Set new active editor
          window.wpActiveEditor = window.ohv_target;
          // Insert shortcode
          window.wp.media.editor.insert(shortcode);
          // Restore previous editor
          window.wpActiveEditor = window.sohv_wpActiveEditor;
        });

        $(document).on('click', toggleHome, function(e) {
          _log('Open all listings pane');
          // Remove selector
          $popupBlock.removeClass(_prefix + 'show-preview');
          // Prevent default action
          e.preventDefault();
        });
      }

      // Toggle state of actions
      var toggleActions = function(state) {
        $(toggleLivePreview).prop('disabled', state);
        $(toggleInsertShortcode).prop('disabled', state);
      }

      return {
        init: function() {
          if (!$popupBlock.length)
            return;

          handleEvents();

          _log('Init popup');
        },
        enableActions: function() {
          toggleActions(false);
        },
        disableActions: function() {
          toggleActions(true);
        }
      }
    }();

    return {
      init: function() {
        _log('Init JS OHV v' + WPOpenHouseVideo.version);

        login.init();
        user.init();
        popup.init();
        info.init();

        handleEvents();
      }
    }
  }();

  OpenHouseVideo.init();
});

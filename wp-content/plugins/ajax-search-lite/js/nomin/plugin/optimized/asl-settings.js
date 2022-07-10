(function($){
    "use strict";
    let functions = {
        showSettings: function () {
            let $this = this;

            $this.n.searchsettings.css($this.settAnim.showCSS);
            $this.n.searchsettings.removeClass($this.settAnim.hideClass).addClass($this.settAnim.showClass);

            if ($this.settScroll == null && ($this.is_scroll) ) {
                $this.settScroll = [];
                $('.asl_sett_scroll', $this.n.searchsettings).forEach(function(o, i){
                    let _this = this;
                    // Small delay to fix a rendering issue
                    setTimeout(function(){
                        // noinspection JSUnresolvedFunction,JSUnresolvedVariable,JSPotentiallyInvalidConstructorUsage
                        $this.settScroll[i] = new asp_SimpleBar($(_this).get(0), {
                            direction: $('body').hasClass('rtl') ? 'rtl' : 'ltr',
                            autoHide: true
                        });
                    }, 20);
                });
            }
            $this.n.prosettings.data('opened', 1);
            $this.fixSettingsPosition(true);
        },
        hideSettings: function () {
            let $this = this;

            $this.n.searchsettings.removeClass($this.settAnim.showClass).addClass($this.settAnim.hideClass);
            setTimeout(function(){
                $this.n.searchsettings.css($this.settAnim.hideCSS);
            }, $this.settAnim.duration);

            $this.n.prosettings.data('opened', 0);
        }
    }
    $.fn.extend(window.WPD.ajaxsearchlite.plugin, functions);
})(WPD.dom);(function($){
    "use strict";
    let functions = {
        initFacetEvents: function() {
            let $this = this;
            $('input[type=checkbox]', $this.n.searchsettings).on('asl_chbx_change', function(e){
                $this.ktype = e.type;
                $this.n.searchsettings.find('input[name=filters_changed]').val(1);
                $this.gaEvent?.('facet_change', {
                    'option_label': $(this).closest('fieldset').find('legend').text(),
                    'option_value': $(this).closest('.asl_option').find('.asl_option_label').text() + ($(this).prop('checked') ? '(checked)' : '(unchecked)')
                });
                $this.setFilterStateInput(65);
                $this.searchWithCheck(80);
            });
        }
    }
    $.fn.extend(window.WPD.ajaxsearchlite.plugin, functions);
})(WPD.dom);(function($){
    "use strict";
    let functions = {
        initSettingsEvents: function() {
            let $this = this;

            // Note if the settings have changed
            $this.n.searchsettings.on('click', function(){
                $this.settingsChanged = true;
            });

            $this.n.searchsettings.on($this.clickTouchend, function (e) {
                /**
                 * Stop propagation on settings clicks, except the noUiSlider handler event.
                 * If noUiSlider event propagation is stopped, then the: set, end, change events does not fire properly.
                 */
                if ( typeof e.target != 'undefined' && !$(e.target).hasClass('noUi-handle') ) {
                    e.stopImmediatePropagation();
                }
            });

            $this.n.prosettings.on("click", function () {
                if ($this.n.prosettings.data('opened') == 0) {
                    $this.showSettings();
                } else {
                    $this.hideSettings();
                }
            });

            if ($this.o.settingsVisible == 1) {
                $this.showSettings(false);
            }

            // Category level automatic checking and hiding
            $('.asl_option_cat input[type="checkbox"]', $this.n.searchsettings).on('asl_chbx_change', function(){
                $this.settingsCheckboxToggle( $(this).closest('.asl_option_cat') );
            });
            // Init the hide settings
            $('.asl_option_cat', $this.n.searchsettings).forEach(function(el){
                $this.settingsCheckboxToggle( $(el), false );
            });
        }
    }
    $.fn.extend(window.WPD.ajaxsearchlite.plugin, functions);
})(WPD.dom);(function($){
    "use strict";
    let helpers = window.WPD.ajaxsearchlite.helpers;
    let functions = {
        initSettingsBox: function() {
            let $this = this;
            let appendSettingsTo = function($el) {
                let old = $this.n.searchsettings.get(0);
                $this.n.searchsettings = $this.n.searchsettings.clone();
                $el.append($this.n.searchsettings);


                $(old).find('*[id]').forEach(function(el){
                    if ( el.id.indexOf('__original__') < 0 ) {
                        el.id = '__original__' + el.id;
                    }
                });
                $this.n.searchsettings.find('*[id]').forEach(function(el){
                    if ( el.id.indexOf('__original__') > -1 ) {
                        el.id =  el.id.replace('__original__', '');
                    }
                });
            }

            appendSettingsTo($('body'));
            $this.n.searchsettings.get(0).id = $this.n.searchsettings.get(0).id.replace('__original__', '');
        },
        initSettingsAnimations: function() {
            let $this = this;
            $this.settAnim = {
                "showClass": "",
                "showCSS": {
                    "visibility": "visible",
                    "display": "block",
                    "opacity": 1,
                    "animation-duration": $this.animOptions.settings.dur + 'ms'
                },
                "hideClass": "",
                "hideCSS": {
                    "visibility": "hidden",
                    "opacity": 0,
                    "display": "none"
                },
                "duration": $this.animOptions.settings.dur + 'ms'
            };

            if ($this.animOptions.settings.anim == "fade") {
                $this.settAnim.showClass = "asl_an_fadeIn";
                $this.settAnim.hideClass = "asl_an_fadeOut";
            }

            if ($this.animOptions.settings.anim == "fadedrop" &&
                !$this.o.blocking ) {
                $this.settAnim.showClass = "asl_an_fadeInDrop";
                $this.settAnim.hideClass = "asl_an_fadeOutDrop";
            } else if ( $this.animOptions.settings.anim == "fadedrop" ) {
                // If does not support transitio, or it is blocking layout
                // .. fall back to fade
                $this.settAnim.showClass = "asl_an_fadeIn";
                $this.settAnim.hideClass = "asl_an_fadeOut";
            }

            $this.n.searchsettings.css({
                "-webkit-animation-duration": $this.settAnim.duration + "ms",
                "animation-duration": $this.settAnim.duration + "ms"
            });
        }
    }
    $.fn.extend(window.WPD.ajaxsearchlite.plugin, functions);
})(WPD.dom);
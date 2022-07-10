window._ASL_load = function () {
    "use strict";
    let $ = WPD.dom;

    window.ASL.instances = {
        instances: [],
        get: function(id, instance) {
            this.clean();
            if ( typeof id === 'undefined' || id == 0) {
                return this.instances;
            } else {
                if ( typeof instance === 'undefined' ) {
                    let ret = [];
                    for ( let i=0; i<this.instances.length; i++ ) {
                        if ( this.instances[i].o.id == id ) {
                            ret.push(this.instances[i]);
                        }
                    }
                    return ret.length > 0 ? ret : false;
                } else {
                    for ( let i=0; i<this.instances.length; i++ ) {
                        if ( this.instances[i].o.id == id && this.instances[i].o.iid == instance ) {
                            return this.instances[i];
                        }
                    }
                }
            }
            return false;
        },
        set: function(obj) {
            if ( !this.exist(obj.o.id, obj.o.iid) ) {
                this.instances.push(obj);
                return true;
            } else {
                return false;
            }
        },
        exist: function(id, instance) {
            this.clean();
            for ( let i=0; i<this.instances.length; i++ ) {
                if ( this.instances[i].o.id == id ) {
                    if (typeof instance === 'undefined') {
                        return true;
                    } else if (this.instances[i].o.iid == instance) {
                        return true;
                    }
                }
            }
            return false;
        },
        clean: function() {
            let unset = [], _this = this;
            this.instances.forEach(function(v, k){
                if ( $('.asl_m_' + v.o.rid).length == 0 ) {
                    unset.push(k);
                }
            });
            unset.forEach(function(k){
                if ( typeof _this.instances[k] !== 'undefined' ) {
                    _this.instances[k].destroy();
                    _this.instances.splice(k, 1);
                }
            });
        },
        destroy: function(id, instance) {
            let i = this.get(id, instance);
            if ( i !== false ) {
                if ( Array.isArray(i) ) {
                    i.forEach(function (s) {
                        s.destroy();
                    });
                    this.instances = [];
                } else {
                    let u = 0;
                    this.instances.forEach(function(v, k){
                        if ( v.o.id == id && v.o.iid == instance) {
                            u = k;
                        }
                    });
                    i.destroy();
                    this.instances.splice(u, 1);
                }
            }
        }
    };

    window.ASL.initialized = false;
    window.ASL.initializeById = function (id, ignoreViewport) {
        let selector = ".asl_init_data";
        ignoreViewport = typeof ignoreViewport == 'undefined' ? false : ignoreViewport;
        if (typeof id !== 'undefined' && typeof id != 'object')
            selector = "div[id*=asl_init_id_" + id + "]";

        /**
         * Getting around inline script declarations with this solution.
         * So these new, invisible divs contains a JSON object with the parameters.
         * Parse all of them and do the declaration.
         */
        let initialized = 0;
        $(selector).forEach(function (el) {
            // noinspection JSUnusedAssignment
            let $asl = $(el).closest('.asl_w_container').find('.asl_m');
            // $asl.length == 0 -> when fixed compact layout mode is enabled
            if ( $asl.length == 0 || typeof $asl.get(0).hasAsl != 'undefined') {
                ++initialized;
                return true;
            }

            if (!ignoreViewport && !$asl.inViewPort(-100)) {
                return true;
            }

            let jsonData = $(el).data("asldata");
            if (typeof jsonData === "undefined") return true;   // Do not return false, it breaks the loop!

            jsonData = WPD.Base64.decode(jsonData);
            if (typeof jsonData === "undefined" || jsonData == "") return true; // Do not return false, it breaks the loop!

            let args = JSON.parse(jsonData);
            $asl.get(0).hasAsl = true;
            ++initialized;
            return $asl.ajaxsearchlite(args);
        });

        if ($(selector).length == initialized) {
            document.removeEventListener('scroll', ASL.initializeById);
            document.removeEventListener('resize', ASL.initializeById);
        }
    }

// Call this function if you need to initialize an instance that is printed after an AJAX call
// Calling without an argument initializes all instances found.
    window.ASL.initialize = function (id) {
        // this here is either window.ASL or window._ASL
        let _this = this;

        // Some weird ajax loader problem prevention
        if (typeof _this.version == 'undefined')
            return false;

        // noinspection JSUnresolvedVariable
        if ( ASL.script_async_load ) {
            document.addEventListener('scroll', ASL.initializeById, {passive: true});
            document.addEventListener('resize', ASL.initializeById, {passive: true});
            ASL.initializeById(id);
        } else {
            ASL.initializeById(id, true);
        }

        if (_this.highlight.enabled) {
            let data = localStorage.getItem('asl_phrase_highlight');
            localStorage.removeItem('asl_phrase_highlight');
            if (data != null) {
                data = JSON.parse(data);
                _this.highlight.data.forEach(function (o) {
                    let selector = o.selector != '' && $(o.selector).length > 0 ? o.selector : 'article',
                        $highlighted;
                    selector = $(selector).length > 0 ? selector : 'body';
                    // noinspection JSUnresolvedVariable
                    $(selector).highlight(data.phrase, {
                        element: 'span',
                        className: 'asl_single_highlighted',
                        wordsOnly: o.whole,
                        excludeParents: '.asl_w, .asl-try'
                    });
                    $highlighted = $('.asl_single_highlighted');
                    if (o.scroll && $highlighted.length > 0) {
                        let stop = $highlighted.offset().top - 120;
                        let $adminbar = $("#wpadminbar");
                        if ($adminbar.length > 0)
                            stop -= $adminbar.height();
                        // noinspection JSUnresolvedVariable
                        stop = stop + o.scroll_offset;
                        stop = stop < 0 ? 0 : stop;
                        $('html').animate({
                            "scrollTop": stop
                        }, 500);
                    }
                    return false;
                });
            }
        }

        _this.initialized = true;
    };

    window.ASL.ready = function () {
        let _this = this,
            $body = $('body'),
            t, tt, ttt, ts;

        /**
         * This function is triggered right after the script stack is loaded, when using the async loader.
         * The DOMContentLoaded is already fired, so we need to force the init.
         */
        if ( ASL.script_async_load ) {
            _this.initialize();
        }

        $(document).on('DOMContentLoaded', function() {
            _this.initialize();
        });

        // DOM tree modification detection to re-initialize automatically if enabled
        // noinspection JSUnresolvedVariable
        if (typeof ASL.detect_ajax != "undefined" && ASL.detect_ajax == 1) {
            let observer = new MutationObserver(function() {
                clearTimeout(t);
                t = setTimeout(function () {
                    _this.initialize();
                }, 500);
            });
            function addObserverIfDesiredNodeAvailable() {
                let db = document.querySelector("body");
                if( !db ) {
                    //The node we need does not exist yet.
                    //Wait 500ms and try again
                    window.setTimeout(addObserverIfDesiredNodeAvailable,500);
                    return;
                }
                observer.observe(db, {subtree: true, childList: true});
            }
            addObserverIfDesiredNodeAvailable();
        }

        $(window).on('resize', function () {
            clearTimeout(tt);
            tt = setTimeout(function () {
                _this.initializeById();
            }, 200);
        });

        // Known slide-out and other type of menus to initialize on click
        ts = '#menu-item-search, .fa-search, .fa, .fas';
        // Avada theme
        ts = ts + ', .fusion-flyout-menu-toggle, .fusion-main-menu-search-open';
        // Be theme
        ts = ts + ', #search_button';
        // The 7 theme
        ts = ts + ', .mini-search.popup-search';
        // Flatsome theme
        ts = ts + ', .icon-search';
        // Enfold theme
        ts = ts + ', .menu-item-search-dropdown';
        // Uncode theme
        ts = ts + ', .mobile-menu-button';
        // Newspaper theme
        ts = ts + ', .td-icon-search, .tdb-search-icon';
        // Bridge theme
        ts = ts + ', .side_menu_button, .search_button';
        // Jupiter theme
        ts = ts + ', .raven-search-form-toggle';
        // Elementor trigger lightbox & other elementor stuff
        ts = ts + ', [data-elementor-open-lightbox], .elementor-button-link, .elementor-button';
        ts = ts + ', i[class*=-search], a[class*=-search]';

        // Attach this to the document ready, as it may not attach if this is loaded early
        $body.on('click touchend', ts, function () {
            clearTimeout(ttt);
            ttt = setTimeout(function () {
                _this.initializeById({}, true);
            }, 300);
        });

        // Elementor popup events (only works with jQuery)
        if ( typeof jQuery != 'undefined' ) {
            jQuery(document).on('elementor/popup/show', function(){
                setTimeout(function () {
                    _this.initializeById({}, true);
                }, 10);
            });
        }
    };

    window.ASL.loadScriptStack = function (stack) {
        let scriptTag;
        if ( stack.length > 0 ) {
            scriptTag = document.createElement('script');
            scriptTag.src = stack.shift()['src'];
            scriptTag.onload = function () {
                if ( stack.length > 0 ) {
                    window.ASL.loadScriptStack(stack)
                } else {
                    window.ASL.ready();
                }
            }
            document.body.appendChild(scriptTag);
        }
    }

    window.ASL.init = function () {
        // noinspection JSUnresolvedVariable
        if (ASL.script_async_load) {   // Opimized Normal
            // noinspection JSUnresolvedVariable
            window.ASL.loadScriptStack(ASL.additional_scripts);
        } else {
            if (typeof WPD.ajaxsearchlite !== 'undefined') {   // Classic normal
                window.ASL.ready();
            }
        }
    };

    // noinspection JSUnresolvedVariable
    if (
        !window.ASL.css_async ||
        typeof window.ASL.css_loaded != 'undefined' // CSS loader finished, but this script was not ready yet
    ) {
        window.WPD.intervalUntilExecute(window.ASL.init, function() {
            return typeof window.ASL.version != 'undefined' && $.fn.ajaxsearchlite != 'undefined'
        });
    }
};
// Run on document ready
(function() {
    // Preload script executed?
    if ( typeof WPD != 'undefined' && typeof WPD.dom != 'undefined' ) {
        window._ASL_load();
    } else {
        document.addEventListener('wpd-dom-core-loaded', window._ASL_load);
    }
})();
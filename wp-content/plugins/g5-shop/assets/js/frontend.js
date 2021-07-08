var G5SHOP = window.G5SHOP || {};
(function ($) {
    "use strict";
    window.G5SHOP = G5SHOP;


    var $window = $(window),
        $body = $('body'),
        isRTL = $body.hasClass('rtl');

    G5SHOP = {
        init: function () {
            this.tooltip();
            this.addToCart();
            this.saleCountdown();
            this.switchLayout();
            this.updateAjaxSuccess();
            this.filter();
            this.productSearchForm();
            this.ordering();
            this.filterAjax();
            this.singleProductImage();
            this.singleProductVideo();
            this.quantity();
            this.singleProductTabs();
            this.quickView();
            this.categoryFilter();
        },
        tooltip: function () {
            $('.yith-wcwl-wishlistexistsbrowse > a,.yith-wcwl-add-button > a,.yith-wcwl-wishlistaddedbrowse > a,.g5shop__product-actions .compare,.g5shop__product-list-actions .compare', '.woocommerce').each(function () {
                var title = $(this).text().trim(),
                    configs = {
                        title: title,
                        container: $(this).parent()
                    };
                if ($(this).closest('.g5core__tooltip-wrap').length) {
                    configs = $.extend({}, configs, $(this).closest('.g5core__tooltip-wrap').data('tooltip-options'));
                }
                $(this).tooltip(configs);
            });
        },
        addToCart: function () {
            $(document).on('click', '.add_to_cart_button', function () {
                var button = $(this);
                if (button.hasClass('ajax_add_to_cart')) {
                    var productWrap = button.closest('.g5shop__product-item-inner');
                    if (typeof(productWrap) === 'undefined') {
                        return;
                    }
                    productWrap.addClass('active');
                }
            });

            $body.on('wc_cart_button_updated', function (event, $button) {
                var buttonWrap = $button.parent(),
                    buttonViewCart = buttonWrap.find('.added_to_cart'),
                    addedTitle = buttonViewCart.text(),
                    productWrap = buttonWrap.closest('.g5shop__product-item-inner');

                if ($button.closest('.g5shop__product-list-actions').length) {
                    buttonViewCart.addClass('button');
                }

                if (!$button.closest('.gf-product-swatched').length) {
                    $button.remove();
                }


                if (buttonWrap.data('toggle')) {
                    setTimeout(function () {
                        buttonWrap.tooltip('hide').attr('title', addedTitle).tooltip('_fixTitle');
                    }, 500);
                }

                setTimeout(function () {
                    productWrap.removeClass('active');
                }, 700);
            });
        },
        saleCountdown: function () {
            $('.g5shop__product-deal-countdown').each(function () {
                var date_end = $(this).data('date-end');
                var $this = $(this);
                $this.countdown(date_end, function (event) {
                    count_down_callback(event, $this);
                }).on('update.countdown', function (event) {
                    count_down_callback(event, $this);
                });
            });

            function count_down_callback(event, $this) {
                var seconds = parseInt(event.offset.seconds);
                var minutes = parseInt(event.offset.minutes);
                var hours = parseInt(event.offset.hours);
                var days = parseInt(event.offset.totalDays);

                if (days < 10) days = '0' + days;
                if (hours < 10) hours = '0' + hours;
                if (minutes < 10) minutes = '0' + minutes;
                if (seconds < 10) seconds = '0' + seconds;


                $('.countdown-day', $this).text(days);
                $('.countdown-hours', $this).text(hours);
                $('.countdown-minutes', $this).text(minutes);
                $('.countdown-seconds', $this).text(seconds);
            }
        },
        switchLayout : function() {
            $(document).on('click', '.g5shop__switch-layout > a', function (event) {
                event.preventDefault();
                var layout = $(this).data('layout'),
                    $wrapper = $('[data-archive-wrapper]');
                if ($wrapper.length > 0) {
                    var settingId = $wrapper.data('items-wrapper');
                    G5CORE.paginationAjax.loadPosts(settingId, this);
                    $.cookie('g5shop_product_layout', layout, {expires: 15});
                }
            });
        },
        updateAjaxSuccess: function () {
            var self = this;
            $body.on('g5core_pagination_ajax_success', function (event, _data, $ajaxHTML, target, loadMore) {
                if (_data.settings['post_type'] === 'product') {
                    self.saleCountdown();
                    self.tooltip();

                    var $shop_filter = $('.g5shop__shop-toolbar');
                    if ($shop_filter.length && !loadMore && (typeof _data.settings['isMainQuery'] !== 'undefined')) {
                        var $result_shop_filter = $ajaxHTML.find('.g5shop__shop-toolbar');
                        if ($result_shop_filter.length) {
                            $shop_filter.replaceWith($result_shop_filter.removeAttr('hidden').prop('outerHTML'));
                            $('.woocommerce-ordering').off('change').on('change', 'select.orderby', function () {
                                $(this).closest('form').submit();
                            });
                            $('.g5core__pretty-tabs').g5core__PrettyTabs();

                            var $switch_layout = $('.g5shop__switch-layout');
                            if ($switch_layout.length) {
                                var $resultWrapper = $ajaxHTML.find('[data-items-wrapper]'),
                                    resultWrapperClass = $resultWrapper.attr('class');
                                $(event.target).attr('class',resultWrapperClass);

                            }

                            self.ordering();
                            self.categoryFilter();

                        }
                    }
                }
            });

            $body.on('g5core_pagination_ajax_before_update_sidebar', function (event, _data, $ajaxHTML, target, loadMore) {
                if (_data.settings['post_type'] === 'product') {


                    $('.dropdown_product_cat').off('change').on('change', function () {
                        if ($(this).val() != '') {
                            var this_page = '';
                            var home_url = g5shop_vars.home_url;
                            if (home_url.indexOf('?') > 0) {
                                this_page = home_url + '&product_cat=' + $(this).val();
                            } else {
                                this_page = home_url + '?product_cat=' + $(this).val();
                            }
                            location.href = this_page;
                        } else {
                            location.href = g5shop_vars.shop_url;
                        }
                    });


                    $('.woocommerce-widget-layered-nav-dropdown').off('change').on('change', function () {
                        var slug = $(this).val(),
                            $wrapper = $(this).closest('.woocommerce-widget-layered-nav-dropdown');
                        $('input[type="hidden"]', $wrapper).val(slug);

                        // Submit form on change if standard dropdown.
                        if (!$(this).attr('multiple')) {
                            $(this).closest('form').submit();
                        }
                    });


                    $('.woocommerce-widget-layered-nav-dropdown select,.dropdown_product_cat').selectWoo({
                        width: '100%',
                    }).on('select2:select', function () {
                        $(this).focus(); // Maintain focus after select https://github.com/select2/select2/issues/4384
                    });

                    self.priceFilter();

                }
            });

        },
        filter: function () {
            $(document).on('click', '.g5shop__filter-button', function (event) {
                event.preventDefault();
                var _self = this,
                    $content = $('.g5shop__archive-filter-content');
                $content.slideToggle('500',function () {
                    if ($(_self).closest('.stretched').length && $('.primary-sidebar'.length && $('.primary-sidebar').hasClass('.sidebar-sticky'))) {
                        $('.primary-sidebar.sidebar-sticky > .primary-sidebar-inner').hcSticky('refresh');
                    }

                });
            });


            var $wrapper = $('.g5shop__shop-toolbar'),
                $primary_content = $('#primary-content');
            if ($wrapper.length) {
                if ($wrapper.hasClass('stretched') || $wrapper.hasClass('stretched_content')) {
                    $wrapper.detach().insertBefore($primary_content);
                }
                $wrapper.removeAttr('hidden');
                $('.g5core__pretty-tabs', $wrapper).g5core__PrettyTabs();
            }


        },
        miniCart: function () {
            $(document).on('wc_fragments_loaded', function () {
                $('.g5shop__mini-cart .woocommerce-mini-cart').perfectScrollbar({
                    wheelSpeed: 0.5,
                    suppressScrollX: true
                });

            });
        },
        productSearchForm: function () {
            var $form = $('.g5shop__search-product'),
	            $input = $form.find('input[type="search"]'),
                dropdownCssClass = $form.closest('.g5core-search-popup').length > 0 ? 'g5shop__search-product-cat-select2-dropdown-popup'  : 'g5shop__search-product-cat-select2-dropdown';
            if ($form.hasClass('g5core-search-ajax')) {
                var $result = $form.find('.result'),
                    $remove = $form.find('.remove');
            }
            if ($().selectWoo) {
                $('.g5shop__search-product-cat select')
                    .on('select2:select', function () {
                        $(this).focus(); // Maintain focus after select https://github.com/select2/select2/issues/4384
                    })
                    .selectWoo({
                        width: '100%',
                        dropdownCssClass: dropdownCssClass
                    }).on('select2:opening', function (e) {
	                $input.val('');

	                if ($form.hasClass('g5core-search-ajax')) {
		                $result.html('');
		                $remove.removeClass('in');
                    }
                }).on('select2:close', function (e) {
                    setTimeout(function () {
                        $input.focus();
                    }, 50);

                });
            }
        },
        ordering: function () {
            if ($().selectWoo) {
                $('.woocommerce-ordering select').selectWoo({
                    width: '100%',
                }).on('select2:select', function () {
                    $(this).focus(); // Maintain focus after select https://github.com/select2/select2/issues/4384
                });
            }
        },
        categoryFilter: function() {
            var $allCategory = $('<li class="cate-item"><a href="'+ g5shop_vars.shop_url +'">'+ g5shop_vars.localization.all_category +'</a></li>');
            $('.g5shop__archive-filter-content ul.product-categories').prepend($allCategory);
        },
        priceFilter: function () {
            if ($().slider) {
                $('input#min_price, input#max_price').hide();
                $('.price_slider, .price_label').show();

                var min_price = $('.price_slider_amount #min_price').data('min'),
                    max_price = $('.price_slider_amount #max_price').data('max'),
                    current_min_price = $('.price_slider_amount #min_price').val(),
                    current_max_price = $('.price_slider_amount #max_price').val();

                $('.price_slider:not(.ui-slider)').slider({
                    range: true,
                    animate: true,
                    min: min_price,
                    max: max_price,
                    values: [current_min_price, current_max_price],
                    create: function () {

                        $('.price_slider_amount #min_price').val(current_min_price);
                        $('.price_slider_amount #max_price').val(current_max_price);

                        $(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
                    },
                    slide: function (event, ui) {

                        $('input#min_price').val(ui.values[0]);
                        $('input#max_price').val(ui.values[1]);

                        $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                    },
                    change: function (event, ui) {

                        $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                    }
                });


                $('input#min_price, input#max_price').hide();
                $('.price_slider, .price_label').show();

                var min_price = $('.price_slider_amount #min_price').data('min'),
                    max_price = $('.price_slider_amount #max_price').data('max'),
                    step = $('.price_slider_amount').data('step') || 1,
                    current_min_price = $('.price_slider_amount #min_price').val(),
                    current_max_price = $('.price_slider_amount #max_price').val();

                $('.price_slider:not(.ui-slider)').slider({
                    range: true,
                    animate: true,
                    min: min_price,
                    max: max_price,
                    step: step,
                    values: [current_min_price, current_max_price],
                    create: function () {

                        $('.price_slider_amount #min_price').val(current_min_price);
                        $('.price_slider_amount #max_price').val(current_max_price);

                        $(document.body).trigger('price_slider_create', [current_min_price, current_max_price]);
                    },
                    slide: function (event, ui) {

                        $('input#min_price').val(ui.values[0]);
                        $('input#max_price').val(ui.values[1]);

                        $(document.body).trigger('price_slider_slide', [ui.values[0], ui.values[1]]);
                    },
                    change: function (event, ui) {

                        $(document.body).trigger('price_slider_change', [ui.values[0], ui.values[1]]);
                    }
                });
            }


        },
        filterAjax: function () {
            $(document).on('click', '.widget_product_categories .product-categories li > a,.g5shop__product-sorting li > a, .g5shop__price-filter li > a', function (event) {
                var $wrapper = $('[data-archive-wrapper]');
                if ($wrapper.length > 0) {
                    event.preventDefault();
                    if (($(this).closest('.current-cat').length === 0)
                        && ($(this).closest('.current').length === 0)) {
                        var settingId = $wrapper.data('items-wrapper');
                        G5CORE.paginationAjax.loadPosts(settingId, this);
                    }
                }
            });

            $(document).on('click', '.woocommerce-widget-layered-nav-list li > a,.widget_layered_nav_filters ul li > a,.widget_rating_filter ul li > a', function (event) {
                var $wrapper = $('[data-archive-wrapper]');
                if ($wrapper.length > 0) {
                    event.preventDefault();
                    var settingId = $wrapper.data('items-wrapper');
                    G5CORE.paginationAjax.loadPosts(settingId, this);
                }
            });

        },
        singleProductImage: function ($productImageWrap) {
            if (typeof $productImageWrap === "undefined") {
                $productImageWrap = $('.woocommerce-product-gallery');
            }
            if ($productImageWrap.length === 0) return;
            var $slider_thumb = $productImageWrap.find('.flex-control-thumbs'),
                type = $productImageWrap.closest('.g5shop__product-gallery-horizontal').length ? 'horizontal' : 'vertical';

            if ($slider_thumb.length && ($().slick) ) {
                if (type === 'horizontal') {
                    $slider_thumb.slick(g5shop_vars.single_product_gallery_horizontal_args);
                } else  {
                    $slider_thumb.slick(g5shop_vars.single_product_gallery_vertical_args);
                }
            }

            $(window).on('resize',function () {
                // Trigger resize after main image loads to ensure correct gallery size.
                var $image =  $( '.woocommerce-product-gallery__wrapper .woocommerce-product-gallery__image:eq(0)');
                if ($image) {
                    setTimeout( function() {
                        var setHeight = $image.closest( '.woocommerce-product-gallery__image' ).height();
                        var $viewport = $image.closest( '.flex-viewport' );

                        if ( setHeight && $viewport ) {
                            $viewport.height( setHeight );
                        }
                    }, 100 );
                }
            });
        },
        singleProductVideo : function () {
            var $product_video = $('.g5shop__product-gallery-video'),
                $product_gallery = $('.woocommerce-product-gallery');
            if ($product_video.length && $product_gallery.length) {
                $product_video = $product_video.detach();
                $product_gallery.prepend($product_video);
                $product_video.removeAttr('hidden');
            }
        },
        quantity: function () {
            $(document).on('click', '.g5shop__quantity .btn-number', function (event) {
                event.preventDefault();
                var type = $(this).data('type'),
                    input = $('input', $(this).parent()),
                    current_value = parseFloat(input.val()),
                    max = parseFloat(input.attr('max')),
                    min = parseFloat(input.attr('min')),
                    step = parseFloat(input.attr('step')),
                    stepLength = 0;
                if (input.attr('step').indexOf('.') > 0) {
                    stepLength = input.attr('step').split('.')[1].length;
                }



                if (isNaN(max)) {
                    max = 100;
                }
                if (isNaN(min)) {
                    min = 0;
                }
                if (isNaN(step)) {
                    step = 1;
                    stepLength = 0;
                }

                if (!isNaN(current_value)) {
                    if (type == 'minus') {
                        if (current_value > min) {
                            current_value = (current_value - step).toFixed(stepLength);
                            input.val(current_value).change();
                        }

                        if (parseFloat(input.val()) <= min) {
                            input.val(min).change();
                            $(this).attr('disabled', true);
                        }
                    }

                    if (type == 'plus') {
                        if (current_value < max) {
                            current_value = (current_value + step).toFixed(stepLength);
                            input.val(current_value).change();
                        }
                        if (parseFloat(input.val()) >= max) {
                            input.val(max).change();
                            $(this).attr('disabled', true);
                        }
                    }
                } else {
                    input.val(min);
                }
            });


            $('input', '.g5shop__quantity').on('focusin', function () {
                $(this).data('oldValue', $(this).val());
            });

            $('input', '.g5shop__quantity').on('change', function () {
                var input = $(this),
                    max = parseFloat(input.attr('max')),
                    min = parseFloat(input.attr('min')),
                    current_value = parseFloat(input.val()),
                    step = parseFloat(input.attr('step'));

                if (isNaN(max)) {
                    max = 100;
                }
                if (isNaN(min)) {
                    min = 0;
                }

                if (isNaN(step)) {
                    step = 1;
                }


                var btn_add_to_cart = $('.add_to_cart_button', $(this).parent().parent().parent());
                if (current_value >= min) {
                    $(".btn-number[data-type='minus']", $(this).parent()).removeAttr('disabled');
                    if (typeof(btn_add_to_cart) != 'undefined') {
                        btn_add_to_cart.attr('data-quantity', current_value);
                    }

                } else {
                    alert(g5shop_vars.localization.quantity_minimum_alert);
                    $(this).val($(this).data('oldValue'));

                    if (typeof(btn_add_to_cart) != 'undefined') {
                        btn_add_to_cart.attr('data-quantity', $(this).data('oldValue'));
                    }
                }

                if (current_value <= max) {
                    $(".btn-number[data-type='plus']", $(this).parent()).removeAttr('disabled');
                    if (typeof(btn_add_to_cart) != 'undefined') {
                        btn_add_to_cart.attr('data-quantity', current_value);
                    }
                } else {
                    alert(g5shop_vars.localization.quantity_maximum_alert);
                    $(this).val($(this).data('oldValue'));
                    if (typeof(btn_add_to_cart) != 'undefined') {
                        btn_add_to_cart.attr('data-quantity', $(this).data('oldValue'));
                    }
                }

            });
        },
        singleProductTabs: function () {
            $('.g5shop__tabs-container a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var $currentTab = $(e.target),
                    $previousTab = $(e.relatedTarget),
                    $currentPanel = $('.g5shop__panels-container').find($currentTab.attr('href')),
                    $previousPanel = $('.g5shop__panels-container').find($previousTab.attr('href'));

                $currentPanel.find('.collapse').addClass('show');
                $previousPanel.find('.collapse').removeClass('show');

            });

            $('.g5shop__panels-container .collapse').on('shown.bs.collapse', function (e) {
                var $currentPanel = $(e.target).closest('.tab-pane'),
                    $currentTab = $('.g5shop__tabs-container').find('.' + $currentPanel.attr('id'));

                $('.g5shop__tabs-container').find('a').removeClass('active');
                $('.g5shop__panels-container').find('.tab-pane').removeClass('show active');
                $currentPanel.addClass('show active');
                $currentTab.addClass('active');
            })
        },
        quickView: function () {
            var is_click_quick_view = false,
                self = this;
            $(document).on('click','.g5shop__quick-view',function (event) {
               event.preventDefault();
                if (is_click_quick_view) return;
                is_click_quick_view = true;
                var $this = $(this),
                    product_id = $this.data('product_id'),
                    nonce = $this.data('nonce'),
                    popupWrapper = '#g5shop__popup-product-quick-view',
                    $icon = $this.find('i'),
                    iconClass = $icon.attr('class'),
                    productWrap = $this.closest('.g5shop__product-item-inner'),
                    button = $this;

                productWrap.addClass('active');
                button.addClass('active');
                $icon.attr('class', 'far fa-spinner fa-spin');

                $.ajax({
                    url: g5shop_vars.ajax_url,
                    data: {
                        action: 'g5shop_product_quick_view',
                        id: product_id,
                        _ajax_nonce : nonce,
                    },
                    success: function (html) {
                        productWrap.removeClass('active');
                        button.removeClass('active');
                        $icon.attr('class', iconClass);

                        if ($(popupWrapper).length) {
                            $(popupWrapper).remove();
                        }
                        $body.append(html);

                        if (typeof $.fn.wc_variation_form !== 'undefined') {
                            var form_variation = $(popupWrapper).find('.variations_form');
                            var form_variation_select = $(popupWrapper).find('.variations_form .variations select');
                            form_variation.wc_variation_form();
                            form_variation.trigger('check_variations');
                            form_variation_select.change();
                        }


                        $.magnificPopup.open({
                           items: {
                               src : popupWrapper
                           },
                            type: 'inline',
                            closeOnBgClick: false,
                            closeBtnInside: true,
                            mainClass: 'mfp-move-from-top',
                            removalDelay: 700,
                        });


                        if( typeof $.fn.wc_product_gallery !== 'undefined' ) {
                            var $productImageWrap = $('.woocommerce-product-gallery', popupWrapper);
                            setTimeout(function () {
                                $productImageWrap.wc_product_gallery( wc_single_product_params );
                                self.singleProductImage($productImageWrap);
                                self.singleProductVideo();
                            }, 200);
                        }

                        self.tooltip();
                        G5CORE.util.tooltip();
                        self.saleCountdown();
                        is_click_quick_view = false;
                        $(popupWrapper).trigger('g5shop_product_quick_view_success');
                    },
                    error: function () {
                        is_click_quick_view = false;
                    }
                });


            });
        }
    };

    $(document).ready(function () {
        G5SHOP.init();
    });

    G5SHOP.miniCart();
})(jQuery);
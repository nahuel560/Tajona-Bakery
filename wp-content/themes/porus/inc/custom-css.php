<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( function_exists( 'G5CORE' ) ) {
	add_action( 'template_redirect', 'porus_custom_css', 20 );
}

function porus_custom_css() {
	$custom_css = '';
	$custom_css .= porus_custom_text_color();
	$custom_css .= porus_custom_accent_color();
	$custom_css .= porus_custom_border_color();
	$custom_css .= porus_custom_heading_color();
	$custom_css .= porus_custom_caption_color();
	$custom_css .= porus_custom_placeholder_color();
	$custom_css .= porus_custom_primary_color();
	$custom_css .= porus_custom_secondary_color();
	$custom_css .= porus_custom_dark_color();
	$custom_css .= porus_custom_light_color();
	$custom_css .= porus_custom_body_font();
	$custom_css .= porus_custom_primary_font();
	G5CORE()->custom_css()->addCss( $custom_css );
}


function porus_custom_text_color() {
	$text_color = G5CORE()->options()->color()->get_option( 'site_text_color' );
	return <<<CSS
	
.gel-pricing-progress .pricing-feature.disable {
  color: {$text_color} !important;
}
.gel-pricing-progress .pricing-feature.disable .list-bullet {
  color: {$text_color} !important;
}

.text-color,
body,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02.vc_tta-tabs .vc_tta-panels-container .gel-image-box .price-short-desc del,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column.vc_tta-tabs .vc_tta-panels-container .gel-image-box .price-short-desc del,
.page-numbers:not(ul) {
  color: {$text_color};
}

.text-border-color,
.article-post .entry-meta li:not(:last-child) {
  border-color: {$text_color};
}

.text-bg-color,
ul.g5blog__post-meta li:after {
  background-color: {$text_color};
}

.select2-container--default.select2-container--default .select2-selection--single .select2-selection__rendered {
  color: {$text_color};
}

.woocommerce a.remove:before,
.woocommerce .g5shop__product-item .g5shop__product-info .price del,
.woocommerce ul.products li.product .price del,
.g5shop__search-product-popup .select2-container--default.select2-container--default .select2-selection--single .select2-selection__rendered,
.g5shop__search-product .result del .amount,
.g5shop__loop-product-cat,
.woocommerce ul.product_list_widget:not(.cart_list) li del span.amount,
.woocommerce div.product div.summary p.price del,
.woocommerce div.product div.summary span.price del {
  color: {$text_color};
}
CSS;

}


function porus_custom_accent_color() {
	$accent_color            = G5CORE()->options()->color()->get_option( 'accent_color' );
	$accent_foreground_color = g5core_color_contrast( $accent_color );
	$accent_color_darken_075 = g5core_color_darken( $accent_color, '7.5%' );
	$accent_color_darken_10  = g5core_color_darken( $accent_color, '10%' );
	$accent_color_lighten_10 = g5core_color_lighten( $accent_color, '10%' );
	$accent_adjust_brightness_15 = g5core_color_adjust_brightness( $accent_color ,'15%');


	return <<<CSS

::-moz-selection {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
}

::selection {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
}

.btn,
button,
input[type=button],
input[type=reset],
input[type=submit] {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}

.btn:focus, .btn:hover, .btn:active,
button:focus,
button:hover,
button:active,
input[type=button]:focus,
input[type=button]:hover,
input[type=button]:active,
input[type=reset]:focus,
input[type=reset]:hover,
input[type=reset]:active,
input[type=submit]:focus,
input[type=submit]:hover,
input[type=submit]:active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075}; 
  border-color: {$accent_color_darken_10};
}

.btn.btn-outline,
button.btn-outline,
input[type=button].btn-outline,
input[type=reset].btn-outline,
input[type=submit].btn-outline {
  color: {$accent_color};
}

.btn.btn-outline:focus, .btn.btn-outline:hover, .btn.btn-outline:active,
button.btn-outline:focus,
button.btn-outline:hover,
button.btn-outline:active,
input[type=button].btn-outline:focus,
input[type=button].btn-outline:hover,
input[type=button].btn-outline:active,
input[type=reset].btn-outline:focus,
input[type=reset].btn-outline:hover,
input[type=reset].btn-outline:active,
input[type=submit].btn-outline:focus,
input[type=submit].btn-outline:hover,
input[type=submit].btn-outline:active {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
  border-color: {$accent_color};
}

.btn.btn-link,
button.btn-link,
input[type=button].btn-link,
input[type=reset].btn-link,
input[type=submit].btn-link {
  color: {$accent_color};
}

.btn.btn-accent {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}
.btn.btn-accent:focus, .btn.btn-accent:hover, .btn.btn-accent:active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075};
  border-color: {$accent_color_darken_10};
}
.btn.btn-accent.btn-outline {
  color: {$accent_color};
}
.btn.btn-accent.btn-outline:focus, .btn.btn-accent.btn-outline:hover, .btn.btn-accent.btn-outline:active {
  background-color: {$accent_color};
  color: {$accent_foreground_color};
  border-color: {$accent_color};
}
.btn.btn-accent.btn-link {
  color: {$accent_color};
}

.g5core-menu-popup-wrapper .mfp-close:hover {
  border-color: {$accent_color} !important;
}

p.porus-mailchimp input[type=email]:-moz-placeholder {
  color: {$accent_color} !important;
}
p.porus-mailchimp input[type=email]::-moz-placeholder {
  color: {$accent_color} !important;
}
p.porus-mailchimp input[type=email]:-ms-input-placeholder {
  color: {$accent_color} !important;
}
p.porus-mailchimp input[type=email]::-webkit-input-placeholder {
  color: {$accent_color} !important;
}

.wp-block-button__link:not(.has-background):not(.has-text-color) {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}
.wp-block-button__link:not(.has-background):not(.has-text-color):focus, .wp-block-button__link:not(.has-background):not(.has-text-color):hover, .wp-block-button__link:not(.has-background):not(.has-text-color):active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075};
  border-color: {$accent_color_darken_10};
}

.wp-block-button:not(.is-style-outline) .wp-block-button__link:hover {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}

.wp-block-button:not(.is-style-outline) .wp-block-button__link:hover:focus, .wp-block-button:not(.is-style-outline) .wp-block-button__link:hover:hover, .wp-block-button:not(.is-style-outline) .wp-block-button__link:hover:active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075};
  border-color: {$accent_color_darken_10};
}

.wp-block-button.is-style-outline .wp-block-button__link:hover {
  background-color: {$accent_color} !important;
}

.accent-text-color,
.custom-content-block p,
.gel-testimonial .author-attr h4,
.gel-heading-description,
.gel-heading-title mark,
.gel-pricing-style-1 .pricing-name,
.gel-pricing-style-2 .pricing-name,
.gel-pricing-style-3 .pricing-name,
.custom-booking-form .rtb-booking-form fieldset input,
.custom-booking-form .rtb-booking-form fieldset select,
.custom-booking-form .rtb-booking-form fieldset textarea,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02.vc_tta-tabs .vc_tta-panels-container .gel-image-box .price-short-desc ins,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column.vc_tta-tabs .vc_tta-panels-container .gel-image-box .price-short-desc ins,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link:after,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector select,
.contact-reservation input[type=text],
.contact-reservation input[type=email],
.contact-reservation input[type=date],
.contact-reservation select,
.site-info a,
.custom-text-footer p,
.custom-icon-list ul li i,
.page-sub-title,
.slick-dots li.slick-active,
.slick-dots li:hover,
.slick-arrow:active,
.slick-dots li:active,
.slick-arrow:focus,
.slick-dots li:focus,
.g5core__cate-filer li:hover,
.g5core__cate-filer li:active,
.g5core__cate-filer li.active,
.g5core-breadcrumbs,
ul.g5core__share-list li,
.porus.tparrows:hover:before,
.wp-block-button.is-style-outline .wp-block-button__link:not(.has-text-color),
.wp-block-archives li > a:hover,
.wp-block-categories li > a:hover,
.wp-block-archives .current-cat > a,
.wp-block-categories .current-cat > a,
.wp-block-latest-posts a:hover,
.wp-block-latest-comments a:hover,
.article-post .entry-footer .link-more > a:hover,
.article-post .entry-footer .meta-comment a:hover,
.article-post .entry-title a:hover,
.article-post .entry-meta a:hover,
.article-post .entry-meta .meta-author .title-meta-author,
.comment-list .comment-reply-link:hover,
.comment-list .comment-author .fn:hover,
.page-numbers:not(ul):not(.dots).next:hover:before,
.page-numbers:not(ul):not(.dots).prev:hover:before,
ul.g5blog__post-meta li:hover,
ul.g5blog__post-meta li.meta-author span,
.g5blog__post-title:hover,
.porus__blog-layout-circle .g5blog__post-grid .g5blog__post-title:hover,
.widget_rss ul a:hover,
.widget_recent_entries ul a:hover,
.widget_recent_comments ul a:hover,
.widget_meta ul a:hover,
.widget_archive ul li > a:hover,
.widget_categories ul li > a:hover,
.widget_nav_menu ul li > a:hover,
.widget_pages ul li > a:hover,
.widget_archive ul .current-cat > a,
.widget_categories ul .current-cat > a,
.widget_nav_menu ul .current-cat > a,
.widget_pages ul .current-cat > a,
.content-404-wrapper p a,
.content-404-wrapper h2 {
  color: {$accent_color};
}

.accent-bg-color,
.slick-dots li span:before,
.gel-pricing-progress.pri-progress-50 .pricing-wrap-top,
.g5core-back-to-top:focus,
.g5core-back-to-top:hover,
.g5core__paging.next-prev > a:not(.disable):hover,
.post-navigation .nav-links > div:hover,
.page-numbers:not(ul):not(.current):not(.dots):not(.next):not(.prev):hover,
.page-numbers:not(ul).current,
.page-links > .page-links-text {
  background-color: {$accent_color};
}

.accent-border-color,
p.porus-mailchimp input[type=email],
.g5core__paging.next-prev > a:not(.disable):hover,
.g5core__cate-filer > li:not(.dropdown):hover a:before,
.g5core__cate-filer > li:not(.dropdown):hover a:after,
.g5core__cate-filer > li:not(.dropdown):active a:before,
.g5core__cate-filer > li:not(.dropdown):active a:after,
.g5core__cate-filer > li:not(.dropdown).active a:before,
.g5core__cate-filer > li:not(.dropdown).active a:after,
.porus.tparrows:hover,
.wp-block-button.is-style-outline .wp-block-button__link:hover,
.post-navigation .nav-links > div:hover,
.page-numbers:not(ul):not(.dots).next:hover,
.page-numbers:not(ul):not(.dots).prev:hover,
.page-numbers:not(ul):not(.current):not(.dots):not(.next):not(.prev):hover,
.page-numbers:not(ul).current,
.page-links > .page-links-text {
  border-color: {$accent_color};
}


.accent-foreground-color,
.pricing-featured-text span,
.gel-pricing-progress.pri-progress-50 .pricing-name,
.gel-pricing-progress.pri-progress-50 .pricing-price-number,
.gel-pricing-progress.pri-progress-50 .pricing-price-currency,
.gel-pricing-progress.pri-progress-50 .pricing-price-duration,
.rtb-booking-form fieldset input,
.rtb-booking-form fieldset select,
.rtb-booking-form fieldset textarea,
.rtb-booking-form button:hover,
#rtb-date_root table.picker__table td .picker__day:hover,
#rtb-date_root .picker__footer .picker__button--close:hover:before,
div.picker--time ul.picker__list .picker--focused .picker__list-item--highlighted,
div.picker--time ul.picker__list .picker__list-item--highlighted:hover,
div.picker--time ul.picker__list .picker__list-item:hover,
div.picker--time ul.picker__list li.picker__list-item--highlighted,
.custom-booking-form .rtb-booking-form button:hover,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button:hover,
.opentable-light div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link,
.opentable-light div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button,
.contact-reservation .item-res-btn .btn:hover,
.contact-reservation-2-col.contact-reservation input[type=text],
.contact-reservation-2-col.contact-reservation input[type=email],
.contact-reservation-2-col.contact-reservation input[type=date],
.contact-reservation-2-col.contact-reservation select,
.contact-reservation-2-col.contact-reservation .item-res-btn .btn,
.contact-reservation-2-col.contact-reservation,
.contact-reservation-2-col ~ div.wpcf7-response-output,
.custom-icon-list.white-icon-color ul li i,
.g5core-back-to-top:focus,
.g5core-back-to-top:hover,
.g5core__paging.next-prev > a:not(.disable):hover,
.wp-block-button.is-style-outline .wp-block-button__link:hover,
.post-navigation .nav-links > div:hover,
.page-numbers:not(ul):not(.current):not(.dots):not(.next):not(.prev):hover,
.page-numbers:not(ul).current {
  color: {$accent_foreground_color};
}

.accent-foreground-border-color,
.btn-white-booking button:hover,
#rtb-date_root .picker__footer .picker__button--today:hover:before,
#rtb-date_root .picker__footer .picker__button--clear:hover:before,
.opentable-light div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button:hover,
.contact-reservation .item-res-btn .btn:hover,
.contact-reservation-2-col.contact-reservation .item-res-btn .btn:hover {
  border-color: {$accent_foreground_color};
}

.accent-foreground-bg-color,
.btn-white-booking button:hover,
.opentable-light div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button:hover,
.contact-reservation-2-col.contact-reservation .item-res-btn .btn:hover {
  background-color: {$accent_foreground_color};
}



.woocommerce #respond input#submit,
.woocommerce a.button,
.woocommerce button.button,
.woocommerce input.button {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}

.woocommerce #respond input#submit:focus,
.woocommerce #respond input#submit:hover,
.woocommerce #respond input#submit:active,
.woocommerce a.button:focus,
.woocommerce a.button:hover,
.woocommerce a.button:active,
.woocommerce button.button:focus,
.woocommerce button.button:hover,
.woocommerce button.button:active,
.woocommerce input.button:focus,
.woocommerce input.button:hover,
.woocommerce input.button:active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075};
  border-color: {$accent_color_darken_10};
}

.woocommerce #respond input#submit.disabled,
.woocommerce #respond input#submit:disabled,
.woocommerce #respond input#submit:disabled[disabled],
.woocommerce a.button.disabled,
.woocommerce a.button:disabled,
.woocommerce a.button:disabled[disabled],
.woocommerce button.button.disabled,
.woocommerce button.button:disabled,
.woocommerce button.button:disabled[disabled],
.woocommerce input.button.disabled,
.woocommerce input.button:disabled,
.woocommerce input.button:disabled[disabled] {
  color: {$accent_foreground_color};
  background-color: {$accent_color};
  border-color: {$accent_color};
}

.woocommerce #respond input#submit.disabled:focus,
.woocommerce #respond input#submit.disabled:hover,
.woocommerce #respond input#submit.disabled:active,
.woocommerce #respond input#submit:disabled:focus,
.woocommerce #respond input#submit:disabled:hover,
.woocommerce #respond input#submit:disabled:active,
.woocommerce #respond input#submit:disabled[disabled]:focus,
.woocommerce #respond input#submit:disabled[disabled]:hover,
.woocommerce #respond input#submit:disabled[disabled]:active,
.woocommerce a.button.disabled:focus,
.woocommerce a.button.disabled:hover,
.woocommerce a.button.disabled:active,
.woocommerce a.button:disabled:focus,
.woocommerce a.button:disabled:hover,
.woocommerce a.button:disabled:active,
.woocommerce a.button:disabled[disabled]:focus,
.woocommerce a.button:disabled[disabled]:hover,
.woocommerce a.button:disabled[disabled]:active,
.woocommerce button.button.disabled:focus,
.woocommerce button.button.disabled:hover,
.woocommerce button.button.disabled:active,
.woocommerce button.button:disabled:focus,
.woocommerce button.button:disabled:hover,
.woocommerce button.button:disabled:active,
.woocommerce button.button:disabled[disabled]:focus,
.woocommerce button.button:disabled[disabled]:hover,
.woocommerce button.button:disabled[disabled]:active,
.woocommerce input.button.disabled:focus,
.woocommerce input.button.disabled:hover,
.woocommerce input.button.disabled:active,
.woocommerce input.button:disabled:focus,
.woocommerce input.button:disabled:hover,
.woocommerce input.button:disabled:active,
.woocommerce input.button:disabled[disabled]:focus,
.woocommerce input.button:disabled[disabled]:hover,
.woocommerce input.button:disabled[disabled]:active {
  color: {$accent_foreground_color};
  background-color: {$accent_color_darken_075};
  border-color: {$accent_color_darken_10};
}


.woocommerce a.remove:hover:before,
.woocommerce .g5shop__product-item .g5shop__product-info .price,
.woocommerce ul.products li.product .price,
.g5shop__search-product .result .amount,
.g5shop__loop-product-cat:hover,
.g5shop__mini-cart ul.woocommerce-mini-cart li a:not(.remove):hover,
.g5shop__mini-cart ul.woocommerce-mini-cart li a:not(.remove):focus,
.g5shop__mini-cart ul.woocommerce-mini-cart li a:not(.remove):active,
.woocommerce .widget_layered_nav_filters ul li a:before,
.woocommerce .widget_layered_nav_filters ul li a:hover,
.woocommerce .woocommerce-widget-layered-nav ul.woocommerce-widget-layered-nav-list li.woocommerce-widget-layered-nav-list__item--chosen a,
.woocommerce .woocommerce-widget-layered-nav ul.woocommerce-widget-layered-nav-list li.woocommerce-widget-layered-nav-list__item--chosen a + .count,
.woocommerce .woocommerce-widget-layered-nav ul.woocommerce-widget-layered-nav-list a:hover,
.woocommerce .woocommerce-widget-layered-nav ul.woocommerce-widget-layered-nav-list a:hover + .count,
.woocommerce .widget_price_filter .price_slider_amount .button:hover,
.woocommerce ul.product_list_widget:not(.cart_list) li a:hover,
.woocommerce ul.product_list_widget:not(.cart_list) li a:focus,
.woocommerce ul.product_list_widget:not(.cart_list) li a:active,
.woocommerce ul.product_list_widget:not(.cart_list) li span.amount,
.widget_product_categories ul li > a:hover,
.widget_product_categories ul .current-cat > a,
.g5shop__widget-price-filter ul li > a:hover,
.g5shop__widget-product-sorting ul li > a:hover,
.g5shop__widget-price-filter ul .current-cat > a,
.g5shop__widget-product-sorting ul .current-cat > a,
.g5shop__widget-price-filter ul .current,
.g5shop__widget-product-sorting ul .current,
.g5shop__switch-layout a.active,
.g5shop__switch-layout a:hover,
.g5shop__switch-layout a:focus,
.g5shop__switch-layout a:active,
.g5shop__filter-button.active,
.g5shop__filter-button:hover,
.g5shop__filter-button:focus,
.g5shop__filter-button:active,
.g5shop__product-gallery-video:hover,
.woocommerce div.product div.summary .product_title a:hover,
.woocommerce div.product div.summary .woocommerce-product-rating a:hover,
.woocommerce div.product div.summary p.price,
.woocommerce div.product div.summary span.price,
.product_meta > span a:hover,
.woocommerce div.product form.cart table.group_table td.woocommerce-grouped-product-list-item__label a:hover,
.woocommerce div.product form.cart table.group_table td.woocommerce-grouped-product-list-item__price .amount,
.woocommerce div.product .woocommerce-tabs ul.tabs li:hover a,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active a,
.g5shop__tabs-container .nav-tabs .nav-link:hover,
.g5shop__tabs-container .nav-tabs .nav-link.active,
.g5shop__single-product-tabs.layout-3 .g5shop__tabs-container .nav-tabs .nav-link:hover,
.g5shop__single-product-tabs.layout-3 .g5shop__tabs-container .nav-tabs .nav-link.active,
.woocommerce div.product p.stock,
.woocommerce .cart-collaterals .cart_totals table.shop_table tr.order-total td,
.woocommerce table.shop_table .product-name a:hover,
.woocommerce table.shop_table .product-subtotal .amount,
.woocommerce table.shop_table .product-price .amount,
.woocommerce .woocommerce-checkout-review-order table.shop_table tbody td,
.woocommerce .woocommerce-checkout-review-order table.shop_table tfoot td,
.woocommerce .woocommerce-order-details table.shop_table tbody td,
.woocommerce .woocommerce-order-details table.shop_table tfoot td,
.woocommerce .woocommerce-form-login .lost_password a:hover,
.woocommerce-MyAccount-content > p:not(.woocommerce-info) a:hover {
  color: {$accent_color};
}

.woocommerce nav.woocommerce-pagination ul.page-numbers li span.current,
.woocommerce nav.woocommerce-pagination ul.page-numbers li a:hover,
.g5shop__product-list-actions .g5shop__quick-view,
.g5shop__product-list-actions .yith-wcwl-add-to-wishlist a,
.g5shop__product-list-actions .compare,
.g5shop__countdown-section,
.g5shop__add-to-cart:hover,
.g5shop__add-to-cart:active,
.g5shop__add-to-cart:focus,
.g5shop__quick-view:hover,
.g5shop__quick-view:active,
.g5shop__quick-view:focus,
.yith-wcwl-add-to-wishlist a:hover,
.yith-wcwl-add-to-wishlist a:active,
.yith-wcwl-add-to-wishlist a:focus,
.g5shop__product-actions .compare:hover,
.g5shop__product-actions .compare:active,
.g5shop__product-actions .compare:focus,
.woocommerce .woocommerce-widget-layered-nav ul.woocommerce-widget-layered-nav-list li.woocommerce-widget-layered-nav-list__item--chosen a:not(.layered-nav-item-color) > span,
.woocommerce .woocommerce-widget-layered-nav ul.woocommerce-widget-layered-nav-list a:not(.layered-nav-item-color):hover > span,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce div.product div.images .woocommerce-product-gallery__trigger:hover:after,
.g5shop__tabs-container .nav-tabs .nav-link:before {
  background-color: {$accent_color};
}

.woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
.woocommerce div.product div.images .flex-control-thumbs li img.flex-active,
.woocommerce div.product div.images .flex-control-thumbs li img:hover,
.woocommerce div.product div.images .woocommerce-product-gallery__trigger:hover:before,
.woocommerce div.product .woocommerce-tabs ul.tabs li:hover,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
body.no-sidebar .g5shop__single-product-summary .woocommerce-error,
body.no-sidebar .g5shop__single-product-summary .woocommerce-info,
body.no-sidebar .g5shop__single-product-summary .woocommerce-message {
  border-color: {$accent_color};
}

.g5shop__product-list-actions .g5shop__quick-view,
.g5shop__product-list-actions .yith-wcwl-add-to-wishlist a,
.g5shop__product-list-actions .compare,
.g5shop__add-to-cart:hover,
.g5shop__add-to-cart:active,
.g5shop__add-to-cart:focus,
.g5shop__quick-view:hover,
.g5shop__quick-view:active,
.g5shop__quick-view:focus,
.yith-wcwl-add-to-wishlist a:hover,
.yith-wcwl-add-to-wishlist a:active,
.yith-wcwl-add-to-wishlist a:focus {
  color: {$accent_foreground_color};
}


CSS;

}


function porus_custom_border_color() {
	$border_color = G5CORE()->options()->color()->get_option( 'border_color' );
return <<<CSS
.gel-pricing-progress .pricing-features:after {
  background-color: {$border_color};
}

.gel-pricing-line .pricing-features:before {
  background-color: {$border_color};
}

.border-color,
hr,
blockquote,
table th,
table td,
table thead th,
.gel-pricing-progress,
.gel-pricing-line,
div.picker--time ul.picker__list .picker__list-item,
.site-header .search-form-wrapper .search-form,
.site-header .site-navigation,
.g5core__paging.next-prev > a,
.wp-block-table th,
.wp-block-table td,
.wp-block-quote:not(.is-large):not(.is-style-large),
ul.wp-block-latest-posts.is-grid li,
.post-navigation .nav-links > div,
.page-links > .page-links-text,
.page-links > a,
.widget_calendar caption {
  border-color: {$border_color};
}

@media (max-width: 767px) {
  .g5shop__tab-panel {
    border: 1px solid {$border_color};
  }
}

.g5shop__single-product-tabs.layout-3 .g5shop__tabs-container .nav-tabs .nav-link {
  border-color: {$border_color} {$border_color} {$border_color};
}
.g5shop__single-product-tabs.layout-3 .g5shop__tabs-container .nav-tabs .nav-link.active {
  border-color: {$border_color} #fff {$border_color} {$border_color};
}

@media (max-width: 768px) {
  .woocommerce table.shop_table_responsive.shop_table.cart {
    border: 1px solid {$border_color};
  }
}

.g5shop__shop-toolbar.stretched .g5shop__shop-toolbar-inner,
.g5shop__shop-toolbar.stretched_content .g5shop__shop-toolbar-inner,
.g5shop__layout-list .g5shop__product-item-inner,
.g5shop__layout-list .g5shop__product-cat-item-inner,
.woocommerce div.product div.summary .product_meta,
.g5shop__panel-heading h4,
.g5shop__single-product-tabs.layout-3 .g5shop__panels-container,
.g5shop__single-product-tabs.layout-4 .g5shop__tab-panel,
.woocommerce table.shop_table thead th,
.woocommerce table.shop_table td,
.woocommerce form.checkout_coupon,
.woocommerce form.login,
.woocommerce form.register,
.woocommerce-MyAccount-navigation ul,
.woocommerce-MyAccount-navigation ul li,
.woocommerce-MyAccount-content address,
.woocommerce-MyAccount-content fieldset {
  border-color: {$border_color};
}
CSS;


}

function porus_custom_heading_color() {
	$heading_color = G5CORE()->options()->color()->get_option( 'heading_color' );

	return <<<CSS
	
.content-404-wrapper .search-form .search-field:-moz-placeholder, .search-form-404 .search-form .search-field:-moz-placeholder {
  color: {$heading_color};
}
.content-404-wrapper .search-form .search-field::-moz-placeholder, .search-form-404 .search-form .search-field::-moz-placeholder {
  color: {$heading_color};
}
.content-404-wrapper .search-form .search-field:-ms-input-placeholder, .search-form-404 .search-form .search-field:-ms-input-placeholder {
  color: {$heading_color};
}
.content-404-wrapper .search-form .search-field::-webkit-input-placeholder, .search-form-404 .search-form .search-field::-webkit-input-placeholder {
  color: {$heading_color};
}	
	
	
.heading-color,
h1,
h2,
h3,
h4,
h5,
h6,
.h1,
.h2,
.h3,
.h4,
.h5,
.h6,
.gel-testimonial .gel-testimonial-job,
.gel-countdown-value,
.rtb-booking-form fieldset select#rtb-party option,
.custom-booking-form .rtb-booking-form fieldset label,
.custom-booking-form .rtb-booking-form .add-message a,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link:before,
.contact-reservation .column > .item-res .title,
.contact-reservation-2-col.contact-reservation input[type=text] option,
.contact-reservation-2-col.contact-reservation input[type=email] option,
.contact-reservation-2-col.contact-reservation input[type=date] option,
.contact-reservation-2-col.contact-reservation select option,
.site-header .search-form-wrapper > span > i:before,
.site-branding-text .site-title a,
.page-main-title,
.has-drop-cap:not(:focus):first-letter,
.article-post .entry-footer .link-more > a,
.comment-form .logged-in-as a:hover,
.post-navigation .nav-links .nav-subtitle,
.search-form button:before {
  color: {$heading_color};
}

.g5shop__quantity .g5shop__quantity-inner .btn-number,
.woocommerce table.wishlist_table .product-stock-status span.wishlist-in-stock,
.wishlist_table.mobile li .item-details table.item-details-table td.value,
.wishlist_table.mobile li table.additional-info td.value,
.g5shop__swatch-text .g5shop__swatches-item.g5shop__sw-selected,
.g5shop__swatch-text .g5shop__swatches-item:hover,
.g5shop__reset_variations:hover,
.woocommerce .widget_price_filter .price_slider_amount .button,
.woocommerce-product-search button:before,
.woocommerce div.product form.cart table.variations .reset_variations:hover,
.woocommerce .cart-collaterals .cart_totals table.shop_table tr td,
.woocommerce .cart-collaterals .cart_totals table.shop_table tr.order-total th,
.woocommerce-MyAccount-content fieldset legend,
.woocommerce-Address-title > h3 {
  color: {$heading_color};
}

CSS;

}

function porus_custom_caption_color() {
	$caption_color = G5CORE()->options()->color()->get_option( 'caption_color' );

	return <<<CSS

.caption-color,
.g5core__paging.next-prev > a,
.blocks-gallery-caption {
  color: {$caption_color};
}

.g5shop__swatch-text .g5shop__swatches-item,
.g5shop__reset_variations {
  color: {$caption_color};
}
	
CSS;

}

function porus_custom_placeholder_color() {
	$placeholder_color = G5CORE()->options()->color()->get_option( 'placeholder_color' );

	return <<<CSS
textarea:-moz-placeholder,
select:-moz-placeholder,
input[type]:-moz-placeholder {
  color: {$placeholder_color};
}
textarea::-moz-placeholder,
select::-moz-placeholder,
input[type]::-moz-placeholder {
  color: {$placeholder_color};
}
textarea:-ms-input-placeholder,
select:-ms-input-placeholder,
input[type]:-ms-input-placeholder {
  color: {$placeholder_color};
}
textarea::-webkit-input-placeholder,
select::-webkit-input-placeholder,
input[type]::-webkit-input-placeholder {
  color: {$placeholder_color};
}
	
CSS;

}

function porus_custom_primary_color() {
	$primary_color            = G5CORE()->options()->color()->get_option( 'primary_color' );
	$primary_color_foreground = g5core_color_contrast( $primary_color );
	$primary_color_darken_075 = g5core_color_darken( $primary_color, '7.5%' );
	$primary_color_darken_10  = g5core_color_darken( $primary_color, '10%' );

	return <<<CSS
select {
  background-image: linear-gradient(45deg, transparent 50%, {$primary_color} 50%), linear-gradient(135deg, {$primary_color} 50%, transparent 50%);
}

.btn.btn-primary {
  color: {$primary_color_foreground};
  background-color: {$primary_color};
  border-color: {$primary_color};
}
.btn.btn-primary:focus, .btn.btn-primary:hover, .btn.btn-primary:active {
  color: {$primary_color_foreground};
  background-color: {$primary_color_darken_075};
  border-color: {$primary_color_darken_10};
}
.btn.btn-primary.btn-outline {
  color: {$primary_color};
}
.btn.btn-primary.btn-outline:focus, .btn.btn-primary.btn-outline:hover, .btn.btn-primary.btn-outline:active {
  background-color: {$primary_color};
  color: {$primary_color_foreground};
  border-color: {$primary_color};
}
.btn.btn-primary.btn-link {
  color: {$primary_color};
}

@media (min-width: 768px) {
  .wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column .vc_tta-panels-container {
    border-left: 1px dashed {$primary_color};
  }
}

.primary-color,
.rtb-booking-form fieldset label,
.btn-white-booking button:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02 .vc_tta-tab > a:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02 .vc_tta-tab > a:focus,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02 .vc_tta-tab.vc_active > a,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column .vc_tta-tabs-container ul.vc_tta-tabs-list li.vc_active a,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column .vc_tta-tabs-container ul.vc_tta-tabs-list li > a:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column .vc_tta-tabs-container ul.vc_tta-tabs-list li > a:focus,
.opentable-light div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link:before,
.opentable-light div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link:after,
.opentable-light div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button:hover,
.contact-reservation-2-col.contact-reservation .column .title,
.contact-reservation-2-col.contact-reservation .item-res-btn .btn:hover,
.custom-header-03 .toggle-icon,
ul.g5core__share-list li:hover,
.porus.tparrows:before,
.x-mega-sub-menu .gel-list .gel-list-item:hover,
.article-post .post-tags a:hover,
.page-numbers:not(ul):not(.dots).prev:before,
.page-numbers:not(ul):not(.dots).next:before,
.g5blog__single-meta-tag a:hover,
.search-form input:focus + button:before,
.tagcloud a:hover {
  color: {$primary_color};
}

.primary-border-color,
input[type=text],
input[type=email],
input[type=url],
input[type=password],
input[type=search],
input[type=number],
input[type=tel],
input[type=range],
input[type=date],
input[type=month],
input[type=week],
input[type=time],
input[type=datetime],
input[type=datetime-local],
input[type=color],
textarea,
select,
.btn.btn-secondary.btn-outline,
.g5core-menu-popup-wrapper .mfp-close,
.rtb-booking-form fieldset input,
.rtb-booking-form fieldset select,
.rtb-booking-form fieldset textarea,
.rtb-booking-form button,
#rtb-date_root table.picker__table .picker__day--highlighted,
#rtb-date_root .picker__footer .picker__button--clear,
#rtb-date_root .picker__footer .picker__button--close,
#rtb-date_root .picker__footer .picker__button--today,
div.picker--time .picker__button--clear,
div.picker--time ul.picker__list .picker__list-item--highlighted,
div.picker--time ul.picker__list .picker__list-item:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-01 .vc_tta-tab > a,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02 .vc_tta-tabs-list,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column .vc_tta-tabs-container ul.vc_tta-tabs-list li.vc_active:before,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button,
.contact-reservation input[type=text],
.contact-reservation input[type=email],
.contact-reservation input[type=date],
.contact-reservation select,
.contact-reservation .item-res-btn .btn,
.porus.tparrows,
.page-numbers:not(ul):not(.dots).prev,
.page-numbers:not(ul):not(.dots).next,
.content-404-wrapper .search-form .search-field,
.search-form-404 .search-form .search-field {
  border-color: {$primary_color};
}

.primary-bg-color,
.gel-pricing-progress .pricing-features:before,
.rtb-booking-form fieldset select#rtb-party option:hover,
#rtb-date_root table.picker__table .picker--focused .picker__day--highlighted,
#rtb-date_root table.picker__table .picker__day--highlighted:hover,
#rtb-date_root table.picker__table .picker__day--infocus:hover,
#rtb-date_root table.picker__table .picker__day--outfocus:hover,
#rtb-date_root table.picker__table .picker__day--highlighted,
div.picker--time ul.picker__list .picker--focused .picker__list-item--highlighted,
div.picker--time ul.picker__list .picker__list-item--highlighted:hover,
div.picker--time ul.picker__list .picker__list-item:hover,
div.picker--time ul.picker__list li.picker__list-item--highlighted,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02 .vc_tta-tab > a .vc_tta-title-text:before {
  background-color: {$primary_color};
}

.select2-container--default.select2-container--default .select2-selection--single .select2-selection__arrow b {
  border-color: {$primary_color} transparent transparent transparent;
}

.select2-container--default.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
  border-color: transparent transparent {$primary_color} transparent;
}

#g5shop__popup-product-quick-view button.mfp-close:hover {
  border: 2px dotted {$primary_color} !important;
}

.woocommerce-loop-category__title:hover,
.g5shop__loop-product-title:hover,
.woocommerce ul.products li.product .woocommerce-loop-category__title:hover,
.woocommerce ul.products li.product .woocommerce-loop-product__title:hover,
.g5shop__product-flash,
.select2-container--default.select2-container--default .select2-selection--single .select2-selection__clear,
.g5shop__search-product .result a:hover,
.woocommerce-product-search input:focus + button:before {
  color: {$primary_color};
}

.select2-container--default.select2-container--default .select2-selection--single,
.select2-container--default.select2-container--default .select2-search--dropdown .select2-search__field,
.select2-dropdown.select2-dropdown,
.woocommerce .quantity .qty,
#g5shop__popup-product-quick-view button.mfp-close,
.woocommerce table.shop_table.cart td.actions .coupon .input-text {
  border-color: {$primary_color};
}

.woocommerce span.onsale,
.g5shop_header-action-icon a > span {
  background-color: {$primary_color};
}

CSS;

}

function porus_custom_secondary_color() {
	$secondary_color = G5CORE()->options()->color()->get_option('secondary_color');
	$secondary_color_foreground = g5core_color_contrast($secondary_color);
	$secondary_color_darken_075 = g5core_color_darken($secondary_color, '7.5%');
	$secondary_color_darken_10 = g5core_color_darken($secondary_color, '10%');
	return <<<CSS
	.btn.btn-secondary {
  color: {$secondary_color_foreground};
  background-color: {$secondary_color};
  border-color: {$secondary_color};
}
.btn.btn-secondary:focus, .btn.btn-secondary:hover, .btn.btn-secondary:active {
  color: {$secondary_color_foreground};
  background-color: {$secondary_color_darken_075};
  border-color: {$secondary_color_darken_10};
}
.btn.btn-secondary.btn-outline {
  color: {$secondary_color};
}
.btn.btn-secondary.btn-outline:focus, .btn.btn-secondary.btn-outline:hover, .btn.btn-secondary.btn-outline:active {
  background-color: {$secondary_color};
  color: {$secondary_color_foreground};
  border-color: {$secondary_color};
}
.btn.btn-secondary.btn-link {
  color: {$secondary_color};
}

#rtb-date_root .picker__footer .picker__button--clear,
#rtb-date_root .picker__footer .picker__button--close,
#rtb-date_root .picker__footer .picker__button--today {
  color: {$secondary_color} !important;
}

.secondary-color,
.gel-image-box-style-01 h4.title,
.gel-image-box-style-02 h4.title,
.gel-image-box-style-03 h4.title,
.gel-heading-subtitle,
#rtb-date_root .picker__footer .picker__button--close:before,
div.picker--time .picker__button--clear,
.custom-booking-form .rtb-booking-form button,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02.vc_tta-tabs .vc_tta-panels-container .gel-image-box .title,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column.vc_tta-tabs .vc_tta-panels-container .gel-image-box .title,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02.vc_tta-tabs .vc_tta-panels-container .gel-image-box .title-short-desc,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-02.vc_tta-tabs .vc_tta-panels-container .gel-image-box .price-short-desc,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column.vc_tta-tabs .vc_tta-panels-container .gel-image-box .title-short-desc,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-column.vc_tta-tabs .vc_tta-panels-container .gel-image-box .price-short-desc,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button,
.contact-reservation .item-res-btn .btn,
.g5core__cate-filer li,
.wp-block-archives li > a,
.wp-block-categories li > a,
.wp-block-archives .cat-count,
.article-post .post-tags a,
.comment-list .comment-reply-link,
.comment-list .comment-author .fn,
.g5blog__single-meta-tag a,
.porus__blog-layout-circle .g5blog__post-grid .g5blog__post-title,
.widget_meta ul li a,
.widget_recent_entries ul li a,
.widget_archive ul li > a,
.widget_categories ul li > a,
.widget_nav_menu ul li > a,
.widget_pages ul li > a,
.tagcloud a,
.content-404-wrapper h4 {
  color: {$secondary_color};
}

.secondary-border-color,
.slick-dots li.slick-active span:before,
.rtb-booking-form button:hover,
#rtb-date_root .picker__footer .picker__button--clear:hover,
#rtb-date_root .picker__footer .picker__button--close:hover,
#rtb-date_root .picker__footer .picker__button--today:hover,
#rtb-date_root .picker__footer .picker__button--today:before,
#rtb-date_root .picker__footer .picker__button--clear:before,
div.picker--time .picker__button--clear:focus,
div.picker--time .picker__button--clear:hover,
div.picker--time .picker__button--clear:before,
.custom-booking-form .rtb-booking-form button:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-01 .vc_tta-tab > a:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-01 .vc_tta-tab.vc_active > a,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button:hover,
.g5core__cate-filer > li:not(.dropdown) > a:before,
.g5core__cate-filer > li:not(.dropdown) > a:after {
  border-color: {$secondary_color};
}

.secondary-bg-color,
.rtb-booking-form button:hover,
#rtb-date_root .picker__footer .picker__button--clear:hover,
#rtb-date_root .picker__footer .picker__button--close:hover,
#rtb-date_root .picker__footer .picker__button--today:hover,
div.picker--time .picker__button--clear:focus,
div.picker--time .picker__button--clear:hover,
.custom-booking-form .rtb-booking-form button:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-01 .vc_tta-tab > a:hover,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general.custom-tab-01 .vc_tta-tab.vc_active > a,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button:hover,
.contact-reservation .item-res-btn .btn:hover,
.wp-block-categories__post-count,
.cat-count {
  background-color: {$secondary_color};
}

.woocommerce-loop-category__title,
.g5shop__loop-product-title,
.woocommerce ul.products li.product .woocommerce-loop-category__title,
.woocommerce ul.products li.product .woocommerce-loop-product__title,
.g5shop__quantity label,
.g5shop__search-product .result a,
.g5shop__loop-product_excerpt h1,
.g5shop__loop-product_excerpt h2,
.g5shop__loop-product_excerpt h3,
.g5shop__loop-product_excerpt h4,
.g5shop__loop-product_excerpt h5,
.g5shop__loop-product_excerpt h6,
.woocommerce-variation-description h1,
.woocommerce-variation-description h2,
.woocommerce-variation-description h3,
.woocommerce-variation-description h4,
.woocommerce-variation-description h5,
.woocommerce-variation-description h6,
.woocommerce-product-details__short-description h1,
.woocommerce-product-details__short-description h2,
.woocommerce-product-details__short-description h3,
.woocommerce-product-details__short-description h4,
.woocommerce-product-details__short-description h5,
.woocommerce-product-details__short-description h6,
.g5shop__deal-heading,
.woocommerce .woocommerce-widget-layered-nav ul.woocommerce-widget-layered-nav-list,
.woocommerce ul.product_list_widget:not(.cart_list) li a,
.widget_product_categories ul li > a,
.g5shop__widget-price-filter ul li > a,
.g5shop__widget-product-sorting ul li > a,
.g5shop__switch-layout a,
.woocommerce .g5shop__archive-filter-content .widget_archive ul li .count,
.woocommerce .g5shop__archive-filter-content .widget_categories ul li .count,
.woocommerce .g5shop__archive-filter-content .widget_nav_menu ul li .count,
.woocommerce .g5shop__archive-filter-content .widget_pages ul li .count,
.woocommerce .g5shop__archive-filter-content .widget_product_categories ul li .count,
.woocommerce div.product div.summary .product_title,
.product_meta > span label,
.g5core__social-share.product .g5core__share-label,
.woocommerce div.product form.cart table.variations td.label label,
.woocommerce div.product form.cart table.group_table td.woocommerce-grouped-product-list-item__label a,
.woocommerce div.product .woocommerce-tabs ul.tabs li a,
.g5shop__panel-heading h4,
.g5shop__tabs-container .nav-tabs .nav-link,
.woocommerce #reviews #comments ol.commentlist li .comment-text p.meta .woocommerce-review__author,
.woocommerce #reviews #review_form_wrapper .comment-reply-title,
.woocommerce table.shop_table thead th,
.woocommerce table.shop_table tfoot th,
.woocommerce table.shop_table .product-name a,
.woocommerce-billing-fields > h3,
.woocommerce-additional-fields > h3,
.woocommerce-shipping-fields > h3,
#order_review_heading,
.woocommerce .woocommerce-checkout-review-order table.shop_table tfoot tr.cart-subtotal td,
.woocommerce-checkout #payment ul.payment_methods label,
.woocommerce-column__title,
.woocommerce-order-details__title,
.woocommerce .woocommerce-order-details table.shop_table tfoot tr:first-child td,
div.woocommerce form.woocommerce-form-track-order p.form-row label,
.woocommerce-MyAccount-navigation ul li > a {
  color: {$secondary_color};
}


.g5shop__product-list-actions .g5shop__quick-view:hover,
.g5shop__product-list-actions .g5shop__quick-view:active,
.g5shop__product-list-actions .g5shop__quick-view:focus,
.g5shop__product-list-actions .yith-wcwl-add-to-wishlist a:hover,
.g5shop__product-list-actions .yith-wcwl-add-to-wishlist a:active,
.g5shop__product-list-actions .yith-wcwl-add-to-wishlist a:focus,
.g5shop__product-list-actions .compare:hover,
.g5shop__product-list-actions .compare:active,
.g5shop__product-list-actions .compare:focus,
.g5shop__add-to-cart,
.g5shop__quick-view,
.yith-wcwl-add-to-wishlist a,
.g5shop__product-actions .compare,
.widget_product_categories ul span.count,
div.woocommerce form.woocommerce-form-track-order p.form-row button {
  background-color: {$secondary_color};
}
CSS;

}

function porus_custom_dark_color() {
	$dark_color = G5CORE()->options()->color()->get_option('dark_color');
	$dark_color_foreground = g5core_color_contrast($dark_color);
	$dark_color_darken_075 = g5core_color_darken($dark_color, '7.5%');
	$dark_color_darken_10 = g5core_color_darken($dark_color, '10%');
	return <<<CSS

.btn.btn-dark {
  color: {$dark_color_foreground};
  background-color: {$dark_color};
  border-color: {$dark_color};
}
.btn.btn-dark:focus, .btn.btn-dark:hover, .btn.btn-dark:active {
  color: {$dark_color_foreground};
  background-color: {$dark_color_darken_075};
  border-color: {$dark_color_darken_10};
}
.btn.btn-dark.btn-outline {
  color: {$dark_color};
}
.btn.btn-dark.btn-outline:focus, .btn.btn-dark.btn-outline:hover, .btn.btn-dark.btn-outline:active {
  background-color: {$dark_color};
  color: {$dark_color_foreground};
  border-color: {$dark_color};
}
.btn.btn-dark.btn-link {
  color: {$dark_color};
}

.dark-color,
.gel-pricing-progress .pricing-feature,
.gel-pricing-style-1 .pricing-price-number,
.gel-pricing-style-1 .pricing-price-currency,
.gel-pricing-style-2 .pricing-price-number,
.gel-pricing-style-2 .pricing-price-currency,
.gel-pricing-style-3 .pricing-price-number,
.gel-pricing-style-3 .pricing-price-currency,
.gel-pricing-style-3 .pricing-name,
.gel-pricing-style-5 .pricing-price-number,
.gel-pricing-style-5 .pricing-price-currency {
  color: {$dark_color};
}


CSS;

}

function porus_custom_light_color() {
	$light_color = G5CORE()->options()->color()->get_option('light_color');
	$light_color_foreground = g5core_color_contrast($light_color);
	$light_color_darken_075 = g5core_color_darken($light_color, '7.5%');
	$light_color_darken_10 = g5core_color_darken($light_color, '10%');
	return <<<CSS
.btn.btn-light {
  color: {$light_color_foreground};
  background-color: {$light_color};
  border-color: {$light_color};
}
.btn.btn-light:focus, .btn.btn-light:hover, .btn.btn-light:active {
  color: {$light_color_foreground};
  background-color: {$light_color_darken_075};
  border-color: {$light_color_darken_10};
}
.btn.btn-light.btn-outline {
  color: {$light_color};
}
.btn.btn-light.btn-outline:focus, .btn.btn-light.btn-outline:hover, .btn.btn-light.btn-outline:active {
  background-color: {$light_color};
  color: {$light_color_foreground};
  border-color: {$light_color};
}
.btn.btn-light.btn-link {
  color: {$light_color};
}
.gel-pricing-style-3 {
  background-color: {$light_color};
}
CSS;

}

function porus_custom_body_font() {
	$font = g5core_process_font(G5CORE()->options()->typography()->get_option('body_font'));
	return <<<CSS
.font-body,
body,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general .vc_tta-tab > a,
.wpb-js-composer.wpb-js-composer .vc_tta.vc_general .vc_tta-panel-title > a,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-selector a.ot-dtp-picker-selector-link,
div.ot-dtp-picker.wide .ot-dtp-picker-form .ot-dtp-picker-button,
.x-mega-sub-menu .gel-heading-title {
  font-family: {$font['font_family']};
}
CSS;

}

function porus_custom_primary_font() {
	$font = g5core_process_font(G5CORE()->options()->typography()->get_option('primary_font'));
	return <<<CSS
.font-primary,
.gel-testimonial .author-attr h4,
.gel-heading-title,
.pricing-price-duration,
.page-main-title,
.g5blog__block-title {
  font-family: {$font['font_family']};
}


.woocommerce .products.related > h2,
.woocommerce .products.upsells > h2,
.woocommerce .cart-collaterals .cross-sells > h2 {
  font-family: {$font['font_family']};
}

CSS;

}
<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Shop_Assets')) {
    class G5Shop_Assets
    {
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init()
        {
            add_action('init', array($this, 'register_assets'));
            add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));

            add_action('wp_enqueue_scripts',array($this,'dequeue_assets'),40);
        }

        public function register_assets()
        {
            wp_register_script(G5SHOP()->assets_handle('swatches'), G5SHOP()->asset_url('assets/js/swatches.min.js'), array('jquery'), G5SHOP()->plugin_ver(), true);

            wp_register_script(G5SHOP()->assets_handle('frontend'), G5SHOP()->asset_url('assets/js/frontend.min.js'), array('jquery'), G5SHOP()->plugin_ver(), true);
            wp_register_style(G5SHOP()->assets_handle('frontend'), G5SHOP()->asset_url('assets/scss/frontend.min.css'), array(), G5SHOP()->plugin_ver());

        }

        public function dequeue_assets() {
            wp_dequeue_style(G5CORE_CURRENT_THEME . '-woocommerce-default');
        }

        public function enqueue_assets()
        {



            $product_quick_view = G5SHOP()->options()->get_option('product_quick_view_enable');
            if ($product_quick_view === 'on') {
                wp_enqueue_script('wc-add-to-cart-variation');
                if (version_compare(WC()->version, '3.0.0', '>=')) {
                    if (current_theme_supports('wc-product-gallery-zoom')) {
                        wp_enqueue_script('zoom');
                    }
                    if (current_theme_supports('wc-product-gallery-lightbox')) {
                        wp_enqueue_script('photoswipe-ui-default');
                        wp_enqueue_style('photoswipe-default-skin');
                        if (has_action('wp_footer', 'woocommerce_photoswipe') === FALSE) {
                            add_action('wp_footer', 'woocommerce_photoswipe', 15);
                        }
                    }
                    wp_enqueue_script('flexslider');
                    wp_enqueue_script('wc-single-product');
                }
            }


            wp_enqueue_script('selectWoo');
            wp_enqueue_style('select2');


            wp_enqueue_style(G5SHOP()->assets_handle('frontend'));
            wp_enqueue_script(G5SHOP()->assets_handle('frontend'));


            wp_localize_script(G5SHOP()->assets_handle('frontend'), 'g5shop_vars', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'home_url' => home_url('/'),
                'shop_url' => wc_get_page_permalink('shop'),
                'product_selector' => apply_filters('g5shop_product_selector', '.g5shop__product-item'),
                'price_selector' => apply_filters('g5shop_price_selector', '.price'),
                'localization' => array(
                    'quantity_minimum_alert' => esc_html__('Sorry, the minimum value was reached', 'g5-shop'),
                    'quantity_maximum_alert' => esc_html__('Sorry, the maximum value was reached', 'g5-shop'),
                    'add_to_cart_text' => esc_html__('Add to cart', 'g5-shop'),
                    'read_more_text' => esc_html__('Read more', 'g5-shop'),
                    'select_options_text' => esc_html__('Select options', 'g5-shop'),
                    'all_category' => esc_html__('All Category', 'g5-shop')
                ),
                'single_product_gallery_horizontal_args' => apply_filters('g5shop_single_product_gallery_horizontal_args', array(
                    'arrows' => true,
                    'adaptiveHeight' => true,
                    'infinite' => false,
                    'slidesToShow' => 4,
                    'slidesToScroll' => 4,
                    'prevArrow' => '<div class="slick-prev" aria-label="Previous"><i class="fas fa-chevron-left"></i></div>',
                    'nextArrow' => '<div class="slick-next" aria-label="Next"><i class="fas fa-chevron-right"></i></div>',
                    'responsive' => array(
                        array(
                            'breakpoint' => 1200,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'slidesToScroll' => 4,
                            )
                        ),
                        array(
                            'breakpoint' => 992,
                            'settings' => array(
                                'slidesToShow' => 3,
                                'slidesToScroll' => 3,
                            )
                        ),
                        array(
                            'breakpoint' => 768,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'slidesToScroll' => 4,
                            )
                        ),
                        array(
                            'breakpoint' => 576,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'slidesToScroll' => 4,
                            )
                        ),
                        array(
                            'breakpoint' => 320,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'slidesToScroll' => 4,
                            )
                        ),
                    )
                )),
                'single_product_gallery_vertical_args' => apply_filters('g5shop_single_product_gallery_vertical_args', array(
                    'arrows' => true,
                    'infinite' => false,
                    'slidesToShow' => 4,
                    'slidesToScroll' => 4,
                    'vertical' => true,
                    'verticalSwiping' => true,
                    'swipeToSlide' => true,
                    'prevArrow' => '<div class="slick-prev" aria-label="Previous"><i class="fas fa-chevron-up"></i></div>',
                    'nextArrow' => '<div class="slick-next" aria-label="Next"><i class="fas fa-chevron-down"></i></div>',
                    'responsive' => array(
                        array(
                            'breakpoint' => 1200,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'vertical' => false,
                                'verticalSwiping' => false
                            )
                        ),
                        array(
                            'breakpoint' => 992,
                            'settings' => array(
                                'slidesToShow' => 3,
                                'vertical' => false,
                                'verticalSwiping' => false
                            )
                        ),
                        array(
                            'breakpoint' => 768,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'vertical' => false,
                                'verticalSwiping' => false
                            )
                        ),
                        array(
                            'breakpoint' => 576,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'vertical' => false,
                                'verticalSwiping' => false
                            )
                        ),
                        array(
                            'breakpoint' => 320,
                            'settings' => array(
                                'slidesToShow' => 4,
                                'vertical' => false,
                                'verticalSwiping' => false
                            )
                        ),
                    )
                )),
            ));
            $swatches_enable = G5SHOP()->options()->get_option('swatches_enable');
            if ($swatches_enable === 'on') {
                wp_enqueue_script(G5SHOP()->assets_handle('swatches'));
            }

        }


    }
}
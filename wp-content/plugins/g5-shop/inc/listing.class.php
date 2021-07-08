<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if ( ! class_exists( 'G5Core_Listing_Abstract', false ) ) {
    G5CORE()->load_file(G5CORE()->plugin_dir('inc/abstract/listing.class.php'));
}
if (!class_exists('G5Shop_Listing')) {
    class G5Shop_Listing extends G5Core_Listing_Abstract
    {
        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        protected $key_layout_settings = 'g5shop_layout_settings';

        public function init() {
            add_action('g5core_product_pagination_ajax_response',array($this,'pagination_ajax_response'),10,2);
        }

        public function pagination_ajax_response($settings,$query_args) {
            $this->render_content($query_args,$settings);
        }

        public function get_layout_settings_default() {
            return array(
                'post_layout'            =>  G5SHOP()->options()->get_option('post_layout'),
                'item_skin'            =>  G5SHOP()->options()->get_option('item_skin'),
                'item_custom_class' =>  G5SHOP()->options()->get_option('item_custom_class'),
                'post_columns' => array(
                    'xl' => intval(G5SHOP()->options()->get_option('post_columns_xl')),
                    'lg' => intval(G5SHOP()->options()->get_option('post_columns_lg')),
                    'md' => intval(G5SHOP()->options()->get_option('post_columns_md')),
                    'sm' => intval(G5SHOP()->options()->get_option('post_columns_sm')),
                    '' => intval(G5SHOP()->options()->get_option('post_columns')),
                ),
                'columns_gutter'    => intval(G5SHOP()->options()->get_option('post_columns_gutter')),
                'post_paging'            => G5SHOP()->options()->get_option('post_paging'),
                'post_animation' => G5SHOP()->options()->get_option('post_animation'),
                'itemSelector'           => 'article',
                'cate_filter_enable' => false,
                'append_tabs' => G5SHOP()->options()->get_option('append_tabs'),
                'post_type' => 'product',
                'taxonomy' => 'product_cat'
            );
        }

        public function render_listing() {
            $settings = $this->get_layout_settings();
            if (G5CORE()->query()->have_posts()
                || (isset($settings['isMainQuery']) && 'products' !== woocommerce_get_loop_display_mode())) {
                if (isset($settings['isMainQuery'])) {
                    add_action('g5core_before_listing_wrapper','g5shop_shop_toolbar',5);
                    do_action( 'woocommerce_before_shop_loop' );
                }

                woocommerce_product_loop_start();

                G5SHOP()->get_template( 'listing.php' );

                woocommerce_product_loop_end();
            } elseif (isset($settings['isMainQuery'])) {
                /**
                 * Hook: woocommerce_no_products_found.
                 *
                 * @hooked wc_no_products_found - 10
                 */
                do_action( 'woocommerce_no_products_found' );
            }

            if (isset($settings['isMainQuery'])) {
                remove_action('g5core_before_listing_wrapper','g5shop_shop_toolbar',5);
            }
        }

        public function get_config_layout_matrix() {
            $post_settings = $this->get_layout_settings();
            $item_skin = isset($post_settings['item_skin']) ? $post_settings['item_skin'] : '';
            $data = apply_filters('g5shop_config_layout_matrix',array(
                'grid' => array(
                    'layout' => array(
                        array('template' => $item_skin)
                    ),
                ),
                'list' => array(
                    'layout' => array(
                        array('template' => 'list')
                    ),
                ),
                'block-1' => array('itemSelector' => '.g5core__listing-blocks'),
            ));
            return $data;
        }
    }
}
<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5ThemeAddons_Shop')) {
    class G5ThemeAddons_Shop {
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

            add_filter( 'g5core_default_options_g5shop_options', array($this, 'change_default_options_g5shop_options'));

            add_action('wp_footer',array($this,'print_svg'));

            add_filter('g5shop_sale_flash', array($this,'change_sale_flash') , 10, 3);

            add_action('template_redirect', array($this,'change_loop_cat_position'),50);

            add_action('template_redirect', array($this,'change_shop_single_layout'),50);

            add_action('woocommerce_before_single_product_summary', array($this,'single_product_image_wrap_open') , 3);

            add_action('woocommerce_before_single_product_summary', array($this,'single_product_image_wrap_close') , 10000);

            add_filter('g5shop_single_product_gallery_horizontal_args', array($this,'change_single_product_gallery_horizontal_args'));

            add_filter('g5shop_single_related_layout_setting',array($this,'change_single_related_layout_setting'));

            add_filter('g5shop_single_up_sells_layout_setting',array($this,'change_single_up_sells_layout_setting'));

            add_filter('g5shop_single_cross_sells_layout_setting',array($this,'change_single_cross_sells_layout_setting'));

            add_action( 'template_redirect', array( $this, 'demo_layout' ), 20 );
            add_action( 'pre_get_posts', array( $this, 'demo_post_per_pages' ), 15 );

	        //remove_action('woocommerce_quick_view_product_summary', 'woocommerce_template_single_add_to_cart', 30);
	        //remove_action('woocommerce_quick_view_product_summary', 'woocommerce_template_single_sharing', 50);

	        //add_action('woocommerce_quick_view_product_summary', 'woocommerce_template_single_sharing', 30);
	        //add_action('woocommerce_quick_view_product_summary', 'woocommerce_template_single_add_to_cart', 50);

	        remove_action('woocommerce_shop_loop_item_title', 'g5shop_template_loop_cat', 10);
            add_action('woocommerce_shop_loop_item_title', 'g5shop_template_loop_cat', 25);

        }


        public function change_default_options_g5shop_options($defaults) {
            return wp_parse_args( array(
                'shop_toolbar' =>
                    array (
                        'top' =>
                            array (
                            ),
                        'left' =>
                            array (
                                'result_count' => 'Result Count',
                            ),
                        'right' =>
                            array (
                                'ordering' => 'Ordering',
                            ),
                        'disable' =>
                            array (
                                'switch_layout' => 'Switch Layout',
                                'cat_filter' => 'Category Filter',
                                'filter' => 'Filter',
                            ),
                    ),
                'product_category_enable' => 'on',
                'item_skin' => 'skin-02',
                'single_product_tab' => 'layout-2',
                'product_image_size_mini_cart' => array(
                    'width' => 100,
                    'height' => 100
                )
            ), $defaults );
        }



        public function print_svg() {
            GTA()->get_template('svg.php');
        }

        public function change_sale_flash($html, $label, $type) {
            ob_start();
            GTA()->get_template('loop/sale.php',array(
                'label' => $label,
                'type' => $type
            ));
            return ob_get_clean();
        }

        public function change_loop_cat_position() {
            remove_action('woocommerce_shop_loop_item_title', 'g5shop_template_loop_cat', 10);
            add_action('woocommerce_shop_loop_item_title', 'g5shop_template_loop_cat', 25);
        }

        public function change_shop_single_layout() {
            if (is_singular('product') && !g5core_has_sidebar()) {
                remove_action( G5CORE_CURRENT_THEME . '_before_main_content', array( G5SHOP()->templates(), 'breadcrumb_template' ), 11 );
                add_action('woocommerce_before_single_product_summary', array( G5SHOP()->templates(), 'breadcrumb_template' ), 2);

                remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
                add_action('woocommerce_before_single_product_summary', 'woocommerce_output_all_notices', 1);


                $content_padding = G5CORE()->options()->layout()->get_option('content_padding');
                if (is_array($content_padding) && isset($content_padding['top'])) {
                    $content_padding_top =  $content_padding['top'];
                    $content_padding['top'] = 0;
                    G5CORE()->options()->layout()->set_option('content_padding',$content_padding);
                    $responsive_break_point =  absint(G5CORE()->options()->header()->get_option( 'header_responsive_breakpoint', '991' )) + 1;
                    $custom_css  =<<<CSS
                    @media screen and (min-width: {$responsive_break_point}px) {
                        body.no-sidebar .g5shop__single-product-summary {
                            padding-top: {$content_padding_top}px;
                        }        
                    }
CSS;
                    G5CORE()->custom_css()->addCss($custom_css);

                }


            }
        }


        public function single_product_image_wrap_open() {
            echo '<div class="porus__single-product-images">';
        }

        public function single_product_image_wrap_close() {
            echo '</div><!-- End Single Product Images -->';
        }

        public function change_single_product_gallery_horizontal_args($args) {
            if (!g5core_has_sidebar()) {
                return wp_parse_args(array(
                    'slidesToShow' => 5,
                    'slidesToScroll' => 5,
                ),$args);
            }
            return $args;
        }

        public function change_single_related_layout_setting($args) {
            return wp_parse_args(array(
                'item_skin' => 'skin-02',
            ),$args);
        }

        public function change_single_up_sells_layout_setting($args) {
            return wp_parse_args(array(
                'item_skin' => 'skin-02',
            ),$args);
        }

        public function change_single_cross_sells_layout_setting($args) {
            return wp_parse_args(array(
                'item_skin' => 'skin-02',
            ),$args);
        }

        public function demo_layout() {
            if ( ! function_exists( 'G5CORE' ) || ! function_exists( 'G5SHOP' ) ) {
                return;
            }
            $shop_layout = isset( $_REQUEST['shop_layout'] ) ? $_REQUEST['shop_layout'] : '';
            if ( ! empty( $shop_layout ) ) {
                $ajax_query                = G5CORE()->cache()->get( 'g5core_ajax_query', array() );
                $ajax_query['shop_layout'] = $shop_layout;
                G5CORE()->cache()->set( 'g5core_ajax_query', $ajax_query );
            }

            switch ( $shop_layout ) {
                case 'no-sidebar':
                    G5SHOP()->options()->set_option( 'post_columns_xl', '4' );
                    G5SHOP()->options()->set_option( 'post_columns_lg', '4' );
                    G5SHOP()->options()->set_option( 'post_columns_md', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_sm', '2' );
                    G5SHOP()->options()->set_option( 'post_columns', '1' );
                    G5CORE()->options()->layout()->set_option( 'site_layout', 'none' );
                    break;
                case 'left-sidebar':
                    G5SHOP()->options()->set_option( 'post_columns_xl', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_lg', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_md', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_sm', '2' );
                    G5SHOP()->options()->set_option( 'post_columns', '1' );
                    G5CORE()->options()->layout()->set_option( 'site_layout', 'left' );
                    G5SHOP()->options()->set_option( 'shop_toolbar', array(
                        'left'  =>
                            array(
                                'result_count' => 'Result Count',
                                'cat_filter' => 'Category Filter',
                            ),
                        'right' =>
                            array(
                                'ordering'      => 'Ordering',
                            )
                    ) );

                    break;
                case 'right-sidebar':
                    G5SHOP()->options()->set_option( 'post_columns_xl', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_lg', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_md', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_sm', '2' );
                    G5SHOP()->options()->set_option( 'post_columns', '1' );
                    G5CORE()->options()->layout()->set_option( 'site_layout', 'right' );
                    G5SHOP()->options()->set_option( 'shop_toolbar', array(
                        'left'  =>
                            array(
                                'result_count' => 'Result Count',
                                'cat_filter' => 'Category Filter',
                            ),
                        'right' =>
                            array(
                                'ordering'      => 'Ordering',
                            )
                    ) );
                    break;
                case 'full-width':
                    G5SHOP()->options()->set_option( 'post_columns_xl', '5' );
                    G5SHOP()->options()->set_option( 'post_columns_lg', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_md', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_sm', '2' );
                    G5SHOP()->options()->set_option( 'post_columns', '1' );
                    G5SHOP()->options()->set_option( 'shop_toolbar_layout', 'stretched_content' );
                    G5SHOP()->options()->set_option( 'shop_toolbar', array(
                        'left'  =>
                            array(
                                'result_count' => 'Result Count',
                                'cat_filter' => 'Category Filter',
                            ),
                        'right' =>
                            array(
                                'ordering'      => 'Ordering',
                                'filter'        => 'Filter',
                            )
                    ) );
                    G5CORE()->options()->layout()->set_option( 'site_layout', 'none' );
                    G5CORE()->options()->layout()->set_option( 'site_stretched_content', 'on' );
                    break;
                case 'full-width-left-sidebar':
                    G5SHOP()->options()->set_option( 'post_columns_xl', '4' );
                    G5SHOP()->options()->set_option( 'post_columns_lg', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_md', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_sm', '2' );
                    G5SHOP()->options()->set_option( 'post_columns', '1' );
                    G5SHOP()->options()->set_option( 'shop_toolbar_layout', 'stretched_content' );
                    G5SHOP()->options()->set_option( 'shop_toolbar', array(
                        'left'  =>
                            array(
                                'result_count' => 'Result Count',
                                'cat_filter' => 'Category Filter',
                            ),
                        'right' =>
                            array(
                                'ordering'      => 'Ordering',
                                'filter'        => 'Filter',
                            )
                    ) );
                    G5CORE()->options()->layout()->set_option( 'site_layout', 'left' );
                    G5CORE()->options()->layout()->set_option( 'site_stretched_content', 'on' );
                    break;
                case 'full-width-right-sidebar':
                    G5SHOP()->options()->set_option( 'post_columns_xl', '4' );
                    G5SHOP()->options()->set_option( 'post_columns_lg', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_md', '3' );
                    G5SHOP()->options()->set_option( 'post_columns_sm', '2' );
                    G5SHOP()->options()->set_option( 'post_columns', '1' );
                    G5SHOP()->options()->set_option( 'shop_toolbar_layout', 'stretched_content' );
                    G5SHOP()->options()->set_option( 'shop_toolbar', array(
                        'left'  =>
                            array(
                                'result_count' => 'Result Count',
                                'cat_filter' => 'Category Filter',
                            ),
                        'right' =>
                            array(
                                'ordering'      => 'Ordering',
                                'switch_layout' => 'Switch Layout',
                                'filter'        => 'Filter',
                            )
                    ) );
                    G5CORE()->options()->layout()->set_option( 'site_layout', 'right' );
                    G5CORE()->options()->layout()->set_option( 'site_stretched_content', 'on' );
                    break;
            }
        }

        public function demo_post_per_pages( $query ) {
            if ( ! function_exists( 'G5CORE' ) || ! function_exists( 'G5SHOP' ) ) {
                return;
            }
            if ( ! is_admin() && $query->is_main_query() ) {
                $shop_layout    = isset( $_REQUEST['shop_layout'] ) ? $_REQUEST['shop_layout'] : '';
                $post_per_pages = '';

                switch ( $shop_layout ) {
                    case 'no-sidebar':
                        $post_per_pages = 16;
                        break;
                    case 'left-sidebar':
                        $post_per_pages = 12;
                        break;
                    case 'right-sidebar':
                        $post_per_pages = 12;
                        break;
                    case 'full-width':
                        $post_per_pages = 15;
                        break;
                    case 'full-width-left-sidebar':
                        $post_per_pages = 16;
                        break;
                    case 'full-width-right-sidebar':
                        $post_per_pages = 16;
                        break;
                }

                if ( ! empty( $post_per_pages ) ) {
                    $query->set( 'posts_per_page', $post_per_pages );
                }
            }
        }


    }
}
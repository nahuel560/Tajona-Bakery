<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if ( ! class_exists( 'G5Shop_Templates' ) ) {
    class G5Shop_Templates
    {
        private static $_instance;

        public static function getInstance() {
            if ( self::$_instance == null ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function init() {
            add_filter( 'template_include', array( $this, 'template_loader' ), 15 );
            add_filter( 'wc_get_template', array($this, 'change_wc_get_template'), 10, 5 );
            add_filter( 'wc_get_template_part', array($this, 'change_wc_get_template_part'), 10, 3 );

            add_filter('g5core_locate_template',array($this,'change_g5core_locate_template'),10, 3);


            remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

            // remove product link close
            remove_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_product_link_close',5);
            remove_action('woocommerce_before_shop_loop_item','woocommerce_template_loop_product_link_open',10);

            //remove add to cart
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

            // remove product thumb
            remove_action('woocommerce_before_shop_loop_item_title','woocommerce_template_loop_product_thumbnail',10);

            // Sale count down
            //add_action('woocommerce_before_shop_loop_item_title','g5shop_template_loop_sale_count_down',10);
            add_action('woocommerce_single_product_summary','g5shop_template_single_sale_count_down',15);


            remove_action('woocommerce_before_shop_loop_item_title','woocommerce_show_product_loop_sale_flash',10);
            add_action('woocommerce_before_shop_loop_item_title','g5shop_template_loop_sale_flash',20);



            // product actions
            add_action('g5shop_product_actions','g5shop_template_loop_action_add_to_cart',5);
            add_action('g5shop_product_actions','g5shop_template_loop_quick_view',10);
            add_action('g5shop_product_actions','g5shop_template_loop_wishlist',15);
            add_action('g5shop_product_actions','g5shop_template_loop_compare',20);


            // product actions
            add_action('g5shop_product_list_actions','g5shop_template_loop_add_to_cart',5);
            add_action('g5shop_product_list_actions','g5shop_template_loop_quick_view',10);
            add_action('g5shop_product_list_actions','g5shop_template_loop_wishlist',15);
            add_action('g5shop_product_list_actions','g5shop_template_loop_compare',20);






            // remove product title
            remove_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title',10);

            // product cat
            add_action('woocommerce_shop_loop_item_title','g5shop_template_loop_cat',10);

            // product title
            add_action('woocommerce_shop_loop_item_title','g5shop_template_loop_title',20);

            // remove product rating
            remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',5);
            add_action('woocommerce_after_shop_loop_item_title','g5shop_template_loop_rating',20);


            add_action('woocommerce_after_shop_loop_item_title','g5shop_template_loop_excerpt',40);

            add_filter('g5core_page_title',array($this,'page_title'));

            add_filter('g5core_sidebars',array($this,'register_sidebar'));

            add_filter('g5core_before_widget', array($this,'widget_shop_filter'), 10,4);

            add_filter('g5core_options_header_customize',array($this,'header_customize'));

            add_filter('g5core_options_header_mobile_customize',array($this,'header_mobile_customize'));

            add_filter('woocommerce_add_to_cart_fragments',array($this,'header_add_to_cart_fragment'));

            add_filter('woocommerce_cart_item_thumbnail', array($this, 'change_cart_item_thumbnail'), 10, 3);

           add_filter('woocommerce_get_image_size_gallery_thumbnail',array($this,'change_gallery_thumbnail_image_size'));






            //add_filter('wp_list_categories', array($this,'cat_count_span'),20,2);
            //add_filter('woocommerce_layered_nav_term_html', array($this,'layered_nav_term_html'));

            add_filter('product_cat_class',array($this,'change_product_cat_class'));


            add_action( G5CORE_CURRENT_THEME . '_before_main_content', array( $this, 'breadcrumb_template' ), 11 );


            remove_action('woocommerce_before_single_product_summary','woocommerce_show_product_sale_flash',10);
            add_action('woocommerce_before_single_product_summary','g5shop_template_loop_sale_flash',10);


            add_action('woocommerce_before_single_product_summary', 'g5shop_template_single_product_video',25);

            // remove compare button
            global $yith_woocompare;
            if ( isset($yith_woocompare) && isset($yith_woocompare->obj)) {
                remove_action( 'woocommerce_after_shop_loop_item', array($yith_woocompare->obj,'add_compare_link'), 20 );
                remove_action( 'woocommerce_single_product_summary', array( $yith_woocompare->obj, 'add_compare_link' ), 35 );
            }

            $add_to_cart_enable = G5SHOP()->options()->get_option('add_to_cart_enable');
            if ('on' !== $add_to_cart_enable) {
                remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30);
            }

            add_action('woocommerce_after_add_to_cart_button', 'g5shop_template_single_product_actions');

            add_action('woocommerce_share','g5shop_template_single_product_share');

            add_filter('woocommerce_product_description_heading',array($this,'product_description_heading'));
            add_filter('woocommerce_product_additional_information_heading',array($this,'product_description_heading'));


            // single product related
            add_filter('woocommerce_output_related_products_args', array($this, 'product_related_per_page'));
            add_filter('woocommerce_product_related_posts_relate_by_category',array($this, 'product_related_posts_relate_by_category'));
            add_filter('woocommerce_product_related_posts_relate_by_tag',array($this, 'product_related_posts_relate_by_tag'));

            add_filter('woocommerce_upsells_total', array($this, 'product_up_sells_posts_per_page'));


            // quick view
            add_action('woocommerce_quick_view_product_summary','g5shop_template_quick_view_title',5);
            add_action('woocommerce_quick_view_product_summary','g5shop_template_quick_view_rating',10);
            add_action('woocommerce_quick_view_product_summary','woocommerce_template_single_price',10);
            add_action('woocommerce_quick_view_product_summary','woocommerce_template_single_excerpt',20);

            if ('on' === $add_to_cart_enable) {
                add_action('woocommerce_quick_view_product_summary','woocommerce_template_single_add_to_cart',30);
            }

            add_action('woocommerce_quick_view_product_summary','woocommerce_template_single_meta',40);
            add_action('woocommerce_quick_view_product_summary','woocommerce_template_single_sharing',50);
            add_action('woocommerce_quick_view_product_summary','g5shop_template_single_sale_count_down',15);
            remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
            remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
            add_action('woocommerce_before_checkout_form','g5shop_template_checkout_login_coupon_form',11);

            add_action('template_redirect', array($this,'change_view_list') ,30);

            add_filter('g5shop_single_product_classes',array($this,'single_product_image_gallery_classes'));

            //add_action('woocommerce_checkout_before_order_review_heading',array($this,'checkout_order_review_wrap_open'),1);
            //add_action('woocommerce_checkout_after_order_review',array($this,'checkout_order_review_wrap_close'),100);

            //add_action('woocommerce_checkout_before_customer_details',array($this,'checkout_customer_details_wrap_open'),1);
            //add_action('woocommerce_checkout_after_customer_details',array($this,'checkout_order_review_wrap_close'),100);

            remove_filter(G5CORE_CURRENT_THEME . '_has_sidebar',G5CORE_CURRENT_THEME . '_wc_sidebar',100);

	        add_action( 'gsf_after_change_options/g5shop_options', array( 'WC_Regenerate_Images', 'maybe_regenerate_images' ) );

	        add_action( 'body_class', array( $this, 'body_class' ) );

	        add_action('wp_head',array($this,'change_wishlist_button_position'),11);

        }

        public function body_class($classes) {
        	if (is_singular('product')) {
        		$single_product_layout = G5SHOP()->options()->get_option('single_product_layout');
        		$classes[] = 'g5shop__body-single-product-' . $single_product_layout;
	        }
        	return $classes;
        }



        public function cat_count_span($links, $args)
        {
            if (isset($args['taxonomy']) && ($args['taxonomy'] == 'product_cat')) {
                $links = str_replace('(', '', $links);
                $links = str_replace(')', '', $links);
            }
            return $links;
        }

        public function layered_nav_term_html($term_html) {
            $term_html = str_replace('(', '', $term_html);
            $term_html = str_replace(')', '', $term_html);
            return $term_html;
        }

        public function register_sidebar($sidebars) {
            $sidebars = wp_parse_args(array(
                array(
                    'name' => esc_html__('Shop Filter', 'g5-shop'),
                    'id' => 'g5shop-filter',
                ),
                array(
                    'name' => esc_html__('Shop Sidebar', 'g5-shop'),
                    'id' => 'g5shop-main',
                )
            ),$sidebars);
            return $sidebars;

        }


        public function widget_shop_filter($before_widget, $sidebar_id, $widget_num, $widget_opt) {
            if ($sidebar_id === 'g5shop-filter') {
                $custom_css = g5core_get_bootstrap_columns(array(
                    'xl' => G5SHOP()->options()->get_option('shop_toolbar_columns_xl'),
                    'lg' => G5SHOP()->options()->get_option('shop_toolbar_columns_lg'),
                    'md' => G5SHOP()->options()->get_option('shop_toolbar_columns_md'),
                    'sm' => G5SHOP()->options()->get_option('shop_toolbar_columns_sm'),
                    '' => G5SHOP()->options()->get_option('shop_toolbar_columns'),
                ));
                $before_widget = preg_replace('/class="/', "class=\"{$custom_css} ", $before_widget, 1);
            }
            return $before_widget;
        }



        public function template_loader($template) {
            if (preg_match('/woocommerce\/templates\/archive-product\.php$/', $template)) {
                return G5SHOP()->plugin_dir('woocommerce/archive-product.php');
            }
            return $template;
        }

        public function change_wc_get_template( $template, $template_name, $args, $template_path, $default_path ) {
            $change_template  = G5SHOP()->plugin_dir('woocommerce/' . $template_name);
            if (file_exists($change_template)) {
                $locate_template = locate_template(
                    array(
                        trailingslashit( $template_path ) . $template_name,
                        $template_name,
                    )
                );
                if (is_readable($locate_template)) {
                    return $locate_template;
                }
                return $change_template;
            }
            return $template;
        }

        public function change_wc_get_template_part($template, $slug, $name) {
            if (!empty($name)) {
                $change_template = locate_template(
                    array(
                        "{$slug}-{$name}.php",
                        WC()->template_path() . "{$slug}-{$name}.php",
                    )
                );

                if (is_readable($change_template)) {
                    return $change_template;
                }

                $change_template = G5SHOP()->plugin_dir('woocommerce/' . "{$slug}-{$name}.php");
                if (is_readable($change_template)) {
                    return $change_template;
                }
            }
            else {
                $change_template = locate_template(
                    array(
                        "{$slug}.php",
                        WC()->template_path() . "{$slug}.php",
                    )
                );
                if (is_readable($change_template)) {
                    return $change_template;
                }
                $change_template = G5SHOP()->plugin_dir('woocommerce/' . "{$slug}.php");
                if (is_readable($change_template)) {
                    return $change_template;
                }
            }
            return $template;
        }

        public function change_g5core_locate_template($located, $template_name, $args) {
            $change_template  = G5SHOP()->plugin_dir('g5core/' . $template_name);
            if (file_exists($change_template)) {
                $locate_template = locate_template(
                    array(
                        trailingslashit(get_stylesheet_directory()) . 'g5plus/core/' . $template_name
                    )
                );

                if (is_readable($locate_template)) {
                    return $locate_template;
                }
                return $change_template;
            }
            return $located;
        }

        public function page_title($page_title) {
            if (is_post_type_archive('product')) {
                $shop_page_id = wc_get_page_id( 'shop' );
                if ($shop_page_id) {
                    if (!$page_title) {
                        $page_title   = get_the_title( $shop_page_id );
                    }
                    $page_title_content = get_post_meta( get_the_ID(), G5CORE()->meta_prefix . 'page_title_custom', true);
                    if ($page_title_content !== '') {
                        $page_title = $page_title_content;
                    }
                }
            }
            return $page_title;
        }

        public function header_customize($header_customize) {
            $customize = array(
                'mini-cart' => esc_html__( 'Mini Cart', 'g5-shop' ),
                'my-account' => esc_html__( 'My Account', 'g5-shop' ),
                'search-product' => esc_html__( 'Search Product', 'g5-shop' ),
                'search-product-button' => esc_html__( 'Search Product Button', 'g5-shop' )
            );

            if (shortcode_exists('yith_wcwl_add_to_wishlist')) {
                $customize['wishlist'] = esc_html__( 'Wishlist', 'g5-shop' );
            }
            $header_customize = wp_parse_args($customize,$header_customize);
            return $header_customize;
        }

        public function header_mobile_customize($header_customize) {
            $customize = array(
                'mini-cart' => esc_html__( 'Mini Cart', 'g5-shop' ),
                'my-account' => esc_html__( 'My Account', 'g5-shop' ),
                'search-product-button' => esc_html__( 'Search Product Button', 'g5-shop' )
            );
            if (shortcode_exists('yith_wcwl_add_to_wishlist')) {
                $customize['wishlist'] = esc_html__( 'Wishlist', 'g5-shop' );
            }
            $header_customize = wp_parse_args($customize,$header_customize);
            return $header_customize;
        }

        public function header_add_to_cart_fragment($fragments) {
            ob_start();
            g5shop_template_mini_cart_icon();
            $fragments['.g5shop__mini-cart-icon'] = ob_get_clean();
            return $fragments;
        }

        public function change_cart_item_thumbnail($image, $cart_item, $cart_item_key) {
            if (isset($cart_item['product_id'])) {
                $image_size = G5SHOP()->options()->get_option('product_image_size_mini_cart',array());
                $width = isset($image_size['width']) ? $image_size['width'] : '85';
                $height = isset($image_size['height']) ? $image_size['height'] : '100';

                $image_id = get_post_thumbnail_id($cart_item['product_id']);
                $image = G5CORE()->image_resize()->resize(array(
                    'image_id' => $image_id,
                    'width' => $width,
                    'height' => $height
                ));
                $image_attributes = array(
                    'src="' . esc_url($image['url']) . '"',
                    'width="' . esc_attr($image['width']) . '"',
                    'height="' . esc_attr($image['height']) . '"',
                    'title="' . esc_attr(get_the_title($cart_item['product_id'])) . '"'
                );
                $image = '<img ' . implode(' ', $image_attributes) . '>';
            }
            return $image;
        }

        public function change_gallery_thumbnail_image_size($size) {
	        $image_size = G5SHOP()->options()->get_option('product_image_size_gallery_thumbnail',array());
	        $size['width'] = isset($image_size['width']) ? $image_size['width'] : '100';
	        $size['height'] = isset($image_size['height']) ? $image_size['height'] : '100';
        	return $size;
        }

        public function change_product_cat_class($classes) {
            $classes[] = 'g5shop__product-category';
            return $classes;
        }

        public function breadcrumb_template() {
            if (!is_product()) return;
            g5shop_template_breadcrumbs();
        }

        public function product_description_heading() {
            return '';
        }

        public function product_related_per_page() {
            $product_related_per_page = absint(G5SHOP()->options()->get_option('product_related_per_page')) ;
            if ($product_related_per_page > 0) {
                $args['posts_per_page'] = $product_related_per_page;
            }
            return $args;
        }

        public function product_related_posts_relate_by_category() {
            $product_related_algorithm = G5SHOP()->options()->get_option('product_related_algorithm');
            return (in_array($product_related_algorithm, array('cat', 'cat-tag'))) ? true : false;
        }
        public function product_related_posts_relate_by_tag() {
            $product_related_algorithm = G5SHOP()->options()->get_option('product_related_algorithm');
            return (in_array($product_related_algorithm, array('tag', 'cat-tag'))) ? true : false;
        }

        public function product_up_sells_posts_per_page($posts_per_page) {
            $up_sells_per_page = absint(G5SHOP()->options()->get_option('product_up_sells_per_page'));
            if ($up_sells_per_page > 0) {
                $posts_per_page = $up_sells_per_page;
            }
            return $posts_per_page;
        }

        public function change_view_list() {
            if (is_shop() || is_product_taxonomy()) {
                $view = g5shop_get_product_switch_layout();
                if (!empty($view)) {
                    $ajax_query = G5CORE()->cache()->get('g5core_ajax_query',array());
                    $ajax_query['view'] = $view;
                    G5CORE()->cache()->set('g5core_ajax_query', $ajax_query);
                }
                if ($view === 'list') {
                    G5SHOP()->options()->set_option('post_layout','list');
                    G5SHOP()->options()->set_option('post_columns_xl','1');
                    G5SHOP()->options()->set_option('post_columns_lg','1');
                    G5SHOP()->options()->set_option('post_columns_md','1');
                    G5SHOP()->options()->set_option('post_columns_sm','1');
                    G5SHOP()->options()->set_option('post_columns','1');
                }
            }


            if (isset($_REQUEST['action'])) {
                $view = isset($_REQUEST['view']) ? $_REQUEST['view'] : '';
                if ($view === 'list') {
                    G5SHOP()->options()->set_option('post_layout','list');
                    G5SHOP()->options()->set_option('post_columns_xl','1');
                    G5SHOP()->options()->set_option('post_columns_lg','1');
                    G5SHOP()->options()->set_option('post_columns_md','1');
                    G5SHOP()->options()->set_option('post_columns_sm','1');
                    G5SHOP()->options()->set_option('post_columns','1');
                }
            }

        }

        public function single_product_image_gallery_classes($classes) {
            global $product;
            $attachment_ids = $product->get_gallery_image_ids();
            $classes[] = count($attachment_ids) > 0 ? 'g5shop__product-gallery-has-thumb' : '';
            return $classes;
        }

        public function checkout_order_review_wrap_open() {
            echo '<div class="g5shop__order-review">';

        }

        public function checkout_order_review_wrap_close() {
            echo '</div>';
        }

        public function checkout_customer_details_wrap_open() {
            echo '<div class="g5shop__customer-details">';
        }

        public function change_wishlist_button_position() {
	        if (function_exists('YITH_WCWL_Frontend')) {
		        $YITH_WCWL = YITH_WCWL_Frontend();

		        $positions = apply_filters( 'yith_wcwl_positions', array(
			        'add-to-cart' => array( 'hook' => 'woocommerce_single_product_summary', 'priority' => 31 ),
			        'thumbnails'  => array( 'hook' => 'woocommerce_product_thumbnails', 'priority' => 21 ),
			        'summary'     => array( 'hook' => 'woocommerce_after_single_product_summary', 'priority' => 11 )
		        ) );

		        // Add the link "Add to wishlist"
		        $position = get_option( 'yith_wcwl_button_position', 'add-to-cart' );

		        if ( $position != 'shortcode' && isset( $positions[ $position ] ) ) {
			        remove_action( $positions[ $position ]['hook'], array( $YITH_WCWL, 'print_button' ), $positions[ $position ]['priority'] );
		        }

		        // check if Add to wishlist button is enabled for loop
		        $enabled_on_loop = 'yes' == get_option( 'yith_wcwl_show_on_loop', 'no' );

		        if( ! $enabled_on_loop ){
			        return;
		        }

		        $positions = apply_filters( 'yith_wcwl_loop_positions', array(
			        'before_image' => array( 'hook' => 'woocommerce_before_shop_loop_item', 'priority' => 5 ),
			        'before_add_to_cart' => array( 'hook' => 'woocommerce_after_shop_loop_item', 'priority' => 7 ),
			        'after_add_to_cart' => array( 'hook' => 'woocommerce_after_shop_loop_item', 'priority' => 15 )
		        ) );

		        // Add the link "Add to wishlist"
		        $position = get_option( 'yith_wcwl_loop_position', 'after_add_to_cart' );

		        if ( $position != 'shortcode' && isset( $positions[ $position ] ) ) {
			        remove_action( $positions[ $position ]['hook'], array( $YITH_WCWL, 'print_button' ), $positions[ $position ]['priority'] );
		        }
	        }



        }



    }
}

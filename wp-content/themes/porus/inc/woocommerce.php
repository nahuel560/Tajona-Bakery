<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'PORUS_WC' ) ) {
	class PORUS_WC {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_action('wp_enqueue_scripts',array($this,'enqueue_scripts'),21);
			add_action('init',array($this,'remove_wc_action'));
			add_filter('woocommerce_show_page_title',array($this,'remove_page_title'));
			add_filter('porus_has_sidebar', array($this,'remove_sidebar'), 100);
			add_filter('wp_list_categories', array($this,'change_cate_count'),15,2);

			add_filter('woocommerce_product_description_heading',array($this,'product_description_heading'));
			add_filter('woocommerce_product_additional_information_heading',array($this,'product_description_heading'));

			add_action('woocommerce_checkout_before_order_review_heading', array($this,'checkout_order_review_wrap_open'),1);
			add_action('woocommerce_checkout_after_order_review', array($this,'checkout_order_review_wrap_close'),100);
		}

		public function enqueue_scripts() {
			wp_enqueue_style( PORUS_FILE_HANDLER. 'woocommerce', get_theme_file_uri( '/assets/css/woocommerce.css' ), array(), PORUS_VERSION );
		}

		public function remove_wc_action() {
			remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
		}

		public function remove_page_title() {
			return false;
		}

		public function remove_sidebar($has_sidebar) {
			if (function_exists('WC') && (is_cart() || is_checkout() || is_account_page()) ) {
				return false;
			}

			if (function_exists('yith_wcwl_object_id')) {
				$wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
				if ( ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) ) {
					return false;
				}
			}

			return $has_sidebar;
		}

		public function change_cate_count($links, $args)
		{
			if (isset($args['taxonomy']) && ($args['taxonomy'] == 'product_cat')) {
				$links = str_replace('(', '', $links);
				$links = str_replace(')', '', $links);
			}
			return $links;
		}

		public function product_description_heading() {
			return '';
		}

		public function checkout_order_review_wrap_open() {
			echo '<div id="order_review_wrapper">';
		}

		public function checkout_order_review_wrap_close() {
			echo '</div>';
		}
	}

	function PORUS_WC() {
		return PORUS_WC::getInstance();
	}

	PORUS_WC()->init();
}
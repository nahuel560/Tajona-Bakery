<?php
if ( ! class_exists( 'G5Core_Options_Abstract', false ) ) {
    G5CORE()->load_file(G5CORE()->plugin_dir('inc/abstract/options.class.php'));
}
if (!class_exists('G5Shop_Options')) {
	class G5Shop_Options extends G5Core_Options_Abstract {
		protected $option_name = 'g5shop_options';

		private static $_instance;
		public static function getInstance() {
			if (self::$_instance == NULL) { self::$_instance = new self(); }
			return self::$_instance;
		}

		public function init_default() {
			return array (
                'featured_label_enable' => 'on',
                'featured_label_text' => 'Hot',
                'sale_label_enable' => 'on',
                'sale_flash_mode' => 'text',
                'sale_label_text' => 'Sale',
                'new_label_enable' => '',
                'new_label_since' => '5',
                'new_label_text' => 'New',
                'sale_count_down_enable' => '',
                'swatches_enable' => '',
                'swatches_taxonomy' => '',
                'add_to_cart_enable' => 'on',
                'product_image_size_mini_cart' =>
                    array (
                        'width' => '85',
                        'height' => '100',
                    ),
                'product_image_size_gallery_thumbnail' =>
	                array (
		                'width' => '100',
		                'height' => '100',
	                ),
                'archive_banner_type' => 'none',
                'archive_banner_image' =>
                    array (
                        'id' => 0,
                        'url' => '',
                    ),
                'archive_banner_gallery' => '',
                'archive_banner_video' => '',
                'archive_banner_custom_html' => '',
                'shop_toolbar_layout' => 'boxed',
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
                                'switch_layout' => 'Switch Layout',
                            ),
                        'disable' =>
                            array (
                                'cat_filter' => 'Category Filter',
                                'filter' => 'Filter',
                            ),
                    ),
                'shop_toolbar_mobile' =>
	                array (
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
				                'filter' => 'Filter',
			                ),
	                ),
                'append_tabs' => '',
                'shop_toolbar_columns_xl' => 4,
                'shop_toolbar_columns_lg' => 3,
                'shop_toolbar_columns_md' => 2,
                'shop_toolbar_columns_sm' => 2,
                'shop_toolbar_columns' => 1,
                'post_layout' => 'grid',
                'item_skin' => 'skin-01',
                'item_custom_class' => '',
                'post_columns_gutter' => '30',
                'post_columns_xl' => 3,
                'post_columns_lg' => 3,
                'post_columns_md' => 2,
                'post_columns_sm' => 2,
                'post_columns' => 1,
                'posts_per_page' => 12,
                'post_paging' => 'pagination',
                'post_animation' => 'none',
                'image_hover_effect' => 'change-image',
                'product_category_enable' => '',
                'product_excerpt_enable' => '',
                'product_rating_enable' => 'on',
                'product_quick_view_enable' => 'on',
                'single_product_layout' => 'layout-1',
                'single_product_tab' => 'layout-1',
                'single_product_share_enable' => 'on',
                'product_related_enable' => 'on',
                'product_related_algorithm' => 'cat-tag',
                'product_related_per_page' => 6,
                'product_related_columns_gutter' => '30',
                'product_related_columns_xl' => 4,
                'product_related_columns_lg' => 3,
                'product_related_columns_md' => 2,
                'product_related_columns_sm' => 2,
                'product_related_columns' => 1,
                'product_up_sells_enable' => 'on',
                'product_up_sells_per_page' => 6,
                'product_up_sells_columns_gutter' => '30',
                'product_up_sells_columns_xl' => 4,
                'product_up_sells_columns_lg' => 3,
                'product_up_sells_columns_md' => 2,
                'product_up_sells_columns_sm' => 2,
                'product_up_sells_columns' => 1,
                'product_cross_sells_enable' => 'on',
                'product_cross_sells_per_page' => 6,
                'product_cross_sells_columns_gutter' => '30',
                'product_cross_sells_columns_xl' => 2,
                'product_cross_sells_columns_lg' => 2,
                'product_cross_sells_columns_md' => 2,
                'product_cross_sells_columns_sm' => 2,
                'product_cross_sells_columns' => 1,
            );
		}
	}
}
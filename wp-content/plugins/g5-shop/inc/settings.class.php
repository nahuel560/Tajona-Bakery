<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if ( ! class_exists( 'G5Shop_Settings' ) ) {
	class G5Shop_Settings {
		private static $_instance;

		public static function getInstance() {
			if ( self::$_instance == null ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

        public function get_product_catalog_layout($inherit = false)
        {
            $config = apply_filters('g5shop_options_product_catalog_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/layout-grid.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-shop'),
                            'img' => G5SHOP()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }

        public function get_product_layout($inherit = false)
        {
            $config = apply_filters('g5shop_options_product_layout', array(
                'grid' => array(
                    'label' => esc_html__('Grid', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/layout-grid.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-shop'),
                            'img' => G5SHOP()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }



        public function get_product_skins($inherit = false)
        {
            $config = apply_filters('g5shop_options_product_skins', array(
                'skin-01' => array(
                    'label' => esc_html__('Skin 01', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/skin-01.png'),
                ),
                'skin-02' => array(
                    'label' => esc_html__('Skin 02', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/skin-02.png'),
                ),
               'skin-03' => array(
                    'label' => esc_html__('Skin 03', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/skin-03.png'),
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-shop'),
                            'img' => G5SHOP()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }

        public function get_product_image_hover_effect($inherit = false)
        {
            $config = apply_filters('g5shop_product_image_hover_effect', array(
                'none' => esc_html__('None', 'g5-shop'),
                'change-image' => esc_html__('Change Image', 'g5-shop'),
                'flip-back' => esc_html__('Flip Back', 'g5-shop')
            ));

            if ($inherit) {
                $config = array(
                        '' => esc_html__('Inherit', 'g5-shop')
                    ) + $config;
            }

            return $config;
        }

        public function get_archive_banner_type($inherit = false) {
		    $config = apply_filters('g5shop_archive_banner_type',array(
		        'none' => esc_html__( 'None', 'g5-shop' ),
                'image' => esc_html__( 'Image', 'g5-shop' ),
                'gallery' => esc_html__( 'Gallery', 'g5-shop' ),
                'video' => esc_html__( 'Video', 'g5-shop' ),
                'custom_html' => esc_html__( 'Custom HTML', 'g5-shop' ),
            ));


            if ($inherit) {
                $config = array(
                        '' => esc_html__('Inherit', 'g5-shop')
                    ) + $config;
            }

            return $config;
        }

        public function get_single_product_layout($inherit = false)
        {
            $config = apply_filters('g5shop_options_single_product_layout', array(
                'layout-1' => array(
                    'label' => esc_html__('Layout 01', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/single-product-1.png')
                ),
                'layout-2' => array(
                    'label' => esc_html__('Layout 02', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/single-product-2.png')
                ),
            ));
            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-shop'),
                            'img' => G5SHOP()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }
            return $config;
        }

        public function get_single_product_tab_layout($inherit = false) {
            $config = apply_filters('g5shop_single_product_tab_layout',array(
                'layout-1' => array(
                    'label' => esc_html__('Layout 01', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/product-tab-1.png')
                ),
                'layout-2' => array(
                    'label' => esc_html__('Layout 02', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/product-tab-2.png')
                ),
                'layout-3' => array(
                    'label' => esc_html__('Layout 03', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/product-tab-3.png')
                ),
                'layout-4' => array(
                    'label' => esc_html__('Layout 04', 'g5-shop'),
                    'img' => G5SHOP()->plugin_url('assets/images/theme-options/product-tab-4.png')
                ),
            ));


            if ($inherit) {
                $config = array(
                        '' => array(
                            'label' => esc_html__('Inherit', 'g5-shop'),
                            'img' => G5SHOP()->plugin_url('assets/images/theme-options/default.png'),
                        ),
                    ) + $config;
            }

            return $config;
        }

        public function get_related_product_algorithm()
        {
            $config = apply_filters('g5shop_options_related_product_algorithm', array(
                'cat' => esc_html__('by Category', 'g5-shop'),
                'tag' => esc_html__('by Tag', 'g5-shop'),
                'cat-tag' => esc_html__('by Category & Tag', 'g5-shop')
            ));
            return $config;

        }

        public function get_swatches_taxonomy($inherit = false) {
            $attribute_array = array();
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if ( ! empty( $attribute_taxonomies ) ) {
                foreach ( $attribute_taxonomies as $tax ) {
                    $type = $tax->attribute_type;
                    if ('select' !== $type) {
                        if (wc_attribute_taxonomy_name($tax->attribute_name)) {
                            $attribute_array['pa_' . $tax->attribute_name] = $tax->attribute_label;
                        }
                    }
                }
            }

            if ($inherit) {
                $attribute_array = array(
                        '' => esc_html__('Inherit', 'g5-shop')
                    ) + $attribute_array;
            }

            return $attribute_array;
        }
	}
}
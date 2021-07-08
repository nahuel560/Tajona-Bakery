<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('G5Shop_Config_Options')) {
    class G5Shop_Config_Options
    {
        /*
         * loader instances
         */
        private static $_instance;

        public static function getInstance()
        {
            if (self::$_instance == null) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        public function init()
        {
            add_filter('gsf_option_config', array($this, 'define_options'), 200);
            add_filter('gsf_meta_box_config', array($this, 'define_meta_box'));
	        add_filter('g5core_admin_bar_theme_options', array($this, 'admin_bar_theme_options'), 200);

            add_filter( 'gsf_term_meta_config', array( $this, 'define_term_meta' ) );
            add_filter('g5core_taxonomy_for_term_meta',array($this,'term_meta_page_title'));
            add_action('pre_get_posts', array($this, 'pre_get_posts'));
            add_action('template_redirect', array($this, 'change_taxonomy_setting'));
            add_action('template_redirect', array($this, 'change_single_product_setting'));
            add_action('template_redirect', array($this, 'change_page_layout'),9);

            add_filter( 'g5core_default_options_g5core_options', array($this,'change_default_options') );

        }

	    public function admin_bar_theme_options($admin_bar_theme_options) {
		    $admin_bar_theme_options['g5shop_options'] = array(
			    'title' => esc_html__('Shop','g5-shop'),
			    'permission' => 'manage_options',
		    );
		    return $admin_bar_theme_options;
	    }

        public function define_options($configs)
        {
            $configs['g5shop_options'] = array(
                'layout' => 'inline',
                'page_title' => esc_html__('Shop Options', 'g5-shop'),
                'menu_title' => esc_html__('Shop', 'g5-shop'),
                'option_name' => 'g5shop_options',
                'parent_slug' => 'g5core_options',
                'permission' => 'manage_options',
                'section' => array(
                    $this->config_section_general(),
                    $this->config_section_shop_banner(),
                    $this->config_section_shop_toolbar(),
                    $this->config_section_archive(),
                    $this->config_section_single(),
                    $this->config_section_cart_page()
                )
            );
            return $configs;
        }

        public function config_section_general()
        {
            return array(
                'id' => 'section_general',
                'title' => esc_html__('General', 'g5-shop'),
                'icon' => 'dashicons dashicons-admin-settings',
                'fields' => array(
                    'featured_label_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'featured_label_enable',
                        'title' => esc_html__('Show Featured Label', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to disable featured label', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'featured_label_enable','on' ),
                    )),
                    'featured_label_text' => array(
                        'id' => 'featured_label_text',
                        'type' => 'text',
                        'title' => esc_html__('Featured Label Text', 'g5-shop'),
                        'subtitle' => esc_html__('Enter product featured label text', 'g5-shop'),
                        'default' => esc_html__('Hot', 'g5-shop'),
                        'required' => array('featured_label_enable', '=', 'on')
                    ),
                    'sale_label_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'sale_label_enable',
                        'title' => esc_html__('Show Sale Label', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to disable sale label', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'sale_label_enable','on' ),
                    )),
                    'sale_flash_mode' => array(
                        'id' => 'sale_flash_mode',
                        'title' => esc_html__('Sale Flash Mode', 'g5-shop'),
                        'type' => 'button_set',
                        'options' => array(
                            'text' => esc_html__('Text', 'g5-shop'),
                            'percent' => esc_html__('Percent', 'g5-shop')
                        ),
                        'default' => G5SHOP()->options()->get_default( 'sale_flash_mode','text' ),
                        'required' => array('sale_label_enable', '=', 'on')
                    ),
                    'sale_label_text' => array(
                        'id' => 'sale_label_text',
                        'type' => 'text',
                        'title' => esc_html__('Sale Label Text', 'g5-shop'),
                        'subtitle' => esc_html__('Enter product sale label text', 'g5-shop'),
                        'default' => esc_html__('Sale', 'g5-shop'),
                        'required' => array(
                            array('sale_label_enable', '=', 'on'),
                            array('sale_flash_mode', '=', 'text')
                        )
                    ),
                    'new_label_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'new_label_enable',
                        'title' => esc_html__('Show New Label', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to disable new label', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'new_label_enable','' ),
                    )),
                    'new_label_since' => array(
                        'id' => 'new_label_since',
                        'type' => 'text',
                        'input_type' => 'number',
                        'title' => esc_html__('Mark New After Published (Days)', 'g5-shop'),
                        'subtitle' => esc_html__('Enter the number of days after the publication is marked as new', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'new_label_since','5' ),
                        'required' => array('new_label_enable', '=', 'on')
                    ),
                    'new_label_text' => array(
                        'id' => 'new_label_text',
                        'type' => 'text',
                        'title' => esc_html__('New Label Text', 'g5-shop'),
                        'subtitle' => esc_html__('Enter product new label text', 'g5-shop'),
                        'default' => esc_html__('New', 'g5-shop'),
                        'required' => array('new_label_text', '=', 'on')
                    ),
                    'sale_count_down_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'sale_count_down_enable',
                        'title' => esc_html__('Show Sale Count Down', 'g5-shop'),
                        'subtitle' => esc_html__('Turn On this option if you want to enable sale count down', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'sale_count_down_enable','' ),
                    )),
                    'swatches_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'swatches_enable',
                        'title' => esc_html__('Swatches Enable', 'g5-shop'),
                        'subtitle' => esc_html__('Turn On this option if you want to enable swatches', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'swatches_enable','' ),
                    )),
                    'swatches_taxonomy' => array(
                        'id' => 'swatches_taxonomy',
                        'title' => esc_html__( 'Swatches Attributes', 'g5-shop' ),
                        'subtitle' => esc_html__( 'Select Attributes to show on listing product', 'g5-shop' ),
                        'type' => 'selectize',
                        'options' => G5SHOP()->settings()->get_swatches_taxonomy(),
                        'default' => '',
                        'allow_clear' => true,
                        'placeholder' => esc_html__( 'Select Attributes', 'g5-shop' ),
                        'required' => array('swatches_enable','=','on')
                    ),
                    'add_to_cart_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'add_to_cart_enable',
                        'title' => esc_html__('Show Add To Cart Button', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to disable add to cart button', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'add_to_cart_enable','on' ),
                    )),
                    'product_image_size_mini_cart' => array(
                        'id' => 'product_image_size_mini_cart',
                        'title' => esc_html__('Product Image Size Mini Cart', 'g5-shop'),
                        'subtitle' => esc_html__('Enter product image size in mini cart', 'g5-shop'),
                        'type' => 'dimension',
                        'default' => G5SHOP()->options()->get_default( 'product_image_size_mini_cart', array(
                            'width' => '85',
                            'height' => '100',
                        )),
                    ),
                    'product_image_size_gallery_thumbnail' => array(
	                    'id' => 'product_image_size_gallery_thumbnail',
	                    'title' => esc_html__('Product Image Size Gallery Thumbnail', 'g5-shop'),
	                    'subtitle' => esc_html__('Enter product image size in gallery thumbnail', 'g5-shop'),
	                    'type' => 'dimension',
	                    'default' => G5SHOP()->options()->get_default( 'product_image_size_gallery_thumbnail', array(
		                    'width' => '100',
		                    'height' => '100',
	                    )),
                    ),
                )
            );
        }

        public function config_section_archive()
        {
            return array(
                'id' => 'section_archive',
                'title' => esc_html__('Shop Listing', 'g5-shop'),
                'icon' => 'dashicons dashicons-category',
                'fields' => array(
                    'post_layout' => array(
                        'id' => 'post_layout',
                        'title' => esc_html__('Layout', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your product layout', 'g5-shop'),
                        'type' => 'image_set',
                        'options' => G5SHOP()->settings()->get_product_catalog_layout(),
                        'default' => G5SHOP()->options()->get_default( 'post_layout','grid' ),
                    ),
                    'item_skin' => array(
                        'id' => 'item_skin',
                        'title' => esc_html__('Item Skin', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your product item skin', 'g5-shop'),
                        'type' => 'image_set',
                        'options' => G5SHOP()->settings()->get_product_skins(),
                        'default' => G5SHOP()->options()->get_default('item_skin', 'skin-01'),
                    ),
                    'item_custom_class' => array(
                        'id' => 'item_custom_class',
                        'title' => esc_html__('Item Css Classes', 'g5-shop'),
                        'subtitle' => esc_html__('Add custom css classes to item', 'g5-shop'),
                        'type' => 'text'
                    ),
                    'post_columns_gutter' => array(
                        'id' => 'post_columns_gutter',
                        'title' => esc_html__('Product Columns Gutter', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your horizontal space between product.', 'g5-shop'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5SHOP()->options()->get_default( 'post_columns_gutter','30' ),
                        'required' => array('post_layout', 'in', array('grid'))
                    ),
                    'post_columns_group' => array(
                        'id' => 'post_columns_group',
                        'title' => esc_html__('Product Columns', 'g5-shop'),
                        'type' => 'group',
                        'required' => array('post_layout', 'in', array('grid')),
                        'fields' => array(
                            'post_columns_row_1' => array(
                                'id' => 'post_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'post_columns_xl' => array(
                                        'id' => 'post_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra large devices (>= 1200px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'post_columns_xl','3' ),
                                        'layout' => 'full',
                                    ),
                                    'post_columns_lg' => array(
                                        'id' => 'post_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on large devices (>= 992px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'post_columns_lg','3' ),
                                        'layout' => 'full',
                                    ),
                                    'post_columns_md' => array(
                                        'id' => 'post_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on medium devices (>= 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'post_columns_md','2' ),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'post_columns_row_2' => array(
                                'id' => 'post_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'post_columns_sm' => array(
                                        'id' => 'post_columns_sm',
                                        'title' => esc_html__('Small Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on small devices (< 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'post_columns_sm','2' ),
                                        'layout' => 'full',
                                    ),
                                    'post_columns' => array(
                                        'id' => 'post_columns',
                                        'title' => esc_html__('Extra Small Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra small devices (< 576px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'post_columns','1' ),
                                        'layout' => 'full',
                                    )
                                )
                            )
                        )
                    ),
                    'posts_per_page' => array(
                        'id' => 'posts_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-shop'),
                        'subtitle' => esc_html__('Enter number of product per page you want to display.', 'g5-shop'),
                        'type' => 'text',
                        'input_type' => 'number',
                        'default' => G5SHOP()->options()->get_default( 'posts_per_page','12' ),
                    ),
                    'post_paging' => array(
                        'id' => 'post_paging',
                        'title' => esc_html__('Paging', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your paging mode', 'g5-shop'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_paging_mode(),
                        'default' => G5SHOP()->options()->get_default( 'post_paging','pagination' ),
                    ),
                    'post_animation' => array(
                        'id'       => 'post_animation',
                        'title'    => esc_html__('Animation', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your post animation', 'g5-shop'),
                        'type'     => 'select',
                        'options'  => G5CORE()->settings()->get_animation(),
                        'default'  => G5SHOP()->options()->get_default('post_animation','none')
                    ),
                    'image_hover_effect' => array(
                        'id' => 'image_hover_effect',
                        'type' => 'select',
                        'title' => esc_html__('Image Hover Effect', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your product image hover effect', 'g5-shop'),
                        'options' => G5SHOP()->settings()->get_product_image_hover_effect(),
                        'default' => G5SHOP()->options()->get_default( 'image_hover_effect','change-image' ),
                    ),
                    'product_category_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'product_category_enable',
                        'title' => esc_html__('Show Category', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'product_category_enable','' ),
                    )),
                    'product_excerpt_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'product_excerpt_enable',
                        'title' => esc_html__('Show Excerpt', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'product_excerpt_enable','' ),
                    )),
                    'product_rating_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'product_rating_enable',
                        'title' => esc_html__('Show Rating', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'product_rating_enable','on' ),
                    )),
                    'product_quick_view_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'product_quick_view_enable',
                        'title' => esc_html__('Show Quick View', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'product_quick_view_enable','on' ),
                    )),
                )
            );
        }

        public function config_section_shop_toolbar()
        {
            return array(
                'id' => 'section_shop_toolbar',
                'title' => esc_html__('Shop Toolbar', 'g5-shop'),
                'icon' => 'dashicons dashicons-admin-customizer',
                'fields' => array(
                    'shop_toolbar_layout' => array(
                        'id' => 'shop_toolbar_layout',
                        'title' => esc_html__( 'Shop ToolBar Layout', 'g5-shop' ),
                        'subtitle' => esc_html__( 'Specify your shop tool bar layout', 'g5-shop' ),
                        'type' => 'select',
                        'options' => array(
                            'boxed' => esc_html__( 'Boxed Content', 'g5-shop' ),
                            'stretched'  => esc_html__( 'Stretched row', 'g5-shop' ),
                            'stretched_content'  => esc_html__( 'Stretched row and content', 'g5-shop' ),
                        ),
                        'default' => G5SHOP()->options()->get_default( 'shop_toolbar_layout','boxed' ),
                    ),
                    'shop_toolbar' => array(
                        'id' => 'shop_toolbar',
                        'title' => esc_html__('Shop ToolBar', 'g5-shop'),
                        'type' => 'sorter',
                        'default' => G5SHOP()->options()->get_default( 'shop_toolbar',array(
                            'top' => array(
                            ),
                            'left' => array(
                                'result_count' => esc_html__('Result Count', 'g5-shop')
                            ),
                            'right' => array(
                                'ordering' => esc_html__('Ordering', 'g5-shop'),
                                'switch_layout' => esc_html__('Switch Layout', 'g5-shop'),
                            ),
                            'disable' => array(
                                'cat_filter' => esc_html__('Category Filter', 'g5-shop'),
                                'filter' => esc_html__('Filter', 'g5-shop')
                            )
                        ) ),
                    ),
                    'shop_toolbar_mobile' => array(
	                    'id' => 'shop_toolbar_mobile',
	                    'title' => esc_html__('Shop ToolBar Mobile', 'g5-shop'),
	                    'type' => 'sorter',
	                    'default' => G5SHOP()->options()->get_default( 'shop_toolbar_mobile',array(
		                    'left' => array(
			                    'result_count' => esc_html__('Result Count', 'g5-shop')
		                    ),
		                    'right' => array(
			                    'ordering' => esc_html__('Ordering', 'g5-shop'),
		                    ),
		                    'disable' => array(
			                    'switch_layout' => esc_html__('Switch Layout', 'g5-shop'),
			                    'filter' => esc_html__('Filter', 'g5-shop')
		                    )
	                    ) ),
                    ),
                    'append_tabs' =>  array(
                        'id' => 'append_tabs',
                        'title' => esc_html__('Append Categories','g5-shop'),
                        'subtitle' => esc_html__('Change where the categories are attached (Selector, htmlString, Array, Element, jQuery object)','g5-shop'),
                        'type' => 'text',
                        'default' => G5SHOP()->options()->get_default( 'append_tabs','' ),
                        'required' => array(
                            array(
                                array('shop_toolbar[top]', 'contain', 'cat_filter'),
                                array('shop_toolbar[left]', 'contain', 'cat_filter'),
                                array('shop_toolbar[right]', 'contain', 'cat_filter')
                            )
                        ),
                    ),
                    'group_shop_toolbar_columns' => array(
                        'id' => 'group_shop_toolbar_columns',
                        'title' => esc_html__('Filter Columns', 'g5-shop'),
                        'type' => 'group',
                        'required' => array(
                            array(
                                array('shop_toolbar[top]', 'contain', 'filter'),
                                array('shop_toolbar[left]', 'contain', 'filter'),
                                array('shop_toolbar[right]', 'contain', 'filter'),
	                            array('shop_toolbar_mobile[left]', 'contain', 'filter'),
	                            array('shop_toolbar_mobile[right]', 'contain', 'filter')
                            )
                        ),
                        'fields' => array(
                            'shop_toolbar_columns_row_1' => array(
                                'id' => 'shop_toolbar_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'shop_toolbar_columns_xl' => array(
                                        'id' => 'shop_toolbar_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your archive filter columns on extra large devices (>= 1200px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'shop_toolbar_columns_xl','4' ),
                                        'layout' => 'full',
                                    ),
                                    'shop_toolbar_columns_lg' => array(
                                        'id' => 'shop_toolbar_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your archive filter columns on large devices (>= 992px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'shop_toolbar_columns_lg','3' ),
                                        'layout' => 'full',
                                    ),
                                    'shop_toolbar_columns_md' => array(
                                        'id' => 'shop_toolbar_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your archive filter columns on medium devices (>= 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'shop_toolbar_columns_md','2' ),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'shop_toolbar_columns_row_2' => array(
                                'id' => 'shop_toolbar_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'shop_toolbar_columns_sm' => array(
                                        'id' => 'shop_toolbar_columns_sm',
                                        'title' => esc_html__('Small Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your archive filter columns on small devices (< 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'shop_toolbar_columns_sm','2' ),
                                        'layout' => 'full',
                                    ),
                                    'shop_toolbar_columns' => array(
                                        'id' => 'shop_toolbar_columns',
                                        'title' => esc_html__('Extra Small Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your archive filter columns on extra small devices (< 576px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'shop_toolbar_columns','1' ),
                                        'layout' => 'full',
                                    )
                                )
                            )
                        )
                    ),
                )
            );
        }

        public function config_section_shop_banner()
        {
            return array(
                'id' => 'section_shop_banner',
                'title' => esc_html__('Shop Banner', 'g5-shop'),
                'icon' => 'dashicons dashicons-format-gallery',
                'fields' => array(
                    'archive_banner_type' => array(
                        'id' => 'archive_banner_type',
                        'title' => esc_html__('Banner Type', 'g5-shop'),
                        'type' => 'button_set',
                        'options' => G5SHOP()->settings()->get_archive_banner_type(),
                        'default' => G5SHOP()->options()->get_default( 'archive_banner_type','none' ),
                    ),
                    'archive_banner_image' => array(
                        'id' => 'archive_banner_image',
                        'title' => esc_html__('Banner Image', 'g5-shop'),
                        'type' => 'image',
                        'required' => array('archive_banner_type', '=', 'image')
                    ),
                    'archive_banner_gallery' => array(
                        'id' => 'archive_banner_gallery',
                        'title' => esc_html__('Banner Gallery', 'g5-shop'),
                        'type' => 'gallery',
                        'required' => array('archive_banner_type', '=', 'gallery')
                    ),
                    'archive_banner_video' => array(
                        'id' => 'archive_banner_video',
                        'title' => esc_html__('Banner Video', 'g5-shop'),
                        'subtitle' => esc_html__('Paste YouTube, Vimeo or self hosted video URL then player automatically will be generated.', 'g5-shop'),
                        'type' => 'textarea',
                        'required' => array('archive_banner_type', '=', 'video')
                    ),
                    'archive_banner_custom_html' => array(
                        'id' => 'archive_banner_custom_html',
                        'title' => esc_html__('Custom Html Content', 'g5-shop'),
                        'type' => 'ace_editor',
                        'mode' => 'html',
                        'default' => '',
                        'required' => array('archive_banner_type', '=', 'custom_html')
                    ),
                )
            );
        }

        public function config_section_single()
        {
            return array(
                'id' => 'section_single',
                'title' => esc_html__('Single Product', 'g5-shop'),
                'icon' => 'dashicons dashicons-products',
                'fields' => array(
                    'single_product_layout' => array(
                        'id' => 'single_product_layout',
                        'title' => esc_html__('Layout', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your product single layout', 'g5-shop'),
                        'type' => 'image_set',
                        'options' => G5SHOP()->settings()->get_single_product_layout(),
                        'default' => G5SHOP()->options()->get_default( 'single_product_layout','layout-1' ),
                    ),
                    'single_product_tab' => array(
                        'id' => 'single_product_tab',
                        'title' => esc_html__('Tab Layout', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your product single tab layout', 'g5-shop'),
                        'type' => 'image_set',
                        'options' => G5SHOP()->settings()->get_single_product_tab_layout(),
                        'default' => G5SHOP()->options()->get_default( 'single_product_tab','layout-1' ),
                    ),
                    'single_product_share_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'single_product_share_enable',
                        'title' => esc_html__('Share', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide share on single blog', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'single_product_share_enable','on' ),
                    )),
                    $this->config_group_product_related(),
                    $this->config_group_product_up_sells()
                )
            );
        }

        public function config_section_cart_page() {
            return array(
                'id' => 'section_cart_page',
                'title' => esc_html__('Cart Page', 'g5-shop'),
                'icon' => 'dashicons dashicons-cart',
                'fields' => array(
                    $this->config_group_product_cross_sells()
                )
            );
        }

        public function config_group_product_related()
        {
            return array(
                'id' => 'group_product_related',
                'title' => esc_html__('Product Related', 'g5-shop'),
                'type' => 'group',
                'toggle_default' => false,
                'fields' => array(
                    'product_related_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'product_related_enable',
                        'title' => esc_html__('Related Product', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide related product area on single product', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'product_related_enable','on' ),
                    )),
                    'product_related_algorithm' => array(
                        'id'       => 'product_related_algorithm',
                        'title'    => esc_html__('Related Products Algorithm', 'g5-shop'),
                        'subtitle' => esc_html__('Specify the algorithm of related products', 'g5-shop'),
                        'type'     => 'select',
                        'options'  => G5SHOP()->settings()->get_related_product_algorithm(),
                        'default'  => G5SHOP()->options()->get_default( 'product_related_algorithm','cat-tag' ),
                        'required' => array('product_related_enable', '=', 'on')
                    ),
                    'product_related_per_page' => array(
                        'id' => 'product_related_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-shop'),
                        'subtitle' => esc_html__('Enter number of posts per page you want to display', 'g5-shop'),
                        'type' => 'text',
                        'input_type' => 'number',
                        'default' => G5SHOP()->options()->get_default( 'product_related_per_page','6' ),
                        'required' => array('product_related_enable', '=', 'on')
                    ),
                    'product_related_columns_gutter' => array(
                        'id' => 'product_related_columns_gutter',
                        'title' => esc_html__('Post Columns Gutter', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your horizontal space between product.', 'g5-shop'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5SHOP()->options()->get_default( 'product_related_columns_gutter','30' ),
                        'required' => array('product_related_enable', '=', 'on'),
                    ),
                    'product_related_columns_group' => array(
                        'id' => 'product_related_columns_group',
                        'title' => esc_html__('Product Columns', 'g5-shop'),
                        'type' => 'group',
                        'required' => array('product_related_enable', '=', 'on'),
                        'fields' => array(
                            'product_related_columns_row_1' => array(
                                'id' => 'product_related_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'product_related_columns_xl' => array(
                                        'id' => 'product_related_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra large devices (>= 1200px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_related_columns_xl','4' ),
                                        'layout' => 'full',
                                    ),
                                    'product_related_columns_lg' => array(
                                        'id' => 'product_related_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on large devices (>= 992px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_related_columns_lg','3' ),
                                        'layout' => 'full',
                                    ),
                                    'product_related_columns_md' => array(
                                        'id' => 'product_related_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on medium devices (>= 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_related_columns_md','2' ),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'product_related_columns_row_2' => array(
                                'id' => 'product_related_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'product_related_columns_sm' => array(
                                        'id' => 'product_related_columns_sm',
                                        'title' => esc_html__('Small Devices ', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on small devices (< 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_related_columns_sm','2' ),
                                        'layout' => 'full',
                                    ),
                                    'product_related_columns' => array(
                                        'id' => 'product_related_columns',
                                        'title' => esc_html__('Extra Small Devices ', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra small devices (< 576px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_related_columns','1' ),
                                        'layout' => 'full',
                                    )
                                )
                            ),
                        )
                    ),


                )
            );
        }

        public function config_group_product_up_sells() {
            return array(
                'id' => 'group_product_up_sells',
                'title' => esc_html__('Product Up Sells', 'g5-shop'),
                'type' => 'group',
                'toggle_default' => false,
                'fields' => array(
                    'product_up_sells_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'product_up_sells_enable',
                        'title' => esc_html__('Product Up Sells', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide product up sells area on single product', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_enable','on' ),
                    )),
                    'product_up_sells_per_page' => array(
                        'id' => 'product_up_sells_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-shop'),
                        'subtitle' => esc_html__('Enter number of posts per page you want to display', 'g5-shop'),
                        'type' => 'text',
                        'input_type' => 'number',
                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_per_page','6' ),
                        'required' => array('product_up_sells_enable', '=', 'on')
                    ),
                    'product_up_sells_columns_gutter' => array(
                        'id' => 'product_up_sells_columns_gutter',
                        'title' => esc_html__('Post Columns Gutter', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your horizontal space between product.', 'g5-shop'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_columns_gutter','30' ),
                        'required' => array('product_up_sells_enable', '=', 'on'),
                    ),
                    'product_up_sells_columns_group' => array(
                        'id' => 'product_up_sells_columns_group',
                        'title' => esc_html__('Product Columns', 'g5-shop'),
                        'type' => 'group',
                        'required' => array('product_up_sells_enable', '=', 'on'),
                        'fields' => array(
                            'product_up_sells_columns_row_1' => array(
                                'id' => 'product_up_sells_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'product_up_sells_columns_xl' => array(
                                        'id' => 'product_up_sells_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra large devices (>= 1200px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_columns_xl','4' ),
                                        'layout' => 'full',
                                    ),
                                    'product_up_sells_columns_lg' => array(
                                        'id' => 'product_up_sells_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on large devices (>= 992px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_columns_lg','3' ),
                                        'layout' => 'full',
                                    ),
                                    'product_up_sells_columns_md' => array(
                                        'id' => 'product_up_sells_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on medium devices (>= 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_columns_md','2' ),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'product_up_sells_columns_row_2' => array(
                                'id' => 'product_up_sells_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'product_up_sells_columns_sm' => array(
                                        'id' => 'product_up_sells_columns_sm',
                                        'title' => esc_html__('Small Devices ', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on small devices (< 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_columns_sm','2' ),
                                        'layout' => 'full',
                                    ),
                                    'product_up_sells_columns' => array(
                                        'id' => 'product_up_sells_columns',
                                        'title' => esc_html__('Extra Small Devices ', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra small devices (< 576px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_up_sells_columns','1' ),
                                        'layout' => 'full',
                                    )
                                )
                            ),
                        )
                    ),
                )
            );
        }

        public function config_group_product_cross_sells() {
            return array(
                'id' => 'group_product_cross_sells',
                'title' => esc_html__('Product Cross Sells', 'g5-shop'),
                'type' => 'group',
                'fields' => array(
                    'product_cross_sells_enable' => G5CORE()->fields()->get_config_toggle(array(
                        'id' => 'product_cross_sells_enable',
                        'title' => esc_html__('Product Cross Sells', 'g5-shop'),
                        'subtitle' => esc_html__('Turn Off this option if you want to hide product cross sells area on cart pages', 'g5-shop'),
                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_enable','on' ),
                    )),
                    'product_cross_sells_per_page' => array(
                        'id' => 'product_cross_sells_per_page',
                        'title' => esc_html__('Posts Per Page', 'g5-shop'),
                        'subtitle' => esc_html__('Enter number of posts per page you want to display', 'g5-shop'),
                        'type' => 'text',
                        'input_type' => 'number',
                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_per_page','6' ),
                        'required' => array('product_cross_sells_enable', '=', 'on')
                    ),
                    'product_cross_sells_columns_gutter' => array(
                        'id' => 'product_cross_sells_columns_gutter',
                        'title' => esc_html__('Post Columns Gutter', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your horizontal space between product.', 'g5-shop'),
                        'type' => 'select',
                        'options' => G5CORE()->settings()->get_post_columns_gutter(),
                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_columns_gutter','30' ),
                        'required' => array('product_cross_sells_enable', '=', 'on'),
                    ),
                    'product_cross_sells_columns_group' => array(
                        'id' => 'product_cross_sells_columns_group',
                        'title' => esc_html__('Product Columns', 'g5-shop'),
                        'type' => 'group',
                        'required' => array('product_cross_sells_enable', '=', 'on'),
                        'fields' => array(
                            'product_cross_sells_columns_row_1' => array(
                                'id' => 'product_cross_sells_columns_row_1',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'product_cross_sells_columns_xl' => array(
                                        'id' => 'product_cross_sells_columns_xl',
                                        'title' => esc_html__('Extra Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra large devices (>= 1200px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_columns_xl','2' ),
                                        'layout' => 'full',
                                    ),
                                    'product_cross_sells_columns_lg' => array(
                                        'id' => 'product_cross_sells_columns_lg',
                                        'title' => esc_html__('Large Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on large devices (>= 992px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_columns_lg','2' ),
                                        'layout' => 'full',
                                    ),
                                    'product_cross_sells_columns_md' => array(
                                        'id' => 'product_cross_sells_columns_md',
                                        'title' => esc_html__('Medium Devices', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on medium devices (>= 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_columns_md','2' ),
                                        'layout' => 'full',
                                    ),
                                )
                            ),
                            'product_cross_sells_columns_row_2' => array(
                                'id' => 'product_cross_sells_columns_row_2',
                                'type' => 'row',
                                'col' => 3,
                                'fields' => array(
                                    'product_cross_sells_columns_sm' => array(
                                        'id' => 'product_cross_sells_columns_sm',
                                        'title' => esc_html__('Small Devices ', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on small devices (< 768px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_columns_sm','2' ),
                                        'layout' => 'full',
                                    ),
                                    'product_cross_sells_columns' => array(
                                        'id' => 'product_cross_sells_columns',
                                        'title' => esc_html__('Extra Small Devices ', 'g5-shop'),
                                        'desc' => esc_html__('Specify your product columns on extra small devices (< 576px)', 'g5-shop'),
                                        'type' => 'select',
                                        'options' => G5CORE()->settings()->get_post_columns(),
                                        'default' => G5SHOP()->options()->get_default( 'product_cross_sells_columns','1' ),
                                        'layout' => 'full',
                                    )
                                )
                            ),
                        )
                    ),
                )
            );
        }

        public function define_meta_box($configs)
        {
            $prefix = G5SHOP()->meta_prefix;
            $configs['g5shop_meta'] = array(
                'name' => esc_html__('Shop Settings', 'g5-shop'),
                'post_type' => array('product'),
                'layout' => 'inline',
                'fields' => array(
                    "{$prefix}single_product_layout" => array(
                        'id' => "{$prefix}single_product_layout",
                        'title' => esc_html__('Layout', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your product layout', 'g5-shop'),
                        'type' => 'image_set',
                        'options' => G5SHOP()->settings()->get_single_product_layout(true),
                        'default' => ''
                    ),
                    "{$prefix}single_product_tab" => array(
                        'id' => "{$prefix}single_product_tab",
                        'title' => esc_html__('Tab Layout', 'g5-shop'),
                        'subtitle' => esc_html__('Specify your product single tab layout', 'g5-shop'),
                        'type' => 'image_set',
                        'options' => G5SHOP()->settings()->get_single_product_tab_layout(true),
                        'default' => ''
                    ),
                    "{$prefix}video_url" => array(
                        'id' => "{$prefix}video_url",
                        'title' => esc_html__('Video URL', 'g5-shop'),
                        'subtitle' => esc_html__('Enter video url', 'g5-shop'),
                        'type' => 'text'
                    ),
                    "{$prefix}swatches_enable" => G5CORE()->fields()->get_config_toggle(array(
                        'id' => "{$prefix}swatches_enable",
                        'title' => esc_html__('Swatches Enable', 'g5-shop'),
                        'subtitle' => esc_html__('Turn On this option if you want to enable swatches', 'g5-shop'),
                        'default' => ''
                    ),true),
                    "{$prefix}swatches_taxonomy" => array(
                        'id' => "{$prefix}swatches_taxonomy",
                        'title' => esc_html__( 'Swatches Attributes', 'g5-shop' ),
                        'subtitle' => esc_html__( 'Select Attributes to show on listing product', 'g5-shop' ),
                        'type' => 'selectize',
                        'options' => G5SHOP()->settings()->get_swatches_taxonomy(true),
                        'default' => '',
                        'allow_clear' => true,
                        'placeholder' => esc_html__( 'Select Attributes', 'g5-shop' ),
                        'required' => array("{$prefix}swatches_enable",'!=','off')
                    ),
                )
            );

            return $configs;
        }

        public function define_term_meta($configs) {
            $prefix                            = G5SHOP()->meta_prefix;
            $configs['g5shop_banner_meta'] = array(
                'name'      => esc_html__( 'Banner Settings', 'g5-shop' ),
                'taxonomy'  => array('product_cat'),
                'layout'    => 'inline',
                'fields'    => array(
                    "{$prefix}custom_archive_banner" => G5CORE()->fields()->get_config_toggle(array(
                        'id' => "{$prefix}custom_archive_banner",
                        'title' => esc_html__('Custom Banner', 'g5-shop'),
                        'subtitle' => esc_html__('Turn On this option if you want to custom banner on category pages', 'g5-shop'),
                        'default' => ''
                    )),
                    "{$prefix}archive_banner_type" => array(
                        'id' => "{$prefix}archive_banner_type",
                        'title' => esc_html__('Banner Type', 'g5-shop'),
                        'type' => 'button_set',
                        'options' => G5SHOP()->settings()->get_archive_banner_type(),
                        'default' => 'none',
                        'required' => array("{$prefix}custom_archive_banner",'=','on')
                    ),
                    "{$prefix}archive_banner_image" => array(
                        'id' => "{$prefix}archive_banner_image",
                        'title' => esc_html__('Banner Image', 'g5-shop'),
                        'type' => 'image',
                        'required' => array(
                            array("{$prefix}custom_archive_banner",'=','on'),
                            array("{$prefix}archive_banner_type", '=', 'image')
                        )
                    ),
                    "{$prefix}archive_banner_gallery" => array(
                        'id' => "{$prefix}archive_banner_gallery",
                        'title' => esc_html__('Banner Gallery', 'g5-shop'),
                        'type' => 'gallery',
                        'required' => array(
                            array("{$prefix}custom_archive_banner",'=','on'),
                            array("{$prefix}archive_banner_type", '=', 'gallery')
                        )
                    ),
                    "{$prefix}archive_banner_video" => array(
                        'id' => "{$prefix}archive_banner_video",
                        'title' => esc_html__('Banner Video', 'g5-shop'),
                        'subtitle' => esc_html__('Paste YouTube, Vimeo or self hosted video URL then player automatically will be generated.', 'g5-shop'),
                        'type' => 'textarea',
                        'required' => array(
                            array("{$prefix}custom_archive_banner",'=','on'),
                            array("{$prefix}archive_banner_type", '=', 'video')
                        )
                    ),
                    "{$prefix}archive_banner_custom_html" => array(
                        'id' => "{$prefix}archive_banner_custom_html",
                        'title' => esc_html__('Custom Html Content', 'g5-shop'),
                        'type' => 'ace_editor',
                        'mode' => 'html',
                        'default' => '',
                        'required' => array(
                            array("{$prefix}custom_archive_banner",'=','on'),
                            array("{$prefix}archive_banner_type", '=', 'custom_html')
                        )
                    ),
                )
            );



            return $configs;
        }

        public function term_meta_page_title($terms) {
            $terms[] = 'product_cat';
            return $terms;
        }

        public function pre_get_posts($query)
        {
            if (!is_admin() && $query->is_main_query() && ($query->is_post_type_archive( 'product' ) || $query->is_tax( get_object_taxonomies( 'product' )))) {
                $posts_per_page = absint(G5SHOP()->options()->get_option('posts_per_page'));
                if (!empty($posts_per_page)) {
                    $query->set('posts_per_page', $posts_per_page);
                }
            }

            if (!is_admin() && is_search() && $query->get('post_type') === 'product') {
                $product_cat = isset($_GET['product_cate']) ? $_GET['product_cate'] : '';
                if (!empty($product_cat)) {
                    $tax_query = $query->get('tax_query',array());
                    $tax_query[] = array(
                        'taxonomy'         => 'product_cat',
                        'terms'            => $product_cat,
                        'field' => 'term_id',
                        'operator' => 'IN'
                    );
                    $query->set('tax_query',$tax_query);
                }
            }
        }

        public function change_taxonomy_setting() {

            if (is_tax('product_cat')) {
                $prefix                            = G5SHOP()->meta_prefix;
                $queried_object = get_queried_object();
                $id = $queried_object->term_id;
                $custom_archive_banner = get_term_meta($id,"{$prefix}custom_archive_banner",true);
                if ($custom_archive_banner === 'on') {
                    $settings = array(
                        'archive_banner_type',
                        'archive_banner_image',
                        'archive_banner_gallery',
                        'archive_banner_video',
                        'archive_banner_custom_html'
                    );

                    foreach ($settings as $setting) {
                        $v = get_term_meta($id,"{$prefix}{$setting}",true);
                        G5SHOP()->options()->set_option($setting, $v);
                    }

                }
            }
        }

        public function change_single_product_setting() {
            if (is_product()) {
                $prefix                            = G5SHOP()->meta_prefix;
                $settings = array(
                    'single_product_layout',
                    'single_product_tab',
                );

                foreach ($settings as $setting) {
                    $v = get_post_meta(get_the_ID(),"{$prefix}{$setting}",true);
                    if ($v !== '') {
                        G5SHOP()->options()->set_option($setting, $v);
                    }
                }
            }
        }

        public function change_page_layout() {
            if (is_checkout() || is_cart() || is_account_page()) {
                G5CORE()->options()->layout()->set_option( 'site_layout', 'none' );
            }

            if (is_product()) {
                $single_product_layout = isset($_REQUEST['single_product_layout']) ? $_REQUEST['single_product_layout'] : '';
                if (array_key_exists($single_product_layout,G5Shop()->settings()->get_single_product_layout())) {
                    G5SHOP()->options()->set_option('single_product_layout',$single_product_layout);
                }

                $single_product_tab = isset($_REQUEST['single_product_tab']) ? $_REQUEST['single_product_tab'] : '';
                if (array_key_exists($single_product_tab,G5SHOP()->settings()->get_single_product_tab_layout())) {
                    G5SHOP()->options()->set_option('single_product_tab',$single_product_tab);
                }
            }


        }

        public function change_default_options($defaults) {
            return wp_parse_args(array(
                'product_archive__sidebar' => 'g5shop-main',
                'product_single__sidebar' => 'g5shop-main',
                'product_single__site_layout' => 'none',
                'product_single__page_title_enable' => 'off'
            ),$defaults) ;
        }

    }
}
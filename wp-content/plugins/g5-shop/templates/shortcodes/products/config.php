<?php
/**
 * The template for displaying config.php
 *
 * @package WordPress
 */
return array(
    'base' => 'g5element_products',
    'name' => esc_html__('Products', 'g5-shop'),
    'category' => G5ELEMENT()->shortcode()->get_category_name(),
    'icon'        => 'g5element-vc-icon-products',
    'description' => esc_html__( 'Display list of products', 'g5-shop' ),
    'params' => array_merge(
        array(
            array(
                'param_name' => 'cate_filter_enable',
                'heading' => esc_html__('Category Filter', 'g5-shop'),
                'type' => 'g5element_switch',
                'std' => '',
            ),
	        array(
		        'param_name' => 'cate_filter_align',
		        'heading' => esc_html__('Category Filter Align', 'g5-shop'),
		        'type' => 'g5element_button_set',
		        'value' => array_flip(G5CORE()->settings()->get_category_filter_align()),
		        'std' => '',
		        'dependency' => array('element' => 'cate_filter_enable', 'value' => 'on'),
	        ),
            array(
                'param_name' => 'post_layout',
                'heading' => esc_html__('Product Layout', 'g5-shop'),
                'description' => esc_html__('Specify your product layout', 'g5-shop'),
                'type' => 'g5element_image_set',
                'value' => G5SHOP()->settings()->get_product_layout(),
                'std' => 'grid',
                'admin_label' => true
            ),

            array(
                'param_name' => 'item_skin',
                'heading' => esc_html__('Item Skin', 'g5-shop'),
                'description' => esc_html__('Specify your product item skin', 'g5-shop'),
                'type' => 'g5element_image_set',
                'value' => G5SHOP()->settings()->get_product_skins(),
                'std' => 'skin-01',
                'admin_label' => true
            ),
            array(
                'param_name' => 'item_custom_class',
                'heading' => esc_html__( 'Item Css Classes', 'g5-shop' ),
                'description' => esc_html__( 'Add custom css classes to item', 'g5-shop' ),
                'type' => 'textfield'
            ),
            array(
                'param_name' => 'category_enable',
                'heading' => esc_html__('Show Category','g5-shop'),
                'type' => 'g5element_switch',
                'std' => '',
            ),
            array(
                'param_name' => 'rating_enable',
                'heading' => esc_html__('Show Rating','g5-shop'),
                'type' => 'g5element_switch',
                'std' => '',
            ),
            array(
                'param_name' => 'excerpt_enable',
                'heading' => esc_html__('Show Excerpt','g5-shop'),
                'type' => 'g5element_switch',
                'std' => '',
            ),
            array(
                'param_name' => 'columns_gutter',
                'heading' => esc_html__('Product Columns Gutter', 'g5-shop'),
                'description' => esc_html__('Specify your horizontal space between product.', 'g5-shop'),
                'type' => 'dropdown',
                'value' => array_flip(G5CORE()->settings()->get_post_columns_gutter()),
                'std' => '30',
            ),
            array(
                'param_name' => 'posts_per_page',
                'heading' => esc_html__('Product Per Page', 'g5-shop'),
                'description' => esc_html__('Enter number of product per page you want to display. Default 10', 'g5-shop'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'offset',
                'heading' => esc_html__('Offset posts', 'g5-shop'),
                'description' => esc_html__('Start the count with an offset. If you have a block that shows 4 posts before this one, you can make this one start from the 5\'th post (by using offset 4)', 'g5-shop'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'param_name' => 'post_paging',
                'heading' => esc_html__('Paging', 'g5-shop'),
                'description' => esc_html__('Specify your post paging mode', 'g5-shop'),
                'type' => 'dropdown',
                'value' => array_flip(G5ELEMENT()->settings()->get_post_paging()),
                'std' => 'none'
            ),
            array(
                'param_name' => 'post_animation',
                'heading' => esc_html__('Animation', 'g5-shop'),
                'description' => esc_html__('Specify your product animation', 'g5-shop'),
                'type' => 'dropdown',
                'value' => array_flip(G5CORE()->settings()->get_animation()),
                'std' => 'none'
            ),
            array(
                'type'        => 'textfield',
                'heading'     => esc_html__( 'Append Categories', 'g5-shop' ),
                'param_name'  => 'append_tabs',
                'std'         => '',
                'dependency' => array('element' => 'cate_filter_enable', 'value' => 'on'),
                'description' => esc_html__( 'Change where the categories are attached (Selector, htmlString, Array, Element, jQuery object)', 'g5-shop' ),
            ),
            g5element_vc_map_add_element_id(),
            g5element_vc_map_add_extra_class(),
        ),
        g5shop_vc_map_add_filter(),
        g5element_vc_map_add_columns(array(), esc_html__('Columns', 'g5-shop')),
        array(
            array(
                'param_name' => 'post_image_size',
                'heading' => esc_html__('Image size', 'g5-shop'),
                'description' => esc_html__('Enter image size (Example: "woocommerce_thumbnail", "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 300x400).', 'g5-shop'),
                'type' => 'textfield',
                'std' => 'woocommerce_thumbnail',
                'dependency' => array('element' => 'post_layout', 'value_not_equal_to' => array('masonry','justified')),
                'group' => esc_html__('Image Size', 'g5-shop'),
            ),
            array(
                'param_name' => 'post_image_ratio_width',
                'heading' => esc_html__('Image ratio width', 'g5-shop'),
                'description' => esc_html__('Enter width for image ratio', 'g5-shop'),
                'type' => 'g5element_number',
                'std' => '',
                'edit_field_class' => 'vc_col-sm-6 vc_column',
                'dependency' => array('element' => 'post_image_size', 'value' => 'full'),
                'group' => esc_html__('Image Size', 'g5-shop'),
            ),
            array(
                'param_name' => 'post_image_ratio_height',
                'heading' => esc_html__('Image ratio height', 'g5-shop'),
                'description' => esc_html__('Enter height for image ratio', 'g5-shop'),
                'type' => 'g5element_number',
                'std' => '',
                'edit_field_class' => 'vc_col-sm-6 vc_column',
                'dependency' => array('element' => 'post_image_size', 'value' => 'full'),
                'group' => esc_html__('Image Size', 'g5-shop'),
            ),
        ),
        array(
            g5element_vc_map_add_css_animation(),
            g5element_vc_map_add_animation_duration(),
            g5element_vc_map_add_animation_delay(),
            g5element_vc_map_add_css_editor(),
            g5element_vc_map_add_responsive(),
        )
    )
);
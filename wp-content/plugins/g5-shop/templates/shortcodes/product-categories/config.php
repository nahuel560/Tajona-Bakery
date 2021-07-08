<?php
/**
 * The template for displaying config.php
 *
 * @package WordPress
 */
return array(
    'base' => 'g5element_product_categories',
    'name' => esc_html__('Product Categories', 'g5-shop'),
    'category' => G5ELEMENT()->shortcode()->get_category_name(),
    'icon'        => 'g5element-vc-icon-product-categories',
    'description' => esc_html__( 'Display product categories loop', 'g5-shop' ),
    'params' => array_merge(
        array(
            g5shop_vc_map_add_narrow_category(),
            array(
                'param_name' => 'columns_gutter',
                'heading' => esc_html__('Columns Gutter', 'g5-shop'),
                'description' => esc_html__('Specify your horizontal space between product.', 'g5-shop'),
                'type' => 'dropdown',
                'value' => array_flip(G5CORE()->settings()->get_post_columns_gutter()),
                'std' => '30',
            ),
            array(
                'param_name' => 'number',
                'heading' => esc_html__('Total Categories', 'g5-shop'),
                'description' => esc_html__('Enter number of category you want to display', 'g5-shop'),
                'type' => 'g5element_number',
                'std' => '',
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Order by', 'g5-shop'),
                'param_name' => 'orderby',
                'value' => array(
                    esc_html__( 'Category order', 'g5-shop' ) => 'menu_order',
                    esc_html__( 'Name', 'g5-shop' ) => 'name',
                    esc_html__( 'Include', 'g5-shop' ) => 'include',
                ),
                'std' => 'menu_order',
                'description' => esc_html__('Select how to sort retrieved products.', 'g5-shop'),
            ),
            array(
                'type' => 'g5element_button_set',
                'heading' => esc_html__('Sorting', 'g5-shop'),
                'param_name' => 'order',
                'value' => array(
                    esc_html__('Descending', 'g5-shop') => 'DESC',
                    esc_html__('Ascending', 'g5-shop') => 'ASC',
                ),
                'std' => 'DESC',
                'description' => esc_html__('Select sorting order.', 'g5-shop'),
            ),
            array(
                'param_name' => 'hide_empty',
                'heading' => esc_html__('Hide Empty', 'g5-shop'),
                'type' => 'g5element_switch',
                'std' => 'on'
            ),
            array(
                'param_name' => 'slider_enable',
                'heading' => esc_html__('Slider Enable', 'g5-shop'),
                'type' => 'g5element_switch',
                'std' => ''
            ),
            g5element_vc_map_add_element_id(),
            g5element_vc_map_add_extra_class(),
        ),
        g5element_vc_map_add_columns(array(), esc_html__('Columns', 'g5-shop')),
        g5element_vc_map_add_slider(array(
            'element' => 'slider_enable',
            'value' => 'on'
        ), esc_html__('Slider Options', 'g5-shop')),
        array(
            array(
                'param_name' => 'post_image_size',
                'heading' => esc_html__('Image size', 'g5-shop'),
                'description' => esc_html__('Enter image size (Example: "thumbnail", "medium", "large", "full" or other sizes defined by theme). Alternatively enter size in pixels (Example: 300x400).', 'g5-shop'),
                'type' => 'textfield',
                'std' => '',
                'group' => esc_html__('Image Size', 'g5-shop'),
            ),
        ),
        array(
            g5element_vc_map_add_css_animation(),
            g5element_vc_map_add_animation_duration(),
            g5element_vc_map_add_animation_delay(),
        ),

        array(
            g5element_vc_map_add_css_editor(),
            g5element_vc_map_add_responsive(),
        )
    )
);

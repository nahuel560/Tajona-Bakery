<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $cat
 * @var $columns_gutter
 * @var $number
 * @var $orderby
 * @var $order
 * @var $hide_empty
 * @var $slider_enable
 * @var $el_id
 * @var $el_class
 * @var $columns_xl
 * @var $columns_lg
 * @var $columns_md
 * @var $columns_sm
 * @var $columns
 * @var $slider_rows
 * @var $slider_pagination_enable
 * @var $slider_navigation_enable
 * @var $slider_center_enable
 * @var $slider_center_padding
 * @var $slider_auto_height_enable
 * @var $slider_loop_enable
 * @var $slider_autoplay_enable
 * @var $slider_autoplay_timeout
 * @var $post_image_size
 * @var $animation_style
 * @var $animation_duration
 * @var $animation_delay
 * @var $css_editor
 * @var $responsive
 * Shortcode class
 * @var $this WPBakeryShortCode_G5Element_Product_categories
 */


$slider_enable = $columns_gutter = $number =
$el_id = $el_class =
$cat = $orderby = $order = $hide_empty =
$columns_xl = $columns_lg = $columns_md = $columns_sm = $columns =
$slider_rows = $slider_pagination_enable = $slider_navigation_enable = $slider_center_enable = $slider_center_padding = $slider_auto_height_enable = $slider_loop_enable  = $slider_autoplay_enable = $slider_autoplay_timeout =
$post_image_size =
$animation_style = $animation_duration = $animation_delay = $css_editor = $responsive = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);

$wrapper_classes = array(
    'g5element__product_categories',
    'woocommerce',
    $this->getExtraClass($el_class),
    $this->getCSSAnimation($css_animation),
    vc_shortcode_custom_css_class($css)
);


// Get terms and workaround WP bug with parents/pad counts.
$args = array(
    'orderby'    => $orderby,
    'order'      => $order,
    'hide_empty' => $hide_empty === 'on',
    'pad_counts' => true,
);


$number = absint($number);
if ( $number > 0 ) {
    $args['number'] = $number;
}

if (!empty($cat)) {
    $args['include'] =  array_filter(explode(',',$cat),'absint');
}

$product_categories = get_terms( 'product_cat', $args );

$columns_xl = absint($columns_xl);
$columns_lg = absint($columns_lg);
$columns_md = absint($columns_md);
$columns_sm = absint($columns_sm);
$columns = absint($columns);
$columns_gutter = absint($columns_gutter);
$settings = array(
    'post_columns' => array(
        'xl' => $columns_xl,
        'lg' => $columns_lg,
        'md' => $columns_md,
        'sm' => $columns_sm,
        '' => $columns,
    ),
    'columns_gutter' => $columns_gutter,
);


if ($slider_enable === 'on') {
    $slick_options = array(
        'slidesToShow'   => $columns_xl,
        'slidesToScroll' => $columns_xl,
        'centerMode'     => $slider_center_enable === 'on',
        'centerPadding'  => $slider_center_padding,
        'arrows'         => $slider_navigation_enable === 'on',
        'dots'           => $slider_pagination_enable === 'on',
        'infinite'       => $slider_center_enable === 'on' ? true :  $slider_loop_enable === 'on',
        'adaptiveHeight' => $slider_auto_height_enable === 'on',
        'autoplay'       => $slider_autoplay_enable === 'on',
        'autoplaySpeed'  => absint($slider_autoplay_timeout),
        'draggable' => true,
        'responsive'     => array(
            array(
                'breakpoint' => 1200,
                'settings'   => array(
                    'slidesToShow'   => $columns_lg,
                    'slidesToScroll' => $columns_lg,
                )
            ),
            array(
                'breakpoint' => 992,
                'settings'   => array(
                    'slidesToShow'   => $columns_md,
                    'slidesToScroll' => $columns_md,
                )
            ),
            array(
                'breakpoint' => 768,
                'settings'   => array(
                    'slidesToShow'   => $columns_sm,
                    'slidesToScroll' => $columns_sm,
                )
            ),
            array(
                'breakpoint' => 576,
                'settings'   => array(
                    'slidesToShow'   => $columns,
                    'slidesToScroll' => $columns,
                )
            )
        ),
    );

    if ($slider_rows > 1) {
        $slick_options['rows'] = $slider_rows;
        $slick_options['slidesPerRow']  = $columns_xl;
        $slick_options['slidesToShow'] = 1;
        $slick_options['slidesToScroll'] = 1;

        $slick_options['responsive'] = array(
            array(
                'breakpoint' => 1200,
                'settings'   => array(
                    'slidesPerRow'  => $columns_lg,
                    'slidesToShow'   => 1,
                    'slidesToScroll' => 1,
                )
            ),
            array(
                'breakpoint' => 992,
                'settings'   => array(
                    'slidesPerRow'  => $columns_md,
                    'slidesToShow'   => 1,
                    'slidesToScroll' => 1,
                )
            ),
            array(
                'breakpoint' => 768,
                'settings'   => array(
                    'slidesPerRow'  => $columns_sm,
                    'slidesToShow'   => 1,
                    'slidesToScroll' => 1,
                )
            ),
            array(
                'breakpoint' => 576,
                'settings'   => array(
                    'slidesPerRow'  => $columns,
                    'slidesToShow'   => 1,
                    'slidesToScroll' => 1,
                )
            )
        );
    }
    $settings['slider_rows'] = $slider_rows;
    $settings['slick'] = $slick_options;
}

G5SHOP()->listing()->set_layout_settings($settings);

$class_to_filter =  implode(' ', array_filter($wrapper_classes));
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->getShortcode(), $atts );
$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
?>
<div class="<?php echo esc_attr($css_class)?>" <?php echo implode( ' ', $wrapper_attributes ) ?>>
    <?php

    if ( $product_categories ) {
        woocommerce_product_loop_start();
        foreach ( $product_categories as $category ) {
            wc_get_template( 'content-product_cat.php', array(
                'category' => $category,
                'image_size' => $post_image_size
            ) );
        }
        woocommerce_product_loop_end();
        G5SHOP()->listing()->unset_layout_settings();
    }

    ?>
</div>

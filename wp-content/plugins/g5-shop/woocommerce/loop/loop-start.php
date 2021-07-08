<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$post_settings = &G5SHOP()->listing()->get_layout_settings();
$post_layout = isset($post_settings['post_layout']) ? $post_settings['post_layout'] : 'grid';
$layout_matrix = G5SHOP()->listing()->get_layout_matrix($post_layout);

$slick = isset($post_settings['slick']) ? $post_settings['slick'] : (isset($layout_matrix['slick']) ? $layout_matrix['slick'] : '');
$slider_rows = absint(isset($post_settings['slider_rows']) ? $post_settings['slider_rows'] :  (isset($layout_matrix['slider_rows']) ? $layout_matrix['slider_rows'] : 1));

$layout_settings = isset($layout_matrix['layout']) ? $layout_matrix['layout'] : '';
$columns = isset($layout_matrix['columns']) ? $layout_matrix['columns'] : '';
$columns_gutter =  isset($layout_matrix['columns_gutter']) ? $layout_matrix['columns_gutter'] : (isset($post_settings['columns_gutter']) ? $post_settings['columns_gutter'] : '');

$image_size = isset($post_settings['image_size']) ? $post_settings['image_size'] : (isset($layout_matrix['image_size']) ? $layout_matrix['image_size'] : 'woocommerce_thumbnail');
$image_mode = isset($post_settings['image_mode']) ? $post_settings['image_mode'] : (isset($layout_matrix['image_mode']) ? $layout_matrix['image_mode'] : '');

if ($post_layout === 'masonry') {
    $image_width = isset($post_settings['image_width']) ?  $post_settings['image_width'] : '';

    if (is_array($image_width) && isset($image_width['width'])) {
        $image_width = intval($image_width['width']);
    } else {
        $image_width = 400;
    }


    if ($image_width <= 0) {
        $image_width = 400;
    }
    $image_size = "{$image_width}x0";
}

$image_ratio = '';
if ($image_size === 'full') {
    $image_ratio_custom = isset($post_settings['image_ratio']) ? $post_settings['image_ratio'] : '';
    if (is_array($image_ratio_custom) && isset($image_ratio_custom['width']) && isset($image_ratio_custom['height'])) {
        $image_ratio_custom_width = intval($image_ratio_custom['width']);
        $image_ratio_custom_height = intval($image_ratio_custom['height']);
        if (($image_ratio_custom_width > 0) && ($image_ratio_custom_height > 0)) {
            $image_ratio = "{$image_ratio_custom_width}x{$image_ratio_custom_height}";
        }
    }

    if ($image_ratio === '') {
        $image_ratio = '1x1';
    }
}





$wrapper_classes = array(
    'g5shop__listing-wrap',
    "g5shop__layout-{$post_layout}",
    //'products'
);

$wrapper_attributes = array();

$inner_attributes = array(
    'data-items-container'
);

$inner_classes = array(
    'g5shop__listing-inner'
);


if (isset($post_settings['isMainQuery']))  {
    $wrapper_attributes[] = 'data-archive-wrapper';
}


if ($slick !== '') {
    $inner_classes[] = 'slick-slider';
    $inner_attributes[] = "data-slick-options='" . esc_attr(json_encode($slick)) . "'";
    if ($columns_gutter !== '') {
        if ($slider_rows > 1) {
            $inner_classes[] = 'slick-slider-rows';
            $inner_classes[] = "g5core__gutter-slider-rows-{$columns_gutter}";
        } else {
            $inner_classes[] = "g5core__gutter-{$columns_gutter}";
        }
    }
} else {
    if ($layout_settings !== '') {
        $inner_classes[] = 'row';
        if ($columns !== '') {
            if ($columns === 1) {
                $inner_classes[] = 'no-gutters';
            }
        }

        if ($columns_gutter !== '') {
            $inner_classes[] = "g5core__gutter-{$columns_gutter}";
        }

       /* if (isset($layout_matrix['isotope'])) {
            $inner_classes[] = 'isotope';
            $inner_attributes[] = "data-isotope-options='" . json_encode($layout_matrix['isotope']) . "'";
        }*/

        if (isset($layout_matrix['isotope'])) {
            $inner_classes[] = 'isotope';
            $inner_attributes[] = "data-isotope-options='" . json_encode($layout_matrix['isotope']) . "'";
            $wrapper_attributes[] = 'data-isotope-wrapper="true"';
            if (isset($layout_matrix['isotope']['metro'])) {
                if ($image_size === 'full') {
                    $inner_attributes[] = "data-image-size-base='" . $image_ratio . "'";
                } else {
                    $image_size_dimension = g5core_get_image_dimension($image_size);
                    if ($image_size_dimension) {
                        $inner_attributes[] = "data-image-size-base='" . $image_size_dimension['width'] . 'x' . $image_size_dimension['height'] . "'";
                    }
                }
            }
        }

    }
}
$settingId = isset($post_settings['settingId']) ? $post_settings['settingId'] : uniqid();
$post_settings['settingId'] = $settingId;
$wrapper_attributes[] = sprintf('data-items-wrapper="%s"',$settingId) ;


$paged = G5CORE()->query()->query_var_paged();
$wrapper_class = join(' ', $wrapper_classes);
$inner_class = join(' ', $inner_classes);


?>
<div <?php echo join(' ', $wrapper_attributes); ?> class="<?php echo esc_attr($wrapper_class) ?>">
    <?php
    // You can use this for adding codes before the main loop
    do_action('g5core_before_listing_wrapper');
    ?>
    <div <?php echo join(' ', $inner_attributes); ?> class="<?php echo esc_attr($inner_class); ?>">


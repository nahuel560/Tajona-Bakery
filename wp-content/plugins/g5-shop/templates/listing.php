<?php
$post_settings = &G5SHOP()->listing()->get_layout_settings();
$post_layout = isset($post_settings['post_layout']) ? $post_settings['post_layout'] : 'grid';
$post_paging = isset($post_settings['post_paging']) ? $post_settings['post_paging'] : 'pagination';
$layout_matrix = G5SHOP()->listing()->get_layout_matrix($post_layout);
$image_size = isset($post_settings['image_size']) && !empty($post_settings['image_size']) ? $post_settings['image_size'] : (isset($layout_matrix['image_size']) ? $layout_matrix['image_size'] : 'woocommerce_thumbnail');
$image_mode = isset($post_settings['image_mode']) ? $post_settings['image_mode'] : (isset($layout_matrix['image_mode']) ? $layout_matrix['image_mode'] : 'image');
$item_custom_class = isset($post_settings['item_custom_class']) ? $post_settings['item_custom_class'] : '';
$placeholder = isset($post_settings['placeholder']) ? $post_settings['placeholder'] : (isset($layout_matrix['placeholder']) ? $layout_matrix['placeholder'] : '');

$slick = isset($post_settings['slick']) ? $post_settings['slick'] : (isset($layout_matrix['slick']) ? $layout_matrix['slick'] : '');
$slider_rows = absint(isset($post_settings['slider_rows']) ? $post_settings['slider_rows'] :  (isset($layout_matrix['slider_rows']) ? $layout_matrix['slider_rows'] : 1));

$layout_settings = isset($layout_matrix['layout']) ? $layout_matrix['layout'] : '';
$columns = isset($post_settings['post_columns']) ? $post_settings['post_columns'] : '';
$columns_gutter =  isset($layout_matrix['columns_gutter']) ? $layout_matrix['columns_gutter'] : (isset($post_settings['columns_gutter']) ? $post_settings['columns_gutter'] : '');
$post_index_start = absint(isset($post_settings['index']) ? $post_settings['index'] : 0);
$post_animation = isset($post_settings['post_animation']) ? $post_settings['post_animation'] : '';
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

$post_classes = array(
    'g5core__gutter-item',
    'g5shop__product-item',
    $item_custom_class
);
$post_inner_classes = array(
    'g5core__post-item-inner',
    'g5shop__product-item-inner',
    'clearfix',
    g5core_get_animation_class($post_animation)
);




$paged = G5CORE()->query()->query_var_paged();
$post_inner_class = join(' ', $post_inner_classes);


if ($layout_settings !== '') {
    $index = intval($post_index_start);
    while (G5CORE()->query()->have_posts()) {
        G5CORE()->query()->the_post();
        $index = $index % sizeof($layout_settings);
        $current_layout = $layout_settings[$index];

        $isFirst = isset( $current_layout['isFirst'] ) ? $current_layout['isFirst'] : false;
        if ( $isFirst && ( $paged > 1 ) && in_array( $post_paging, array( 'load-more', 'infinite-scroll' ) ) ) {
            $k = $index;
            while ($isFirst) {
                if ( isset( $layout_settings[$k + 1] ) ) {
                    $current_layout = $layout_settings[$k + 1];
                    $isFirst = isset( $current_layout['isFirst'] ) ? $current_layout['isFirst'] : false;
                    $k++;
                } else {
                    continue;
                }
            }
        }

        $template = isset($current_layout['template']) ? $current_layout['template'] : '';
        $template_class = isset($current_layout['template_class']) ? $current_layout['template_class'] : "g5shop__product-{$template}";
        $post_index = intval(G5CORE()->query()->get_query()->current_post) +1;
        $current_post_classes = array(
            $template_class,
            "g5shop__product-item-{$post_index}"
        );


        if ($slick === '') {
            $current_columns = isset($current_layout['columns']) ? $current_layout['columns'] : $columns;
            if ($current_columns !== '') {
                $current_post_classes[] = is_array($current_columns) ? g5core_get_bootstrap_columns($current_columns) : ($current_columns === 1 ? 'col-12' : $current_columns);
            }
        }


        $current_image_size = isset($current_layout['image_size']) ? $current_layout['image_size'] : $image_size;
        $current_image_ratio = $image_ratio;

        $current_post_classes = wp_parse_args($current_post_classes, $post_classes);
        $current_post_class = join(' ', $current_post_classes);

        $post_inner_attributes = array();

        if (isset($current_layout['layout_ratio'])) {
            $layout_ratio = $current_layout['layout_ratio'];
            if ($image_size !== 'full') {
                $current_image_size = g5core_get_metro_image_size($image_size, $layout_ratio, $columns_gutter);
            } else {
                $current_image_ratio = g5core_get_metro_image_ratio($image_ratio, $layout_ratio);
            }
            $post_inner_attributes[] = 'data-ratio="' . $layout_ratio . '"';
        }


        do_action('g5shop_before_get_template_listing_item',$template);
        G5SHOP()->get_template("loop/listing/item/{$template}.php",array(
            'image_size' => $current_image_size,
            'image_ratio' => $current_image_ratio,
            'post_class' => $current_post_class,
            'post_inner_class' => $post_inner_class,
            'post_inner_attributes' => $post_inner_attributes,
            'image_mode' => $image_mode
        ));

	    do_action('g5shop_after_get_template_listing_item',$template);

        if ( $isFirst ) {
            unset( $layout_settings[$index] );
            $layout_settings = array_values( $layout_settings );
        }

        if ( $isFirst && $paged === 1 ) {
            $index = 0;
        } else {
            $index++;
        }
    }
} else {
    G5SHOP()->get_template( "loop/listing/{$post_layout}.php",array('post_classes' => $post_classes, 'post_inner_class' => $post_inner_class, 'columns_gutter' => $columns_gutter));
}

<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $post_classes
 * @var $post_inner_class
 * @var $columns_gutter
 */
G5CORE()->query()->set_cache('g5core_block_posts_count',3);
$total_block =  G5CORE()->query()->get_total_block();
$modern_grid_configs    = array(
    'image_size_base'     => '470x280',
    'itemSelector' => 'article',
    'columns_gutter' => $columns_gutter,
);

$image_size_base = $modern_grid_configs['image_size_base'];

$modern_grid_classes = array(
    'g5core__listing-blocks',
    'row'
);
if ($columns_gutter !== '') {
    $modern_grid_classes[] = "g5core__gutter-{$columns_gutter}";
}

$modern_grid_class = implode(' ', $modern_grid_classes);

?>
<?php for ($i = 0; $i < $total_block; $i++): ?>
    <?php
    G5CORE()->query()->delete_cache('g5core_block_posts_counter');
    if (!G5CORE()->query()->have_posts()) {
        break;
    }
    ?>
    <div data-modern-options='<?php echo esc_attr(json_encode($modern_grid_configs))?>' data-modern-grid class="<?php echo esc_attr($modern_grid_class)?>">
        <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12 col-12 g5core__modern-grid-col">
            <?php

            if (G5CORE()->query()->have_posts()) {
                G5CORE()->query()->the_post();
                $current_layout = array(
                    'template'     => 'modern-grid',
                    'layout_ratio' => '1.4x2'
                );

                $template = $current_layout['template'];
                $current_image_size = '670x590';


                $template_class = isset($current_layout['template_class']) ? $current_layout['template_class'] : "g5shop__product-{$template}";
                $posts_counter = absint(G5CORE()->query()->get_cache('g5core_block_posts_counter',1)) - 1;
                $current_post_classes = array(
                    $template_class,
                    "g5shop__product-item-{$posts_counter}"
                );


                $current_post_classes = wp_parse_args($current_post_classes, $post_classes);
                $current_post_class = join(' ', $current_post_classes);

                $post_inner_attributes = array();

                if (isset($current_layout['layout_ratio'])) {
                    $layout_ratio = isset($current_layout['layout_ratio']) ? $current_layout['layout_ratio'] : '1x1';
                    if ($image_size_base !== 'full' && $layout_ratio !== '1x1') {
                        $current_image_size = g5core_get_metro_image_size($image_size_base, $layout_ratio, $columns_gutter);
                    }
                    $post_inner_attributes[] = 'data-ratio="' . $layout_ratio . '"';
                }

                G5SHOP()->get_template("loop/listing/item/{$template}.php",array(
                    'image_size' => $current_image_size,
                    'post_class' => $current_post_class,
                    'post_inner_class' => $post_inner_class,
                    'post_inner_attributes' => $post_inner_attributes,
                ));
            }
            ?>
        </div>
        <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12 col-12 g5core__modern-grid-col">
            <?php
            if (G5CORE()->query()->have_posts()) {
                while (G5CORE()->query()->have_posts()) {
                    G5CORE()->query()->the_post();
                    $current_layout = array(
                        'template'       => 'modern-grid',
                        'layout_ratio'   => '1x1'
                    );

                    $template = $current_layout['template'];
                    $current_image_size = '470x280';


                    $template_class = isset($current_layout['template_class']) ? $current_layout['template_class'] : "g5shop__product-{$template}";
                    $posts_counter = absint(G5CORE()->query()->get_cache('g5core_block_posts_counter',1)) - 1;
                    $current_post_classes = array(
                        $template_class,
                        "g5shop__product-item-{$posts_counter}"
                    );

                    $current_post_classes = wp_parse_args($current_post_classes, $post_classes);
                    $current_post_class = join(' ', $current_post_classes);

                    $post_inner_attributes = array();

                    if (isset($current_layout['layout_ratio'])) {
                        $layout_ratio = isset($current_layout['layout_ratio']) ? $current_layout['layout_ratio'] : '1x1';
                        if ($image_size_base !== 'full' && $layout_ratio !== '1x1') {
                            $current_image_size = g5core_get_metro_image_size($image_size_base, $layout_ratio, $columns_gutter);
                        }
                        $post_inner_attributes[] = 'data-ratio="' . $layout_ratio . '"';
                    }

                    G5SHOP()->get_template("loop/listing/item/{$template}.php",array(
                        'image_size' => $current_image_size,
                        'post_class' => $current_post_class,
                        'post_inner_class' => $post_inner_class,
                        'post_inner_attributes' => $post_inner_attributes,
                    ));
                }
            }
            ?>
        </div>
    </div>
<?php endfor; ?>

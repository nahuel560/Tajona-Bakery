<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $image_size
 * @var $post_class
 * @var $post_inner_class
 * @var $placeholder
 * @var $post_inner_attributes
 * @var $image_mode
 */

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
$post_settings = &G5SHOP()->listing()->get_layout_settings();

$post_settings = wp_parse_args(array(
    //'category_enable' => true,
    'excerpt_enable' => true,
    'rating_enable' => true
), $post_settings);
?>
<article <?php wc_product_class($post_class, $product); ?>>
    <div <?php echo join(' ', $post_inner_attributes) ?> class="<?php echo esc_attr($post_inner_class); ?>">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <div class="g5core__post-featured g5shop__product-featured">
            <?php
            g5shop_render_thumbnail_markup(array(
                'image_size' => $image_size,
	            'image_mode' => $image_mode
            ));

            /**
             * woocommerce_before_shop_loop_item_title hook.
             *
             * @hooked g5shop_template_loop_sale_count_down - 10
             * @hooked g5shop_template_loop_sale_flash - 20
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
            <a href="<?php the_permalink() ?>" title="<?php the_title_attribute() ?>"
               class="g5shop_loop-product-link"></a>
        </div>

        <div class="g5shop__product-info">
            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked g5shop_template_loop_cat - 10
             * @hooked g5shop_template_loop_title - 20
             */
            do_action('woocommerce_shop_loop_item_title');

            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_price - 10
             * @hooked woocommerce_template_loop_rating - 20
             * @hooked g5shop_template_loop_swatches - 30
             * @hooked g5shop_template_loop_excerpt - 40
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>

            <div class="g5shop__product-list-actions">
                <?php

                /**
                 * g5shop_product_actions hook
                 *
                 * @hooked g5shop_template_loop_add_to_cart - 5
                 * @hooked g5shop_template_loop_quick_view - 10
                 * @hooked g5shop_template_loop_wishlist - 15
                 * @hooked g5shop_template_loop_compare - 20
                 */
                do_action('g5shop_product_list_actions');
                ?>
            </div>


        </div>

        <?php
        /**
         * Hook: woocommerce_after_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_close - 5
         * @hooked woocommerce_template_loop_add_to_cart - 10
         */
        do_action('woocommerce_after_shop_loop_item');
        ?>
    </div>
</article>

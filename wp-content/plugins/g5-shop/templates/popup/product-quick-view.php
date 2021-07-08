<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
global $product;


$wrapper_classes = array(
    'clearfix',
    'g5shop__single-product',
    'g5shop__single-product-layout-1',
    'g5shop__product-gallery-horizontal',
    'g5shop__product-quick-view',
);
?>
<div id="g5shop__popup-product-quick-view" class="woocommerce mfp-hide mfp-with-anim">
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( $wrapper_classes, $product ); ?>>

        <?php
        remove_action('woocommerce_before_single_product_summary', 'g5shop_template_single_product_video',25);
        /**
         * Hook: woocommerce_before_single_product_summary.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action( 'woocommerce_before_single_product_summary' );
        add_action('woocommerce_before_single_product_summary', 'g5shop_template_single_product_video',25);
        ?>

        <div class="summary entry-summary">
            <?php
            /**
             * Hook: woocommerce_single_product_summary.
             *
             * @hooked woocommerce_template_single_title - 5
             * @hooked woocommerce_template_single_rating - 10
             * @hooked woocommerce_template_single_price - 10
             * @hooked woocommerce_template_single_excerpt - 20
             * @hooked woocommerce_template_single_add_to_cart - 30
             * @hooked woocommerce_template_single_meta - 40
             * @hooked woocommerce_template_single_sharing - 50
             * @hooked WC_Structured_Data::generate_product_data() - 60
             */
            do_action( 'woocommerce_quick_view_product_summary' );
            ?>
        </div>
    </div>
</div>
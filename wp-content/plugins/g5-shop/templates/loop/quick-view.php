<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
global $product;
?>
<a data-nonce="<?php echo esc_attr(wp_create_nonce( 'g5shop_product_quick_view' )) ?>" data-toggle="tooltip" title="<?php esc_attr_e('Quick view', 'g5-shop') ?>" class="g5shop__quick-view" data-product_id="<?php echo esc_attr($product->get_id()); ?>" href="<?php the_permalink(); ?>"><i class="far fa-search"></i></a>

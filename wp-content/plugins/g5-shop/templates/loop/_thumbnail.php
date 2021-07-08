<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $image_size
 * @var $product WC_Product
 */
global $product;
$image_hover_effect = G5SHOP()->options()->get_option('image_hover_effect');
$secondary_image_id = '';
if ($image_hover_effect !== 'none') {
    $attachment_ids = $product->get_gallery_image_ids();
    if ($attachment_ids && isset($attachment_ids[0])) {
        $secondary_image_id = $attachment_ids[0];
    }
}
?>
<?php if ($secondary_image_id === ''): ?>
<div class="g5shop__product-thumbnail">
    <?php echo woocommerce_get_product_thumbnail( $image_size ); ?>
</div>
<?php else: ?>
<div class="g5shop__product-thumbnail g5shop__product-images-hover <?php echo esc_attr($image_hover_effect)?>">
    <div class="g5shop__product-thumb-primary">
        <?php echo woocommerce_get_product_thumbnail( $image_size );  ?>
    </div>
    <div class="g5shop__product-thumb-secondary">
        <?php echo wp_get_attachment_image( $secondary_image_id, $image_size );?>
    </div>
</div>
<?php endif; ?>

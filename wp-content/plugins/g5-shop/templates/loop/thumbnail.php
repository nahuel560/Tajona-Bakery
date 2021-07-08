<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $image_size
 * @var $image_ratio
 * @var $image_mode
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
    <?php g5shop_render_image_markup( array(
        'image_size'         => $image_size,
        'image_ratio' => $image_ratio,
        'image_mode' => $image_mode
    ) ); ?>
</div>
<?php else: ?>
<div class="g5shop__product-thumbnail g5shop__product-images-hover <?php echo esc_attr($image_hover_effect)?>">
    <div class="g5shop__product-thumb-primary">
        <?php g5shop_render_image_markup( array(
            'image_size'         => $image_size,
            'image_ratio' => $image_ratio,
            'image_mode' => $image_mode
        ) ); ?>
    </div>
    <div class="g5shop__product-thumb-secondary">
        <?php g5shop_render_image_markup( array(
            'image_size'         => $image_size,
            'image_ratio' => $image_ratio,
            'image_mode' => $image_mode,
            'image_id' => $secondary_image_id

        ) ); ?>
    </div>
</div>
<?php endif; ?>

<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
    return;
}
?>
<li>
    <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
        <?php echo $product->get_image('thumbnail'); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <span class="product-title"><?php echo wp_kses_post( $product->get_name() ); ?></span>
    </a>
    <?php echo $product->get_price_html(); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</li>
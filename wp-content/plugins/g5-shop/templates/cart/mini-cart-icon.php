<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$total = WC()->cart->get_cart_contents_count();
?>
<div class="g5shop__mini-cart-icon g5shop_header-action-icon">
    <a href="<?php echo esc_url( wc_get_cart_url())  ?>" title="<?php esc_attr_e( 'Shopping Cart', 'g5-shop' ) ?>">
        <span><?php echo esc_html($total)?></span>
        <i class="fal fa-shopping-cart"></i>
    </a>
</div>

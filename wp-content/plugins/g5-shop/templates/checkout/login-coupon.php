<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$coupons_enabled =  wc_coupons_enabled();
$checkout_login_enabled = !( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) );
?>
<div class="row g5shop__checkout-toolbar">
    <?php if ($coupons_enabled): ?>
        <div class="<?php echo esc_attr($checkout_login_enabled ? 'col-lg-6' : 'col-lg-12');?>">
            <?php woocommerce_checkout_coupon_form(); ?>
        </div>
    <?php endif; ?>
    <?php if ($checkout_login_enabled): ?>
    <div class="<?php echo esc_attr($coupons_enabled ? 'col-lg-6' : 'col-lg-12')?>">
        <?php woocommerce_checkout_login_form(); ?>
    </div>
    <?php endif; ?>
</div>

<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$url = '#';
$page_id = get_option('woocommerce_myaccount_page_id');
if(!empty($page_id)) {
    $url = get_the_permalink($page_id);
}
?>
<div class="g5shop_header-action-icon g5shop__header-action-my-account">
    <a href="<?php echo esc_url( $url)  ?>" title="<?php esc_attr_e( 'My Account', 'g5-shop' ) ?>">
        <i class="fal fa-user"></i>
    </a>
</div>
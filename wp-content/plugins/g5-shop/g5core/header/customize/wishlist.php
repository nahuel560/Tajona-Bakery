<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$url = '#';
$page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
if(!empty($page_id)) {
    $url = get_the_permalink($page_id);
}
$total = 0;
if(function_exists( 'yith_wcwl_count_all_products' ) ) {
    $total = yith_wcwl_count_all_products();
}

?>
<div class="g5shop_header-action-icon g5shop__header-action-wishlist">
    <a href="<?php echo esc_url( $url)  ?>" title="<?php esc_attr_e( 'My Wishlist', 'g5-shop' ) ?>">
        <span><?php echo esc_html($total)?></span>
        <i class="fal fa-heart"></i>
    </a>
</div>
<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (shortcode_exists('yith_wcwl_add_to_wishlist') && (get_option('yith_wcwl_show_on_loop') == 'yes')) {
    echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
}

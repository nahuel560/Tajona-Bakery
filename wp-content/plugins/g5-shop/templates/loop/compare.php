<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if ((in_array('yith-woocommerce-compare/init.php', apply_filters('active_plugins', get_option('active_plugins')))
        || in_array('yith-woocommerce-compare-premium/init.php', apply_filters('active_plugins', get_option('active_plugins'))))
    && get_option('yith_woocompare_compare_button_in_products_list') == 'yes'
) {
    if (!shortcode_exists('yith_compare_button') && class_exists('YITH_Woocompare') && function_exists('yith_woocompare_constructor')) {
        $context = isset($_REQUEST['context']) ? $_REQUEST['context'] : null;
        $_REQUEST['context'] = 'frontend';
        yith_woocompare_constructor();
        $_REQUEST['context'] = $context;
    }


    global $yith_woocompare;
    if (isset($yith_woocompare) && isset($yith_woocompare->obj)) {
        remove_action('woocommerce_after_shop_loop_item', array($yith_woocompare->obj, 'add_compare_link'), 20);
    }
    echo do_shortcode('[yith_compare_button container="false" type="link"]');
}
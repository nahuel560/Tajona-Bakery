<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<div class="g5shop__single-product-actions g5shop__product-list-actions">
    <?php

    if (shortcode_exists('yith_wcwl_add_to_wishlist')) {
	    $position = get_option( 'yith_wcwl_button_position', 'add-to-cart' );
	    if ($position != 'shortcode') {
		    echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
	    }
    }

    if ((in_array('yith-woocommerce-compare/init.php', apply_filters('active_plugins', get_option('active_plugins')))
            || in_array('yith-woocommerce-compare-premium/init.php', apply_filters('active_plugins', get_option('active_plugins'))))
        && get_option('yith_woocompare_compare_button_in_products_list') == 'yes') {
        if (!shortcode_exists('yith_compare_button') && class_exists('YITH_Woocompare') && function_exists('yith_woocompare_constructor')) {
            $context = isset($_REQUEST['context']) ? $_REQUEST['context'] : null;
            $_REQUEST['context'] = 'frontend';
            yith_woocompare_constructor();
            $_REQUEST['context'] = $context;
        }

        if (shortcode_exists('yith_compare_button')) {
            echo do_shortcode('[yith_compare_button container="false" type="link"]');
        }
    }
    ?>
</div>

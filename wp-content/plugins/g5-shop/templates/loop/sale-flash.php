<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * @var $product WC_Product_Variable
 */

global $post, $product;

?>
<div class="g5shop__loop-product-flash">
    <?php
        $product_sale_label_enable = G5SHOP()->options()->get_option('sale_label_enable');
        if ($product->is_on_sale() && $product->get_type() != 'grouped' && 'on' === $product_sale_label_enable) {
            $product_sale_flash_mode = G5SHOP()->options()->get_option('sale_flash_mode');
            if ($product_sale_flash_mode == 'text') {
                $product_sale_label_text = G5SHOP()->options()->get_option('sale_label_text');
            } else {
                if ($product->get_type() == 'variable') {
                    $sale_percent = 0;
                    $available_variations = $product->get_available_variations();
                    for ($i = 0; $i < count($available_variations); ++$i) {
                        $variation_id = $available_variations[$i]['variation_id'];
                        $variable_product = new WC_Product_Variation( $variation_id );
                        $regular_price = $variable_product->get_regular_price();
                        $sales_price = $variable_product->get_sale_price();
                        $price = $variable_product->get_price();
                        if ( $sales_price != $regular_price && $sales_price == $price ) {
                            $percentage= round((( ( $regular_price - $sales_price ) / $regular_price ) * 100));
                            if ($percentage > $sale_percent) {
                                $sale_percent = $percentage;
                            }
                        }
                    }
                    $percentage = $sale_percent;
                } else {
                    $percentage = round((( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100));
                }
                if ($percentage > 0) {
                    $product_sale_label_text = '-'.$percentage.'%';
                }
            }

            if (!empty($product_sale_label_text)) {
                echo apply_filters( 'g5shop_sale_flash', '<span class="on-sale g5shop__product-flash">' . esc_html($product_sale_label_text ). '</span>',$product_sale_label_text,'sale', $post, $product );
            }
        }

    // product featured label
    $product_featured_label_enable = G5SHOP()->options()->get_option('featured_label_enable');
    if ($product->is_featured() && 'on' === $product_featured_label_enable) {
        $product_featured_label_text = G5SHOP()->options()->get_option('featured_label_text');
        echo apply_filters( 'g5shop_sale_flash', '<span class="on-featured g5shop__product-flash">' . esc_html($product_featured_label_text ). '</span>',$product_featured_label_text,'featured', $post, $product );
    }

    // product new label
    $product_new_label_enable = G5SHOP()->options()->get_option('new_label_enable');
    if ('on' === $product_new_label_enable) {
        $product_new_label_since = G5SHOP()->options()->get_option('new_label_since');
        $diff = date_diff(wc_string_to_datetime(get_post_time('Y/m/d g:i')), wc_string_to_datetime(current_time( 'Y/m/d g:i' )));
        if($diff->days <= intval($product_new_label_since)) {
            $product_new_label_text = G5SHOP()->options()->get_option('new_label_text');
            echo apply_filters( 'g5shop_sale_flash', '<span class="on-new g5shop__product-flash">' . esc_html($product_new_label_text) . '</span>',$product_new_label_text,'new', $post, $product );
        }
    }
    ?>
</div>

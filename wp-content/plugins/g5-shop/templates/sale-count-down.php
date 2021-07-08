<?php
/**
 * The template for displaying sale count down
 *
 * @var $is_single
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
$product_sale_count_down_enable = G5SHOP()->options()->get_option('sale_count_down_enable');
if ($product_sale_count_down_enable !== 'on') {
    return;
}
global $post, $product;
$sales_price_to = '';
if ($product->is_on_sale() && $product->get_type() != 'grouped') {
    if ($product->get_type() == 'variable') {
        $available_variations = $product->get_available_variations();
        $arr_count = count($available_variations);
        for ($i = 0; $i < $arr_count; ++$i) {
            $sales_price_to_temp = '';
            $variation_id = $available_variations[$i]['variation_id'];
            $variable_product = new WC_Product_Variation( $variation_id );
            $regular_price = $variable_product->get_regular_price();
            $sales_price = $variable_product->get_sale_price();
            $price = $variable_product->get_price();
            if ( $sales_price != $regular_price && $sales_price == $price ) {
                $sales_price_to_temp = $variable_product->get_date_on_sale_to() ? $variable_product->get_date_on_sale_to()->getOffsetTimestamp() : '';
                if (isset($sales_price_to_temp) && !empty($sales_price_to_temp) && ($sales_price_to_temp > $sales_price_to)) {
                    $sales_price_to = $sales_price_to_temp;
                }
            }
        }
    } else {
        $sales_price_to = $product->get_date_on_sale_to() ? $product->get_date_on_sale_to()->getOffsetTimestamp() : '';
    }
}

if ( !empty($sales_price_to)) {

    $sales_price_to = date("Y/m/d", $sales_price_to);
    ?>
    <div class="g5shop__product-deal-countdown" data-date-end="<?php echo esc_attr($sales_price_to); ?>">
        <?php if($is_single): ?>
            <span class="g5shop__deal-heading"><?php esc_html_e('Time remaining for sale', 'g5-shop'); ?></span>
        <?php endif; ?>
        <div class="g5shop__product-deal-countdown-inner">
            <div class="g5shop__countdown-section">
                <span class="countdown-amount countdown-day"></span>
                <span class="countdown-period"><?php esc_html_e('DAY','g5-shop'); ?></span>
            </div>
            <div class="g5shop__countdown-section">
                <span class="countdown-amount countdown-hours"></span>
                <span class="countdown-period"><?php esc_html_e('HOUR','g5-shop'); ?></span>
            </div>
            <div class="g5shop__countdown-section">
                <span class="countdown-amount countdown-minutes"></span>
                <span class="countdown-period"><?php esc_html_e('MIN','g5-shop'); ?></span>
            </div>
            <div class="g5shop__countdown-section">
                <span class="countdown-amount countdown-seconds"></span>
                <span class="countdown-period"><?php esc_html_e('SEC','g5-shop'); ?></span>
            </div>
        </div>
    </div>
    <?php
}
<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
global $product;
?>
<div class="g5shop__add-to-cart" data-toggle="tooltip" title="<?php echo esc_attr($product->add_to_cart_text())?>">
    <?php
    woocommerce_template_loop_add_to_cart(array(
        'class' => implode(' ', array_filter(array(
            'product_type_' . $product->get_type(),
            $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : 'product_out_of_stock',
            $product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : ''
        )))
    ));
    ?>
</div>

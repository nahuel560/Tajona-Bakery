<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}


$post_settings = &G5SHOP()->listing()->get_layout_settings();
$item_skin = isset($post_settings['item_skin']) ? $post_settings['item_skin'] : G5SHOP()->options()->get_option('item_skin');
$image_size = isset($post_settings['image_size']) ? $post_settings['image_size'] : 'woocommerce_thumbnail';
$image_ratio = isset($post_settings['image_ratio']) ? $post_settings['image_ratio'] : '';
$image_mode = isset($post_settings['image_mode']) ? $post_settings['image_mode'] : 'image';
do_action('g5shop_before_get_template_listing_item',$item_skin);
G5SHOP()->get_template("loop/listing/item/{$item_skin}.php",array(
    'image_size' => $image_size,
    'image_ratio' => $image_ratio,
    'image_mode' => $image_mode,
    'post_class' => g5shop_get_product_class(),
    'post_inner_class' => g5shop_get_product_inner_class(),
    'post_inner_attributes' => array(),
));
do_action('g5shop_after_get_template_listing_item',$item_skin);

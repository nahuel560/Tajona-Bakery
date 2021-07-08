<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @var $category WP_Term
 * @var $image_size
 * @var $image_ratio
 */


$product_cat_class = g5shop_get_product_cat_class();
$product_cat_inner_class = g5shop_get_product_cat_inner_class();
?>
<article <?php wc_product_cat_class( $product_cat_class, $category ); ?>>
    <div class="<?php echo esc_attr($product_cat_inner_class)?>">
        <div class="g5core__post-featured g5shop__product-cat-featured">
            <?php g5shop_render_category_thumbnail_markup($category,array(
            		'image_size' => isset($image_size) ? $image_size : ''
            )); ?>
            <a class="g5shop_loop-product-link" href="<?php echo esc_url(get_term_link( $category, 'product_cat' ))?>"></a>
        </div>
        <div class="g5shop__product-cat-info">
                <h2 class="woocommerce-loop-category__title">
                    <a href="<?php echo esc_url(get_term_link( $category, 'product_cat' ))?>"><?php echo esc_html($category->name)?> <mark class="count">(<?php echo esc_html($category->count)?>)</mark></a>
                </h2>
        </div>
    </div>
</article>

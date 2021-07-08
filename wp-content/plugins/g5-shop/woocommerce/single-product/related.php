<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.9.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$product_related_enable = G5SHOP()->options()->get_option('product_related_enable');
if ($product_related_enable !== 'on') return;
$product_related_columns_gutter = absint(G5SHOP()->options()->get_option('product_related_columns_gutter'));
$product_related_columns_xl = absint(G5SHOP()->options()->get_option('product_related_columns_xl'));
$product_related_columns_lg = absint(G5SHOP()->options()->get_option('product_related_columns_lg'));
$product_related_columns_md = absint(G5SHOP()->options()->get_option('product_related_columns_md'));
$product_related_columns_sm = absint(G5SHOP()->options()->get_option('product_related_columns_sm'));
$product_related_columns = absint(G5SHOP()->options()->get_option('product_related_columns'));

$settings = array(
    'post_layout' => 'grid',
    //'item_skin' => 'skin-01',
    //'image_size' => 'woocommerce_thumbnail',
    //'image_ratio' => '',
    'columns_gutter' => $product_related_columns_gutter,
    'post_animation' => 'none',
    //'excerpt_enable' => false,
    //'category_enable' => false,
    'slick' => array(
        'arrows' => false,
        'dots' => true,
        'slidesToShow' => $product_related_columns_xl,
        'slidesToScroll' => $product_related_columns_xl,
        'responsive' => array(
            array(
                'breakpoint' => 1200,
                'settings' => array(
                    'slidesToShow' => $product_related_columns_lg,
                    'slidesToScroll' => $product_related_columns_lg,
                )
            ),
            array(
                'breakpoint' => 992,
                'settings' => array(
                    'slidesToShow' => $product_related_columns_md,
                    'slidesToScroll' => $product_related_columns_md,
                )
            ),
            array(
                'breakpoint' => 768,
                'settings' => array(
                    'slidesToShow' => $product_related_columns_sm,
                    'slidesToScroll' => $product_related_columns_sm,
                )
            ),
            array(
                'breakpoint' => 576,
                'settings' => array(
                    'slidesToShow' => $product_related_columns,
                    'slidesToScroll' => $product_related_columns,
                )
            )
        ),
    )
);

$settings = apply_filters('g5shop_single_related_layout_setting',$settings);


G5SHOP()->listing()->set_layout_settings($settings);

if ($related_products) : ?>

    <section class="related products">

	    <?php
	    $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );

	    if ( $heading ) :
		    ?>
		    <h2><?php echo esc_html( $heading ); ?></h2>
	    <?php endif; ?>

        <?php woocommerce_product_loop_start(); ?>

        <?php foreach ($related_products as $related_product) : ?>

            <?php
            $post_object = get_post($related_product->get_id());

            setup_postdata($GLOBALS['post'] =& $post_object);

            wc_get_template_part('content', 'product'); ?>

        <?php endforeach; ?>

        <?php woocommerce_product_loop_end(); ?>

    </section>

<?php endif;

wp_reset_postdata();

G5SHOP()->listing()->unset_layout_settings();
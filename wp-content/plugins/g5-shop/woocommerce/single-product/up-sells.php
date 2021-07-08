<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$product_up_sells_enable = G5SHOP()->options()->get_option('product_up_sells_enable');
if ($product_up_sells_enable !== 'on') return;
$product_up_sells_columns_gutter = absint(G5SHOP()->options()->get_option('product_up_sells_columns_gutter'));
$product_up_sells_columns_xl = absint(G5SHOP()->options()->get_option('product_up_sells_columns_xl'));
$product_up_sells_columns_lg = absint(G5SHOP()->options()->get_option('product_up_sells_columns_lg'));
$product_up_sells_columns_md = absint(G5SHOP()->options()->get_option('product_up_sells_columns_md'));
$product_up_sells_columns_sm = absint(G5SHOP()->options()->get_option('product_up_sells_columns_sm'));
$product_up_sells_columns = absint(G5SHOP()->options()->get_option('product_up_sells_columns'));

$settings = array(
    'post_layout' => 'grid',
    //'item_skin' => 'skin-01',
    //'image_size' => 'woocommerce_thumbnail',
    //'image_ratio' => '',
    'columns_gutter' => $product_up_sells_columns_gutter,
    'post_animation' => 'none',
    //'excerpt_enable' => false,
    //'category_enable' => false,
    'slick' =>  array(
        'arrows' => false,
        'dots' => true,
        'slidesToShow'   => $product_up_sells_columns_xl,
        'slidesToScroll' => $product_up_sells_columns_xl,
        'responsive'     => array(
            array(
                'breakpoint' => 1200,
                'settings'   => array(
                    'slidesToShow'   => $product_up_sells_columns_lg,
                    'slidesToScroll' => $product_up_sells_columns_lg,
                )
            ),
            array(
                'breakpoint' => 992,
                'settings'   => array(
                    'slidesToShow'   => $product_up_sells_columns_md,
                    'slidesToScroll' => $product_up_sells_columns_md,
                )
            ),
            array(
                'breakpoint' => 768,
                'settings'   => array(
                    'slidesToShow'   => $product_up_sells_columns_sm,
                    'slidesToScroll' => $product_up_sells_columns_sm,
                )
            ),
            array(
                'breakpoint' => 576,
                'settings'   => array(
                    'slidesToShow'   => $product_up_sells_columns,
                    'slidesToScroll' => $product_up_sells_columns,
                )
            )
        ),
    )
);

$settings = apply_filters('g5shop_single_up_sells_layout_setting',$settings);

G5SHOP()->listing()->set_layout_settings($settings);

if ( $upsells ) : ?>

	<section class="up-sells upsells products">

		<h2><?php esc_html_e( 'You may also like&hellip;', 'woocommerce' ); ?></h2>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $upsells as $upsell ) : ?>

				<?php
					$post_object = get_post( $upsell->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object );

					wc_get_template_part( 'content', 'product' ); ?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

<?php endif;

wp_reset_postdata();

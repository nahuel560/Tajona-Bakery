<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $available_variations
 * @var $variation_attributes
 */
global $product;
$wrapper_attributes = array();
$wrapper_attributes[] = 'class="g5shop__loop-swatches g5shop__swatches"';
$wrapper_attributes[] = sprintf('data-product_variations="%s"', esc_attr(json_encode($available_variations)));
$swatches_taxonomy = G5SHOP()->options()->get_option('swatches_taxonomy');
$prefix = G5SHOP()->meta_prefix;
$custom_swatches_taxonomy = get_post_meta(get_the_ID(),"{$prefix}swatches_taxonomy",true);
if ($custom_swatches_taxonomy !== '') {
    $swatches_taxonomy = $custom_swatches_taxonomy;
}



if (!array_key_exists($swatches_taxonomy,$variation_attributes)) return;
$variation_attribute = $variation_attributes[$swatches_taxonomy];
$attribute_type =  G5SHOP()->swatches()->get_attribute_type($swatches_taxonomy);
$product_terms = wp_get_post_terms( $product->get_id(), $swatches_taxonomy, array( 'hide_empty' => false ) );
$tooltip_options = array(
        'container' => 'body'
);


?>
<div <?php echo implode(' ', $wrapper_attributes)?>>
    <div class="g5shop__swatch g5shop__swatch-<?php echo esc_attr($attribute_type)?> g5core__tooltip-wrap" data-attribute="<?php echo esc_attr($swatches_taxonomy)?>" data-tooltip-options="<?php echo esc_attr(json_encode($tooltip_options))?>">
        <?php foreach ($product_terms as $term) {
            if ( in_array( $term->slug, $variation_attribute, true ) ) {
                G5SHOP()->swatches()->get_term_html($term,$attribute_type);
            }
        } ?>
    </div>
    <a class="g5shop__reset_variations" href="#" style="display: none;"><?php esc_html_e('Clear', 'g5-shop') ?></a>
</div>

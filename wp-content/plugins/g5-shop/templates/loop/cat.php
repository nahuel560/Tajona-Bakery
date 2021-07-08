<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $cat
 */
?>
<h3 class="g5shop__loop-product-cat"><a href="<?php echo esc_url(get_term_link($cat,'product_cat'))?>" title="<?php echo esc_attr($cat->name)?>"><?php echo esc_html($cat->name)?></a></h3>

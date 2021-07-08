<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$search_ajax_enable = G5CORE()->options()->get_option('search_ajax_enable');
$current_cat = '';
if (is_tax('product_cat')) {
    global $wp_query;
    $term = $wp_query->get_queried_object();
    $current_cat = $term->term_id;
} elseif (is_search()) {
    $current_cat = isset($_GET['product_cate']) ? $_GET['product_cate'] : '';
}
$wrapper_classes = array(
    'g5core-search-form',
    'g5shop__search-product'
);

if ($search_ajax_enable === 'on') {
    $wrapper_classes[] = 'g5core-search-ajax';
}
$wrapper_class = implode(' ', $wrapper_classes);
?>
<form action="<?php echo esc_url(home_url('/')) ?>" method="get" class="<?php echo esc_attr($wrapper_class);?>">
    <div class="g5shop__search-product-cat">
        <?php wp_dropdown_categories(array(
            'show_option_all' => esc_html__('All Categories', 'g5-shop'),
            'name' => 'product_cate',
            'taxonomy' => 'product_cat',
            'hide_if_empty' => 1,
            'selected' => $current_cat
        )) ?>
    </div>
    <div class="g5shop__search-product-input">
        <input name="s" type="search"
               placeholder="<?php $search_ajax_enable === 'on' ? esc_attr_e('Type to search...', 'g5-shop') : esc_attr_e('Type and hit enter', 'g5-shop') ?>"
               autocomplete="off">
        <input type="hidden" name="post_type" value="product">
        <span class="remove" title="<?php echo esc_attr__('Remove search terms', 'g5-shop') ?>"><i
                    class="fal fa-times"></i></span>
        <button type="submit"><i class="fal fa-search"></i></button>
        <div class="result"></div>
    </div>
    <input type="hidden" name="action" value="g5shop_search_product">
    <?php wp_nonce_field('g5shop_search_product','_g5shop_search_product_nonce'); ?>
</form>

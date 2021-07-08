<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$product_layout = g5shop_get_product_switch_layout();
$base_link = g5shop_get_current_page_url();
?>
<div class="g5shop__switch-layout">
    <a class="<?php echo esc_attr($product_layout === 'grid' ? 'active' : '')?>" href="<?php echo esc_url($link = add_query_arg('view','grid',$base_link))?>" title="<?php esc_attr_e( 'Grid View', 'g5-shop' )?>" data-layout="grid"><i class="fas fa-th"></i></a>
    <a class="<?php echo esc_attr($product_layout === 'list' ? 'active' : '')?>" href="<?php echo esc_url($link = add_query_arg('view','list',$base_link))?>" title="<?php esc_attr_e( 'List View', 'g5-shop' )?>" data-layout="list"><i class="fas fa-bars"></i></a>
</div>

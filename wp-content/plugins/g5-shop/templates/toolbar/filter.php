<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<a href="#" class="g5shop__filter-button"><i class="far fa-filter"></i> <?php esc_html_e( 'Filter', 'g5-shop' ) ?></a>
<?php add_action('g5shop_after_shop_toolbar','g5shop_template_shop_filter',10); ?>

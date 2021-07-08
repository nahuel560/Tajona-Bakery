<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<div id="g5shop__search_product_popup" class="g5core-search-popup g5shop__search-product-popup mfp-hide mfp-with-anim">
	<img src="<?php echo esc_url(get_theme_file_uri('assets/images/search-popup.png'))?>" alt="<?php esc_attr_e('Search','porus')?>">
    <?php g5shop_template_search_product(); ?>
	<p><?php echo wp_kses_post(__('We offer something different to local and foreign patrons and ensure you enjoy a memorable food experience every time.','porus'))  ?></p>
</div>

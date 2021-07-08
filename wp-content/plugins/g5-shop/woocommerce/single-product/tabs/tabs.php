<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters('woocommerce_product_tabs', array());
$single_product_tab = G5SHOP()->options()->get_option('single_product_tab');
if (!empty($tabs)) : ?>
    <div class="g5shop__single-product-tabs <?php echo esc_attr($single_product_tab)?>">
        <div class="g5shop__tabs-container">
            <ul class="nav nav-tabs" role="tablist">
                <?php $index = 0; ?>
                <?php foreach ($tabs as $key => $tab) : ?>
                    <li class="nav-item">
                        <a class="tab-<?php echo esc_attr($key); ?> nav-link <?php echo esc_attr($index === 0 ? 'active' : '')?>"
                           id="<?php echo esc_attr($key); ?>-tab"
                           data-toggle="tab"
                           href="#tab-<?php echo esc_attr($key); ?>"
                           role="tab"
                           aria-controls="tab-<?php echo esc_attr($key); ?>"
                           aria-selected="<?php echo esc_attr($index === 0 ? 'true' : 'false')?>">
                            <?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?>
                        </a>
                    </li>
                    <?php $index++; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="tab-content g5shop__panels-container">
            <?php $index = 0; ?>
            <?php foreach ($tabs as $key => $tab) : ?>
                <div class="tab-pane fade <?php echo esc_attr($index === 0 ? 'show active' : '')?>" id="tab-<?php echo esc_attr($key); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($key); ?>-tab">
                    <div class="g5shop__tab-panel">
                        <div class="g5shop__panel-heading">
                            <h4 data-toggle="collapse" data-target="#collapse-<?php echo esc_attr($key); ?>" aria-expanded="<?php echo esc_attr($index === 0 ? 'true' : 'false')?>" aria-controls="collapse-<?php echo esc_attr($key); ?>"><?php echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key); ?></h4>
                        </div>
                        <div id="collapse-<?php echo esc_attr($key); ?>" class="collapse <?php echo esc_attr($index === 0 ? 'show' : '')?>"  data-parent=".g5shop__panels-container">
                            <div class="g5shop__panel-body">
                                <?php if (isset($tab['callback'])) {
                                    call_user_func($tab['callback'], $key, $tab);
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $index++; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

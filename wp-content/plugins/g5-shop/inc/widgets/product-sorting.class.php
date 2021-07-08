<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Shop_Widget_Product_Sorting')) {
    class G5Shop_Widget_Product_Sorting extends GSF_Widget {
        public function __construct()
        {
            $this->widget_cssclass = 'g5shop__widget-product-sorting';
            $this->widget_id = 'g5shop__widget_product_sorting';
            $this->widget_name = esc_html__('G5Plus: Product Sorting', 'g5-shop');
            $this->widget_description = esc_html__( 'Display a product sorting list', 'g5-shop' );
            $this->settings = array(
                'fields' => array(
                    array(
                        'id'      => 'title',
                        'title'   => esc_html__('Title', 'g5-shop'),
                        'type'    => 'text',
                        'default' => esc_html__( 'Sort by', 'g5-shop' ),
                    ),
                )
            );
            parent::__construct();
        }

        function widget($args, $instance)
        {
            if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
                return;
            }

            if (!is_shop() && !is_product_taxonomy() && !is_search()) return;

            if ($this->get_cached_widget($instance)) {
                return;
            }

            $show_default_orderby = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ) );
            $catalog_orderby_options = apply_filters(
                'woocommerce_catalog_orderby',
                array(
                    'menu_order' => __( 'Default sorting', 'woocommerce' ),
                    'popularity' => __( 'Sort by popularity', 'woocommerce' ),
                    'rating'     => __( 'Sort by average rating', 'woocommerce' ),
                    'date'       => __( 'Sort by latest', 'woocommerce' ),
                    'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
                    'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
                )
            );

            $default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
            $orderby         = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.

            if ( wc_get_loop_prop( 'is_search' ) ) {
                $catalog_orderby_options = array_merge( array( 'relevance' => __( 'Relevance', 'woocommerce' ) ), $catalog_orderby_options );

                unset( $catalog_orderby_options['menu_order'] );
            }

            if ( ! $show_default_orderby ) {
                unset( $catalog_orderby_options['menu_order'] );
            }

            if ( ! wc_review_ratings_enabled() ) {
                unset( $catalog_orderby_options['rating'] );
            }

            if ( ! array_key_exists( $orderby, $catalog_orderby_options ) ) {
                $orderby = current( array_keys( $catalog_orderby_options ) );
            }

            $base_link = g5shop_widget_get_current_page_url();
            extract($args, EXTR_SKIP);
            ob_start();
            $this->widget_start($args,$instance);
            ?>
            <ul class="g5shop__product-sorting">
                <?php foreach ($catalog_orderby_options as $k => $v): ?>
                    <?php $link = add_query_arg( 'orderby',  $k , $base_link );  ?>
                    <li class="<?php echo esc_attr($orderby === $k ? 'current' : '')?>">
                        <a href="<?php echo esc_url($link);?>" title="<?php echo esc_attr($v)?>"><?php echo esc_html($v)?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php
            $this->widget_end($args);
            echo $this->cache_widget( $args, ob_get_clean() ); // WPCS: XSS ok.
        }
    }
}
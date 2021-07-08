<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Shop_Widget_Price_Filter')) {
    class G5Shop_Widget_Price_Filter extends GSF_Widget {

        public function __construct()
        {
            $this->widget_cssclass = 'g5shop__widget-price-filter';
            $this->widget_id = 'g5shop__widget_price_filter';
            $this->widget_name = esc_html__('G5Plus: Product Price Filter', 'g5-shop');
            $this->widget_description = esc_html__( 'Display a product price filter list', 'g5-shop' );
            $this->settings = array(
                'fields' => array(
                    array(
                        'id'      => 'title',
                        'title'   => esc_html__('Title', 'g5-shop'),
                        'type'    => 'text',
                        'default' => esc_html__( 'Filter by price', 'g5-shop' ),
                    ),
                    array(
                        'id' => 'max_price_ranges',
                        'title' => __('Max price ranges', 'g5-shop'),
                        'type'       => 'text',
                        'input_type' => 'number',
                        'args' => array(
                            'min' => '1',
                            'max' => '20',
                            'step' => '1'
                        ),
                        'default' => 5,
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


            extract($args, EXTR_SKIP);

            $max_price_ranges = (!empty($instance['max_price_ranges'])) ? intval($instance['max_price_ranges']) : 5;

            $min_price = isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : '';
            $max_price = isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : '';
            $base_link = g5shop_widget_get_current_page_url();

            // Find min and max price in current result set
            $prices = $this->get_filtered_price();
            $min = floor($prices->min_price);
            $max = ceil($prices->max_price);

            if ($min == $max) {
                return;
            }

            /**
             * Adjust max if the store taxes are not displayed how they are stored.
             * Min is left alone because the product may not be taxable.
             * Kicks in when prices excluding tax are displayed including tax.
             */
            if ( wc_tax_enabled() && 'incl' === get_option( 'woocommerce_tax_display_shop' ) && ! wc_prices_include_tax() ) {
                $tax_classes = array_merge( array( '' ), WC_Tax::get_tax_classes() );
                $class_max   = $max;

                foreach ( $tax_classes as $tax_class ) {
                    if ( $tax_rates = WC_Tax::get_rates( $tax_class ) ) {
                        $class_max = $max + WC_Tax::get_tax_total( WC_Tax::calc_exclusive_tax( $max, $tax_rates ) );
                    }
                }

                $max = $class_max;
            }

            $links = $this->generate_price_links($max, $min_price, $max_price,$max_price_ranges );
            if (empty($links)) return;

            ob_start();
            $this->widget_start($args,$instance);
            ?>
                <ul class="g5shop__price-filter">
                    <?php foreach ($links as $link): ?>
                        <li class="<?php echo esc_attr($link['class'])?>">
                            <a href="<?php echo esc_url($link['href'])?>"><?php echo ($link['title'])?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php
            $this->widget_end($args);
            echo $this->cache_widget( $args, ob_get_clean() ); // WPCS: XSS ok.
        }

        protected function get_filtered_price()
        {
            global $wpdb, $wp_query;
            $args = $wp_query->query_vars;
            $tax_query = isset($args['tax_query']) ? $args['tax_query'] : array();
            $meta_query = isset($args['meta_query']) ? $args['meta_query'] : array();

            if (!empty($args['taxonomy']) && !empty($args['term'])) {
                $tax_query[] = array(
                    'taxonomy' => $args['taxonomy'],
                    'terms' => array($args['term']),
                    'field' => 'slug',
                );
            }

            foreach ($meta_query as $key => $query) {
                if (!empty($query['price_filter']) || !empty($query['rating_filter'])) {
                    unset($meta_query[$key]);
                }
            }

            $meta_query = new WP_Meta_Query($meta_query);
            $tax_query = new WP_Tax_Query($tax_query);

            $meta_query_sql = $meta_query->get_sql('post', $wpdb->posts, 'ID');
            $tax_query_sql = $tax_query->get_sql($wpdb->posts, 'ID');

            $sql = "SELECT min( CAST( price_meta.meta_value AS UNSIGNED ) ) as min_price, max( CAST( price_meta.meta_value AS UNSIGNED ) ) as max_price FROM {$wpdb->posts} ";
            $sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
            $sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode("','", array_map('esc_sql', apply_filters('woocommerce_price_filter_meta_keys', array('_price')))) . "')
					AND price_meta.meta_value > '' ";
            $sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

            return $wpdb->get_row($sql);
        }

        private function generate_price_links( $max, $min_price, $max_price ,$steps) {
            $links = array();

            // Remember current filters/search
            $link          = g5shop_widget_get_current_page_url();
            $link_no_price = remove_query_arg( 'min_price', $link );
            $link_no_price = remove_query_arg( 'max_price', $link_no_price );

            $need_more = false;

            $step_value = $max / $steps;

            if ( $step_value < 10 ) {
                $step_value = 10;
            }

            $step_value = round( $step_value, - 1 );

            // Link to all prices
            $links[] = array(
                'href'  => $link_no_price,
                'title' => esc_html__( 'All', 'g5-shop' ),
                'class' => '',
            );

            for ( $i = 0; $i < (int) $steps; $i ++ ) {

                $step_class = $href = '';

                $step_min = $step_value * $i;

                $step_max = $step_value * ( $i + 1 );

                if ( $step_max > $max ) {
                    $need_more = true;
                    $i ++;
                    break;
                }

                $href = add_query_arg( 'min_price', $step_min, $link );
                $href = add_query_arg( 'max_price', $step_max, $href );

                $step_title = wc_price( $step_min ) . ' - ' . wc_price( $step_max );

                if ( ! empty( $min_price ) && ! empty( $max_price ) && ( $min_price >= $step_min && $max_price <= $step_max ) || ( $i == 0 && ! empty( $max_price ) && $max_price <= $step_max ) ) {
                    $step_class = 'current';
                }

                $links[] = array(
                    'href'  => $href,
                    'title' => $step_title,
                    'class' => $step_class,
                );
            }

            if ( $max > $step_max ) {
                $need_more = true;
                $step_min  = $step_value * $i;
            }

            if ( $need_more ) {

                $step_class = $href = '';

                $href = add_query_arg( 'min_price', $step_min, $link );
                $href = add_query_arg( 'max_price', $max, $href );

                $step_title = wc_price( $step_min ) . ' +';

                if ( $min_price >= $step_min && $max_price <= $max ) {
                    $step_class = 'current-state';
                }

                $links[] = array(
                    'href'  => $href,
                    'title' => $step_title,
                    'class' => $step_class,
                );
            }

            return $links;
        }
    }
}
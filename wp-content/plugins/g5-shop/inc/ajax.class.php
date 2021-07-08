<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Shop_Ajax')) {
    class G5Shop_Ajax {
        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init() {
            add_action('wp_ajax_nopriv_g5shop_search_product', array($this,'search_result'));
            add_action('wp_ajax_g5shop_search_product', array($this,'search_result'));

            add_action('wp_ajax_nopriv_g5shop_product_quick_view',array($this,'product_quick_view'));
            add_action('wp_ajax_g5shop_product_quick_view',array($this,'product_quick_view'));
        }

        public function search_result()
        {
            check_ajax_referer('g5shop_search_product','_g5shop_search_product_nonce');
            global $wpdb;
            $keyword = isset($_REQUEST['s']) ? $_REQUEST['s'] : '';
            if (empty($keyword)) {
                wp_send_json_error();
            }

            $keyword = $wpdb->esc_like($keyword);
            $search_popup_result_amount = G5CORE()->options()->get_option('search_popup_result_amount');
            if (empty($search_popup_result_amount)) {
                $search_popup_result_amount = 8;
            }

            $args = array(
                's' => $keyword,
                'post_status' => 'publish',
                'ignore_sticky_posts' => true,
                'orderby' => 'date',
                'order' => 'DESC',
                'posts_per_page' => $search_popup_result_amount,
                'post_type' => 'product',
                'meta_query' => WC()->query->get_meta_query(),
                'tax_query' => WC()->query->get_tax_query()
            );
            $product_cate = isset($_REQUEST['product_cate']) ? $_REQUEST['product_cate'] : '';
            if (!empty($product_cate)) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'terms' => $product_cate,
                    'operator' => 'IN'
                );
            }

            $args = apply_filters('g5shop_search_product_args',$args);

            $query = new WP_Query($args);
            ?>
            <ul>
                <?php if ($query->have_posts()): ?>
                    <?php
                    while ($query->have_posts()) {
                        $query->the_post();
                        G5SHOP()->get_template('search-item.php');
                    }
                    ?>
                <?php else: ?>
                    <li class="nothing"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'g5-shop'); ?></li>
                <?php endif; ?>
            </ul>
            <?php
            wp_reset_postdata();
            wp_die();
        }

        public function product_quick_view() {
            check_ajax_referer('g5shop_product_quick_view');
            $product_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
            global $post, $product;
            $post = get_post($product_id);
            setup_postdata($post);
            $product = wc_get_product( $product_id );
            G5SHOP()->get_template('popup/product-quick-view.php');
            wp_reset_postdata();
            wp_die();
        }
    }
}
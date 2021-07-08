<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
if (!class_exists('G5Shop_ShortCodes')) {
    class G5Shop_ShortCodes {
        private static $_instance;
        public static function getInstance()
        {
            if (self::$_instance == NULL) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function init() {
            add_filter('g5element_shortcodes_list',array($this,'add_shortcodes_list'));
            add_action( 'vc_after_mapping', array($this,'auto_complete') );
            add_filter('g5element_vc_lean_map_config',array($this,'vc_lean_map_config'),10,2);
            add_filter('g5element_autoload_class_path',array($this,'change_autoload_class_path'),10,3);
            add_filter('g5element_shortcode_template',array($this,'change_shortcode_template'),10,2);
            add_filter('g5element_shortcode_listing_query_args',array($this,'set_query_args'),10,2);
        }

        public function vc_lean_map_config($vc_map_config,$key) {
            if (in_array($key,$this->get_shortcodes())) {
                $file_name = str_replace('_', '-', $key);
                $vc_map_config = G5SHOP()->locate_template("shortcodes/{$file_name}/config.php");
            }
            return $vc_map_config;
        }

        public function change_autoload_class_path($path,$shortcode,$file_name) {
            if (in_array($shortcode,$this->get_shortcodes())) {
                $path = G5SHOP()->locate_template("shortcodes/{$file_name}/{$file_name}.php");
            }
            return $path;
        }

        public function change_shortcode_template($template, $template_name) {
            if (in_array($template_name,$this->get_shortcodes())) {
                $template_name = str_replace('_', '-', $template_name);
                $template = G5SHOP()->locate_template("shortcodes/{$template_name}/template.php");
            }
            return $template;
        }

        public function get_shortcodes() {
            return apply_filters('g5shop_shortcodes',array(
                'products',
                'products_slider',
                'product_tabs',
                'product_slider_tabs',
                'product_categories'
            ));
        }

        public function add_shortcodes_list($shortcodes) {
            return wp_parse_args($this->get_shortcodes(),$shortcodes);
        }

        public function get_auto_complete_fields() {
            return apply_filters('g5shop_auto_complete_fields',array(
                'g5element_products_ids',
                'g5element_products_slider_ids',
                'g5element_product_tabs_tabs_ids',
                'g5element_product_slider_tabs_tabs_ids',
            ));
        }



        public function auto_complete() {
            $auto_complete_fields = $this->get_auto_complete_fields();
            foreach ($auto_complete_fields as $auto_complete_field) {
                //Filters For autocomplete param:
                add_filter( "vc_autocomplete_{$auto_complete_field}_callback", array(&$this,'post_search',), 10, 1 ); // Get suggestion(find). Must return an array
                add_filter( "vc_autocomplete_{$auto_complete_field}_render", array(&$this,'post_render',), 10, 1 ); // Render exact product. Must return an array (label,value)
            }
        }

        public function post_search( $search_string ) {
            $query = $search_string;
            $data = array();
            $args = array(
                's' => $query,
                'post_type' => 'product',
            );
            $args['vc_search_by_title_only'] = true;
            $args['numberposts'] = - 1;
            if ( 0 === strlen( $args['s'] ) ) {
                unset( $args['s'] );
            }
            add_filter( 'posts_search', 'vc_search_by_title_only', 500, 2 );
            $posts = get_posts( $args );
            if ( is_array( $posts ) && ! empty( $posts ) ) {
                foreach ( $posts as $post ) {
                    $data[] = array(
                        'value' => $post->ID,
                        'label' => $post->post_title,
                        'group' => $post->post_type,
                    );
                }
            }

            return $data;
        }

        public function post_render( $value ) {
            $post = get_post( $value['value'] );
            return is_null( $post ) ? false : array(
                'label' => $post->post_title,
                'value' => $post->ID
            );
        }

        public function set_query_args($query_args,$atts) {
            if ($query_args['post_type'] === 'product') {
                $query_args['meta_query'] = array();
                $query_args['tax_query'] = array(
                    'relation' => 'AND',
                );

                $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                    'operator' => 'NOT IN',
                );
                $query_args['post_parent'] = 0;


                if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
                    $query_args['tax_query'][] = array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'term_taxonomy_id',
                            'terms'    => $product_visibility_term_ids['outofstock'],
                            'operator' => 'NOT IN',
                        ),
                    ); // WPCS: slow query ok.
                }

                if (!isset($atts['show'])) {
                    $atts['show'] = '';
                }

                switch ( $atts['show'] ) {
                    case 'featured':
                        $query_args['tax_query'][] = array(
                            'taxonomy' => 'product_visibility',
                            'field'    => 'term_taxonomy_id',
                            'terms'    => $product_visibility_term_ids['featured'],
                        );
                        break;
                    case 'onsale':
                        $product_ids_on_sale    = wc_get_product_ids_on_sale();
                        $product_ids_on_sale[]  = 0;
                        $query_args['post__in'] = $product_ids_on_sale;
                        break;
                    case 'new-in':
                        $query_args['orderby'] = 'date';
                        $query_args['order'] = 'DESC';
                        break;
                    case 'top-rated':
                        $query_args['meta_key'] = '_wc_average_rating';
                        $query_args['orderby'] = 'meta_value_num';
                        $query_args['order'] = 'DESC';
                        $query_args['meta_query'] = WC()->query->get_meta_query();
                        $query_args['tax_query'] = WC()->query->get_tax_query();
                        break;
                    case 'recent-review':
                        add_filter( 'posts_clauses', array($this, 'order_by_comment_date_post_clauses' ) );
                        break;
                    case 'best-selling' :
                        $query_args['meta_key'] = 'total_sales';
                        $query_args['orderby'] = 'meta_value_num';
                        break;
                    case 'products':
                        $query_args['post__in'] = array_filter(explode(',',$atts['ids']),'absint');
                        $query_args['posts_per_page'] = -1;
                        $query_args['orderby'] = 'post__in';
                        break;
                }


                if (in_array($atts['show'],array('all','sale','featured'))) {
                    switch ( $atts['orderby'] ) {
                        case 'price':
                            $query_args['meta_key'] = '_price'; // WPCS: slow query ok.
                            $query_args['orderby']  = 'meta_value_num';
                            break;
                        case 'rand':
                            $query_args['orderby'] = 'rand';
                            break;
                        case 'sales':
                            $query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
                            $query_args['orderby']  = 'meta_value_num';
                            break;
                        default:
                            $query_args['orderby'] = 'date';
                    }
                }

                if ($atts['show'] !== 'product' && !empty($atts['cat'])) {
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'product_cat',
                        'terms' => array_filter(explode(',',$atts['cat']),'absint'),
                        'field' => 'id',
                        'operator' => 'IN'
                    );
                }

            }

            return $query_args;
        }

        public function order_by_comment_date_post_clauses($args){
            global $wpdb;
            $args['join'] .= "
                LEFT JOIN (
                    SELECT comment_post_ID, MAX(comment_date)  as  comment_date
                    FROM $wpdb->comments
                    WHERE comment_approved = 1
                    GROUP BY comment_post_ID
                ) as wp_comments ON($wpdb->posts.ID = wp_comments.comment_post_ID)
            ";
            $args['orderby'] = "wp_comments.comment_date DESC";
            return $args;
        }
    }
}
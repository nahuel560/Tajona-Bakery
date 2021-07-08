<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function g5shop_get_product_switch_layout() {
    $product_layout = 'grid';
    $shop_toolbar = G5SHOP()->options()->get_option('shop_toolbar');
    if (!is_array($shop_toolbar)) return $product_layout;
    $shop_toolbar_left = (!isset($shop_toolbar['left']) || !is_array($shop_toolbar['left']) || (count($shop_toolbar['left']) === 0)) ? false : $shop_toolbar['left'];
    $shop_toolbar_right = (!isset($shop_toolbar['right']) || !is_array($shop_toolbar['right']) || (count($shop_toolbar['right']) === 0)) ? false : $shop_toolbar['right'];
    if (($shop_toolbar_left && array_key_exists('switch_layout', $shop_toolbar_left) )
    || ($shop_toolbar_right && array_key_exists('switch_layout', $shop_toolbar_right) )) {
        $product_layout = isset($_COOKIE['g5shop_product_layout']) ? $_COOKIE['g5shop_product_layout'] : 'grid';
        if (!in_array($product_layout,array('grid','list'))) {
            $product_layout = 'grid';
        }
    }
    return isset($_REQUEST['view']) ? $_REQUEST['view'] : $product_layout;
}


function g5shop_get_current_page_url() {
    global $wp;
    $link = trailingslashit(home_url( add_query_arg( array(), $wp->request ) ));

    // Min/Max.
    if ( isset( $_GET['min_price'] ) ) {
        $link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
    }

    if ( isset( $_GET['max_price'] ) ) {
        $link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
    }

    // Order by.
    if ( isset( $_GET['orderby'] ) ) {
        $link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
    }

    /**
     * Search Arg.
     * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
     */
    if ( get_search_query() ) {
        $link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
    }

    // Post Type Arg.
    if ( isset( $_GET['post_type'] ) ) {
        $link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

        // Prevent post type and page id when pretty permalinks are disabled.
        if ( is_shop() ) {
            $link = remove_query_arg( 'page_id', $link );
        }
    }

    // Min Rating Arg.
    if ( isset( $_GET['rating_filter'] ) ) {
        $link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
    }

    // All current filters.
    if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
        foreach ( $_chosen_attributes as $name => $data ) {
            $filter_name = wc_attribute_taxonomy_slug( $name );
            if ( ! empty( $data['terms'] ) ) {
                $link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
            }
            if ( 'or' === $data['query_type'] ) {
                $link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
            }
        }
    }

    return apply_filters( 'g5shop_get_current_page_url', $link);
}


function g5shop_widget_get_current_page_url() {
    if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
        $link = home_url();
    } elseif ( is_shop() ) {
        $link = get_permalink( wc_get_page_id( 'shop' ) );
    } elseif ( is_product_category() ) {
        $link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
    } elseif ( is_product_tag() ) {
        $link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
    } else {
        $queried_object = get_queried_object();
        $link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
    }

    // Min/Max.
    if ( isset( $_GET['min_price'] ) ) {
        $link = add_query_arg( 'min_price', wc_clean( wp_unslash( $_GET['min_price'] ) ), $link );
    }

    if ( isset( $_GET['max_price'] ) ) {
        $link = add_query_arg( 'max_price', wc_clean( wp_unslash( $_GET['max_price'] ) ), $link );
    }

    // Order by.
    if ( isset( $_GET['orderby'] ) ) {
        $link = add_query_arg( 'orderby', wc_clean( wp_unslash( $_GET['orderby'] ) ), $link );
    }

    /**
     * Search Arg.
     * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
     */
    if ( get_search_query() ) {
        $link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
    }

    // Post Type Arg.
    if ( isset( $_GET['post_type'] ) ) {
        $link = add_query_arg( 'post_type', wc_clean( wp_unslash( $_GET['post_type'] ) ), $link );

        // Prevent post type and page id when pretty permalinks are disabled.
        if ( is_shop() ) {
            $link = remove_query_arg( 'page_id', $link );
        }
    }

    // Min Rating Arg.
    if ( isset( $_GET['rating_filter'] ) ) {
        $link = add_query_arg( 'rating_filter', wc_clean( wp_unslash( $_GET['rating_filter'] ) ), $link );
    }

    // All current filters.
    if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
        foreach ( $_chosen_attributes as $name => $data ) {
            $filter_name = wc_attribute_taxonomy_slug( $name );
            if ( ! empty( $data['terms'] ) ) {
                $link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
            }
            if ( 'or' === $data['query_type'] ) {
                $link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
            }
        }
    }

    return apply_filters( 'g5shop_widget_get_current_page_url', $link);
}

function g5shop_get_product_cat_class() {
    $product_cat_classes = array(
        'g5core__gutter-item',
    );

    $post_settings = &G5SHOP()->listing()->get_layout_settings();
    $product_cat_columns = $post_settings['post_columns'];
    $slick = isset($post_settings['slick']) ? $post_settings['slick'] : '';
    $is_shortcode = wc_get_loop_prop('is_shortcode');


    if ($is_shortcode) {
        $columns_xl = intval(wc_get_loop_prop('columns',4));
        $product_cat_columns = array(
            'xl' => $columns_xl,
            'lg' => $columns_xl > 3 ? 3 : $columns_xl,
            'md' => $columns_xl > 3 ? 3 : $columns_xl,
            'sm' => $columns_xl > 2 ? 2 : $columns_xl,
            '' => 1,
        );
        $slick = '';
    }

    if ($slick === '') {
        $product_cat_classes[] = g5core_get_bootstrap_columns($product_cat_columns);
    }

    return implode(' ', $product_cat_classes);
}

function g5shop_get_product_cat_inner_class() {
	$item_skin = G5SHOP()->options()->get_option('item_skin');
    $post_inner_classes = array(
        'g5core__post-item-inner',
        'g5shop__product-cat-item-inner',
	    "g5shop__product-{$item_skin}",
        'clearfix'
    );
    return implode(' ', $post_inner_classes);
}

function g5shop_get_product_class() {

    $product_classes = array(
        'g5core__gutter-item',
        'g5shop__product-item'
    );
    $post_settings = &G5SHOP()->listing()->get_layout_settings();
    $product_columns = $post_settings['post_columns'];
    $slick = isset($post_settings['slick']) ? $post_settings['slick'] : '';
    $post_index_start = absint(isset($post_settings['index']) ? $post_settings['index'] : 0);
    $loop_index = absint(wc_get_loop_prop( 'loop', 0 ));
    $post_layout = isset($post_settings['post_layout']) ? $post_settings['post_layout'] : g5shop_get_product_layout_default();
    $is_shortcode = wc_get_loop_prop('is_shortcode');
    if ($is_shortcode) {
        $columns_xl = intval(wc_get_loop_prop('columns',4));
        $product_columns = array(
            'xl' => $columns_xl,
            'lg' => $columns_xl > 3 ? 3 : $columns_xl,
            'md' => $columns_xl > 3 ? 3 : $columns_xl,
            'sm' => $columns_xl > 2 ? 2 : $columns_xl,
            '' => 1,
        );
        $slick = '';
        $post_layout = g5shop_get_product_layout_default();
    }
    $layout_matrix = G5SHOP()->listing()->get_layout_matrix($post_layout);
    $layout_settings = isset($layout_matrix['layout']) ? $layout_matrix['layout'] : '';
    if ($slick === '') {
        $product_classes[] = is_array($product_columns) ? g5core_get_bootstrap_columns($product_columns) : ($product_columns === 1 ? 'col-12' : $product_columns);
    }

    if (is_array($layout_settings)) {
        $index = ($post_index_start +  $loop_index) % count($layout_settings);
        $current_layout = $layout_settings[$index];
        $template = $current_layout['template'];
        $template_class = isset($current_layout['template_class']) ? $current_layout['template_class'] : "g5shop__product-{$template}";
        $post_index = $loop_index +1;
        $current_post_classes = array(
            $template_class,
            "g5shop__product-item-{$post_index}"
        );

        $product_classes = wp_parse_args($product_classes, $current_post_classes);
    }
    return implode(' ', $product_classes);
}

function g5shop_get_product_inner_class() {
    $post_settings = &G5SHOP()->listing()->get_layout_settings();
    $post_animation = isset($post_settings['post_animation']) ? $post_settings['post_animation'] : '';
    $post_inner_classes = array(
        'g5core__post-item-inner',
        'g5shop__product-item-inner',
        'clearfix',
        g5core_get_animation_class($post_animation)
    );

    return implode(' ', $post_inner_classes);
}

function g5shop_get_product_layout_default() {
    return apply_filters('g5shop_product_layout_default','grid');
}




/**
 * @param $category WP_Term
 * @param array $args
 */
function g5shop_render_category_thumbnail_markup($category ,  $args = array() ) {
    if (!is_a($category, 'WP_Term')) {
        return;
    }

    $args = wp_parse_args($args,array(
       'image_size' => '',
        'image_ratio' => '',
       'image_mode' => 'image'
    ));

    if (empty($args['image_size'])) {
        $args['image_size'] = apply_filters('subcategory_archive_thumbnail_size', 'woocommerce_thumbnail');
    }

    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
    if (!$thumbnail_id) {

        $placeholder_image = get_option('woocommerce_placeholder_image', 0);
        if (!empty($placeholder_image)) {
            $thumbnail_id = $placeholder_image;
        }
    }
    if ($thumbnail_id) {
        g5shop_render_image_markup(array(
            'image_size' => $args['image_size'],
            'image_id' => $thumbnail_id,
            'image_ratio' => $args['image_ratio'],
            'image_mode' => $args['image_mode'],
        ));
    } else {
        $placeholder_src = WC()->plugin_url() . '/assets/images/placeholder.png';
        ?>
        <div class="g5core__entry-thumbnail g5core__embed-responsive"
             style="background-image: url('<?php echo esc_url($placeholder_src) ?>')">
        </div>
        <?php
    }
}


function g5shop_vc_map_add_narrow_category($args = array())
{
    $category = array();
    $categories = get_categories(array('hide_empty' => '1','taxonomy' => 'product_cat'));
    if (is_array($categories)) {
        foreach ($categories as $cat) {
            $category[$cat->name] = $cat->term_id;
        }
    }
    $default = array(
        'type' => 'g5element_selectize',
        'heading' => esc_html__('Narrow Category', 'g5-shop'),
        'param_name' => 'cat',
        'value' => $category,
        'multiple' => true,
        'description' => esc_html__('Enter categories by names to narrow output (Note: only listed categories will be displayed, divide categories with linebreak (Enter)).', 'g5-shop'),
        'std' => ''
    );
    $default = array_merge($default, $args);
    return $default;
}

function g5shop_vc_map_add_filter() {
    return array(
        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Show', 'g5-shop'),
            'param_name' => 'show',
            'value' => array(
                esc_html__('All', 'g5-shop') => '',
                esc_html__('Sale Off', 'g5-shop') => 'sale',
                esc_html__('New In', 'g5-shop') => 'new-in',
                esc_html__('Featured', 'g5-shop') => 'featured',
                esc_html__('Top rated', 'g5-shop') => 'top-rated',
                esc_html__('Recent review', 'g5-shop') => 'recent-review',
                esc_html__('Best Selling', 'g5-shop') => 'best-selling',
                esc_html__('Narrow Products', 'g5-shop') => 'products'
            ),
            'std' => '',
            'group' => esc_html__('Products Filter', 'g5-shop'),
        ),
        g5shop_vc_map_add_narrow_category(array(
            'dependency' => array('element' => 'show','value_not_equal_to' => array('products')),
            'group' => esc_html__('Products Filter', 'g5-shop')
        )),
        array(
            'type' => 'autocomplete',
            'heading' => esc_html__( 'Narrow Products', 'g5-shop' ),
            'param_name' => 'ids',
            'settings' => array(
                'multiple' => true,
                'sortable' => true,
                'unique_values' => true,
            ),
            'save_always' => true,
            'description' => esc_html__( 'Enter List of Products', 'g5-shop' ),
            'dependency' => array('element' => 'show','value' => 'products'),
            'group' => esc_html__('Products Filter', 'g5-shop'),
        ),


        array(
            'type' => 'dropdown',
            'heading' => esc_html__('Order by', 'g5-shop'),
            'param_name' => 'orderby',
            'value' => array(
                esc_html__('Date', 'g5-shop') => 'date',
                esc_html__('Price', 'g5-shop') => 'price',
                esc_html__('Random', 'g5-shop') => 'rand',
                esc_html__('Sales', 'g5-shop') => 'sales'
            ),
            'std' => 'date',
            'description' => esc_html__('Select how to sort retrieved products.', 'g5-shop'),
            'dependency' => array('element' => 'show','value' => array('', 'sale', 'featured')),
            'group' => esc_html__('Products Filter', 'g5-shop'),
        ),
        array(
            'type' => 'g5element_button_set',
            'heading' => esc_html__('Sorting', 'g5-shop'),
            'param_name' => 'order',
            'value' => array(
                esc_html__('Descending', 'g5-shop') => 'DESC',
                esc_html__('Ascending', 'g5-shop') => 'ASC',
            ),
            'std' => 'DESC',
            'group' => esc_html__('Products Filter', 'g5-shop'),
            'dependency' => array('element' => 'show','value' => array('', 'sale', 'featured')),
            'description' => esc_html__('Select sorting order.', 'g5-shop'),
        ),
    );
}
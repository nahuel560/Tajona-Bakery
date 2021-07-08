<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}

function g5shop_shop_toolbar() {
    if (!woocommerce_products_will_display()) return;
    G5SHOP()->get_template('shop-toolbar.php');
}

function g5shop_render_thumbnail_markup( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'image_size'         => 'woocommerce_thumbnail',
        'image_ratio' => '',
        'image_mode' => '',
    ) );
    G5SHOP()->get_template('loop/thumbnail.php',$args);
}

function g5shop_render_image_markup($args = array())
{
    $args = wp_parse_args($args, array(
        'image_size' => 'thumbnail',
        'image_ratio' => '',
        'image_id' => '',
        'animated_thumbnail' => true,
        'image_mode' => '',
        'post' => null,
    ));

    if (empty($args['image_id'])) {
        $args['image_id'] = get_post_thumbnail_id($args['post']);
    }


    $image_data = g5core_get_image_data(array(
        'image_id' => $args['image_id'],
        'image_size' => $args['image_size'],
        'animated_thumbnail' => $args['animated_thumbnail']
    ));


    if (!$image_data) {
        $args['image_mode'] = '';
    }

    ob_start();
    if ($args['image_mode'] !== 'image') {
        $attributes = array();

        $classes = array(
            'g5core__entry-thumbnail',
            'g5core__embed-responsive',
        );
        if (empty($args['image_ratio'])) {
            if (preg_match('/x/', $args['image_size'])) {
                if (!$image_data) {
                    $image_sizes = preg_split('/x/', $args['image_size']);
                    $image_width = isset($image_sizes[0]) ? intval($image_sizes[0]) : 0;
                    $image_height = isset($image_sizes[1]) ? intval($image_sizes[1]) : 0;
                } else {
                    $image_width = $image_data['width'];
                    $image_height = $image_data['height'];
                }


                if (($image_width > 0) && ($image_height > 0)) {
                    $ratio = ($image_height / $image_width) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$image_width}x{$image_height}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css, "g5core__image-size-{$image_width}x{$image_height}");
                }

                $classes[] = "g5core__image-size-{$image_width}x{$image_height}";
            } else {
                $classes[] = "g5core__image-size-{$args['image_size']}";
            }

        } else {
            $classes[] = "g5core__image-size-{$args['image_ratio']}";

            if (!in_array($args['image_ratio'], array('1x1', '3x4', '4x3', '16x9', '9x16'))) {

                $image_ratio_sizes = preg_split('/x/', $args['image_ratio']);
                $image_ratio_width = isset($image_ratio_sizes[0]) ? intval($image_ratio_sizes[0]) : 0;
                $image_ratio_height = isset($image_ratio_sizes[1]) ? intval($image_ratio_sizes[1]) : 0;

                if (($image_ratio_width > 0) && ($image_ratio_height > 0)) {
                    $ratio = ($image_ratio_height / $image_ratio_width) * 100;
                    $custom_css = <<<CSS
                .g5core__image-size-{$args['image_ratio']}:before{
                    padding-top: {$ratio}%;
                }
CSS;
                    G5Core()->custom_css()->addCss($custom_css, "g5core__image-size-{$args['image_ratio']}");
                }
            }
        }

        if (!empty($image_data['url'])) {
            $attributes[] = sprintf('style="background-image: url(%s);"', esc_url($image_data['url']));
        }

        $attributes[] = sprintf('class="%s"', join(' ', $classes));

        ?>
	    <div <?php echo join(' ', $attributes) ?>></div>

        <?php
    } else {
        $attributes = array();

        if (!empty($image_data['alt'])) {
            $attributes[] = sprintf('alt="%s"', esc_attr($image_data['alt']));
        }

        if (!empty($image_data['width'])) {
            $attributes[] = sprintf('width="%s"', esc_attr($image_data['width']));
        }

        if (!empty($image_data['height'])) {
            $attributes[] = sprintf('height="%s"', esc_attr($image_data['height']));
        }

        if (!empty($image_data['url'])) {
            $attributes[] = sprintf('src="%s"', esc_url($image_data['url']));
        }
        ?>
	    <div class="g5core__entry-thumbnail">
		    <img <?php echo join(' ', $attributes); ?>>
	    </div>
        <?php
    }
    echo ob_get_clean();
}



function g5shop_template_loop_sale_count_down() {
    g5shop_template_sale_count_down();
}

function g5shop_template_single_sale_count_down() {
    g5shop_template_sale_count_down(true);
}


function g5shop_template_sale_count_down($is_single = false) {
    G5SHOP()->get_template('sale-count-down.php',array('is_single' => $is_single));
}

function g5shop_template_loop_action_add_to_cart() {
    $add_to_cart_enable = G5SHOP()->options()->get_option('add_to_cart_enable');
    if ($add_to_cart_enable !== 'on') return;
    G5SHOP()->get_template('loop/add-to-cart.php');
}

function g5shop_template_loop_add_to_cart () {
    $add_to_cart_enable = G5SHOP()->options()->get_option('add_to_cart_enable');
    if ($add_to_cart_enable !== 'on') return;
    woocommerce_template_loop_add_to_cart();
}

function g5shop_template_loop_quick_view() {
    $quick_view_enable = G5SHOP()->options()->get_option('product_quick_view_enable');
    if ($quick_view_enable !== 'on') return;
    G5SHOP()->get_template('loop/quick-view.php');
}

function g5shop_template_loop_wishlist() {
    G5SHOP()->get_template('loop/wishlist.php');
}

function g5shop_template_loop_compare() {
    G5SHOP()->get_template('loop/compare.php');
}

function g5shop_template_loop_cat() {
    $post_settings = &G5SHOP()->listing()->get_layout_settings();
    $product_category_enable = isset($post_settings['category_enable']) ? $post_settings['category_enable'] : (G5SHOP()->options()->get_option('product_category_enable') === 'on');

    if (!$product_category_enable) return;
    $terms = wc_get_product_terms( get_the_ID(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
    if (is_array($terms) && isset($terms[0])) {
        G5SHOP()->get_template('loop/cat.php',array('cat' => $terms[0]));
    }

}

function g5shop_template_loop_excerpt() {
    $post_settings = &G5SHOP()->listing()->get_layout_settings();
    $product_excerpt_enable = isset($post_settings['excerpt_enable']) ? $post_settings['excerpt_enable'] : (G5SHOP()->options()->get_option('product_excerpt_enable') === 'on');
    if (!$product_excerpt_enable) return;
    G5SHOP()->get_template('loop/post-excerpt.php');
}

function g5shop_template_loop_title() {
    G5SHOP()->get_template('loop/title.php');
}



function g5shop_template_loop_rating() {
    $post_settings = &G5SHOP()->listing()->get_layout_settings();
    $product_rating_enable = isset($post_settings['rating_enable']) ? $post_settings['rating_enable'] : (G5SHOP()->options()->get_option('product_rating_enable') === 'on');
    if (!$product_rating_enable) return;
    woocommerce_template_loop_rating();
}

function g5shop_template_loop_sale_flash() {
    G5SHOP()->get_template('loop/sale-flash.php');
}


function g5shop_template_archive_banner() {
    if (!is_shop() && !is_product_taxonomy()) return;
    $archive_banner_type = G5SHOP()->options()->get_option('archive_banner_type');
    if ($archive_banner_type === 'none') return;
    G5SHOP()->get_template('archive-banner.php' , array('archive_banner_type' => $archive_banner_type));
}
add_action('woocommerce_before_main_content','g5shop_template_archive_banner',40);

function g5shop_template_shop_filter() {
    G5SHOP()->get_template('shop-filter.php');
}

function g5shop_template_mini_cart_icon() {
    G5SHOP()->get_template('cart/mini-cart-icon.php');
}

function g5shop_template_breadcrumbs() {
    g5core_template_breadcrumbs('g5shop__single-breadcrumbs');
}

function g5shop_template_single_product_video() {
    $prefix = G5SHOP()->meta_prefix;
    $video_url = get_post_meta(get_the_ID(),"{$prefix}video_url",true);
    if (empty($video_url)) return;
    G5SHOP()->get_template('single/video.php',array('video_url' => $video_url));
}

function g5shop_template_single_product_actions() {
    G5SHOP()->get_template('single/actions.php');
}

function g5shop_template_stock_status() {
    global $product;

    switch ( $product->get_stock_status() ) {
        case 'instock' :
            echo '<div class="stock-status in-stock">' . esc_html__( 'In stock','g5-shop' ) . '</div>';
            break;
        case 'outofstock' :
            echo '<div class="stock-status out-of-stock">' . esc_html__( 'Out of stock','g5-shop' ) . '</div>';
            break;
        case 'onbackorder' :
            echo '<div class="stock-status on-back-order">' . esc_html__( 'On back order','g5-shop' ) . '</div>';
            break;
        default:
    }
}

function g5shop_template_single_product_share() {
    $single_product_share_enable =  G5SHOP()->options()->get_option('single_product_share_enable');
    if ($single_product_share_enable !== 'on') return;
    g5core_template_social_share();
}

function g5shop_template_quick_view_title() {
    G5SHOP()->get_template('quick-view/title.php');
}

function g5shop_template_quick_view_rating() {
    G5SHOP()->get_template('quick-view/rating.php');
}

function g5shop_template_checkout_login_coupon_form() {
    if ( (! wc_coupons_enabled())
    &&  (is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' )) ) { // @codingStandardsIgnoreLine.
        return;
    }
    G5SHOP()->get_template('checkout/login-coupon.php');
}

function g5shop_template_search_product_popup() {
    G5SHOP()->get_template('popup/search-product.php');
}

function g5shop_template_search_product() {
    G5SHOP()->get_template('search-product.php');
}



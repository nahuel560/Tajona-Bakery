<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * Shortcode attributes
 * @var $atts
 * @var $cate_filter_enable
 * @var $cate_filter_align
 * @var $columns_gutter
 * @var $category_enable
 * @var $rating_enable
 * @var $excerpt_enable
 * @var $posts_per_page
 * @var $offset
 * @var $post_animation
 * @var $append_tabs
 * @var $el_id
 * @var $el_class
 * @var $show
 * @var $cat
 * @var $ids
 * @var $orderby
 * @var $order
 * @var $columns_xl
 * @var $columns_lg
 * @var $columns_md
 * @var $columns_sm
 * @var $columns
 * @var $slider_rows
 * @var $slider_pagination_enable
 * @var $slider_navigation_enable
 * @var $slider_center_enable
 * @var $slider_center_padding
 * @var $slider_auto_height_enable
 * @var $slider_loop_enable
 * @var $slider_autoplay_enable
 * @var $slider_autoplay_timeout
 * @var $post_image_size ,
 * @var $post_image_ratio_width
 * @var $post_image_ratio_height
 * @var $animation_style
 * @var $animation_duration
 * @var $animation_delay
 * @var $css_editor
 * @var $responsive
 * Shortcode class
 * @var $this WPBakeryShortCode_G5Element_Products
 */

$cate_filter_enable = $cate_filter_align = $columns_gutter = $posts_per_page = $offset = $post_animation =
$el_id = $el_class = $append_tabs =
$show = $cat = $ids = $orderby = $order =
$columns_xl = $columns_lg = $columns_md = $columns_sm = $columns =
$category_enable = $rating_enable = $excerpt_enable =
$slider_rows = $slider_pagination_enable = $slider_navigation_enable = $slider_center_enable = $slider_center_padding = $slider_auto_height_enable = $slider_loop_enable = $slider_autoplay_enable = $slider_autoplay_timeout =
$post_image_size = $post_image_ratio_width = $post_image_ratio_height =
$animation_style = $animation_duration = $animation_delay = $css_editor = $responsive = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$wrapper_classes = array(
    'g5element__products-slider',
    'woocommerce',
    $this->getExtraClass($el_class),
    $this->getCSSAnimation($css_animation),
    vc_shortcode_custom_css_class($css)
);
$query_args = array(
    'post_type' => 'product',

);
$settings = array(
    'post_layout' => 'grid',
    'image_size' => $post_image_size,
    'image_ratio' => array(
        'width' => absint($post_image_ratio_width),
        'height' => absint($post_image_ratio_height)
    ),
    'category_enable' => $category_enable,
    'rating_enable' => $rating_enable,
    'excerpt_enable' => $excerpt_enable,
);
$atts['slider'] = true;
$this->prepare_display($atts, $query_args, $settings);
$class_to_filter = implode(' ', array_filter($wrapper_classes));
$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->getShortcode(), $atts);
$wrapper_attributes = array();
if (!empty($el_id)) {
    $wrapper_attributes[] = 'id="' . esc_attr($el_id) . '"';
}
?>
    <div class="<?php echo esc_attr($css_class) ?>" <?php echo implode(' ', $wrapper_attributes) ?>>
        <?php G5SHOP()->listing()->render_content($this->_query_args, $this->_settings); ?>
    </div>
<?php
if ($show == 'recent-review') {
    remove_filter('posts_clauses', array(G5SHOP()->shortcodes(), 'order_by_comment_date_post_clauses'));
}


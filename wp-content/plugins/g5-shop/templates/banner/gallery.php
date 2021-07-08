<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$gallery = G5SHOP()->options()->get_option('archive_banner_gallery');
if (empty($gallery)) return;
$gallery = preg_split('/\|/', $gallery);
$slick_args = array(
    'slidesToShow' => 1,
    'dots' => true,
    'arrows' => false
);
?>
<div class="g5core__post-featured slick-slider"
     data-slick-options="<?php echo esc_attr(json_encode($slick_args)); ?>">
    <?php
    foreach ($gallery as $image_id) {
         echo wp_get_attachment_image( $image_id, 'full' );
    }
    ?>
</div>

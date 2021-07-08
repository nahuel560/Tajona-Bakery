<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $video_url
 */
$magnific_options = array(
    'type' =>  'iframe',
);
?>
<a hidden="hidden" class="g5shop__product-gallery-video" data-g5core-mfp data-mfp-options="<?php echo esc_attr(json_encode($magnific_options))?>" href="<?php echo esc_url($video_url) ?>"><i class="far fa-play"></i></a>

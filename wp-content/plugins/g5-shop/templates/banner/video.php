<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$video_embed = G5SHOP()->options()->get_option('archive_banner_video');
if (empty($video_embed)) return;
?>
<div class="g5core__embed-responsive g5core__image-size-16x9">
    <?php
    $hasThumb = true;
    if (wp_oembed_get($video_embed)) {
        echo wp_oembed_get($video_embed, array('wmode' => 'transparent'));
    } else {
        echo $video_embed;
    }
    ?>
</div>

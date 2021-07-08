<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
global $post;

$post_excerpt = apply_filters( 'g5shop_post_excerpt', $post->post_excerpt );

if ( ! $post_excerpt ) {
    return;
}
?>
<div class="g5shop__loop-product_excerpt">
    <?php echo $post_excerpt; // WPCS: XSS ok. ?>
</div>

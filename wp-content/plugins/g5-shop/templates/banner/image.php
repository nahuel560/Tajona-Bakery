<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$banner = G5SHOP()->options()->get_option('archive_banner_image');
if (!is_array($banner) || !isset($banner['url']) || empty($banner['url'])) return;
?>
<img src="<?php echo esc_html($banner['url'])?>" alt="<?php esc_attr_e( 'shop banner', 'g5-shop' ) ?>">

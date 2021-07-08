<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$custom_html = G5SHOP()->options()->get_option('archive_banner_custom_html');
if (empty($custom_html)) return;
echo do_shortcode($custom_html);
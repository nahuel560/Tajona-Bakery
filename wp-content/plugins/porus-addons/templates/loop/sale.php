<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
/**
 * @var $label
 * @var $type
 */

$wrapper_classes = array(
        'g5shop__product-flash'
);
if ($type === 'sale') {
    $wrapper_classes[] = 'on-sale';
} elseif ($type === 'featured') {
    $wrapper_classes[] = 'on-featured';
} elseif ($type === 'new') {
    $wrapper_classes[] = 'on-new';
}
$wrapper_class = join(' ', $wrapper_classes);
?>
<span class="<?php echo esc_attr($wrapper_class)?>">
    <span><?php echo esc_html($label); ?></span>
    <svg class="porus__icon-sale" aria-hidden="true" role="img"> <use href="#porus__icon-sale" xlink:href="#porus__icon-sale"></use> </svg>
</span>
<?php
// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
$shop_toolbar = G5SHOP()->options()->get_option('shop_toolbar');
$shop_toolbar_mobile = G5SHOP()->options()->get_option('shop_toolbar_mobile');
if (!is_array($shop_toolbar) && !is_array($shop_toolbar_mobile)) return;

$shop_toolbar_top = (!isset($shop_toolbar['top']) || !is_array($shop_toolbar['top']) || (count($shop_toolbar['top']) === 0)) ? false : $shop_toolbar['top'];
$shop_toolbar_left = (!isset($shop_toolbar['left']) || !is_array($shop_toolbar['left']) || (count($shop_toolbar['left']) === 0)) ? false : $shop_toolbar['left'];
$shop_toolbar_right = (!isset($shop_toolbar['right']) || !is_array($shop_toolbar['right']) || (count($shop_toolbar['right']) === 0)) ? false : $shop_toolbar['right'];

$shop_toolbar_mobile_left = (!isset($shop_toolbar_mobile['left']) || !is_array($shop_toolbar_mobile['left']) || (count($shop_toolbar_mobile['left']) === 0)) ? false : $shop_toolbar_mobile['left'];
$shop_toolbar_mobile_right = (!isset($shop_toolbar_mobile['right']) || !is_array($shop_toolbar_mobile['right']) || (count($shop_toolbar_mobile['right']) === 0)) ? false : $shop_toolbar_mobile['right'];


if (!$shop_toolbar_left && !$shop_toolbar_right && !$shop_toolbar_top && !$shop_toolbar_mobile_left && !$shop_toolbar_mobile_right) return;
unset($shop_toolbar_top['__no_value__']);
unset($shop_toolbar_left['__no_value__']);
unset($shop_toolbar_right['__no_value__']);
unset($shop_toolbar_mobile_left['__no_value__']);
unset($shop_toolbar_mobile_right['__no_value__']);
$shop_toolbar_layout = G5SHOP()->options()->get_option('shop_toolbar_layout');
$wrapper_classes = array(
    'g5shop__shop-toolbar',
    $shop_toolbar_layout
);

$wrapper_class = join(' ', $wrapper_classes);
?>
<div class="<?php echo esc_attr($wrapper_class)?>" hidden="hidden">
    <div class="g5shop__shop-toolbar-inner">
        <?php if ($shop_toolbar_top): ?>
            <div class="g5shop__shop-toolbar-top">
                <div class="container">
                    <ul class="g5shop__shop-toolbar-list">
                        <?php foreach ($shop_toolbar_top  as $k => $v): ?>
                            <li><?php G5SHOP()->get_template("toolbar/{$k}.php") ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <div class="g5shop__shop-toolbar-bottom">
            <div class="container">
                <div class="g5shop__shop-toolbar-bottom-inner">
                    <?php if ($shop_toolbar_left): ?>
                        <div class="g5shop__shop-toolbar-left">
                            <ul class="g5shop__shop-toolbar-list">
                                <?php foreach ($shop_toolbar_left as $k => $v): ?>
                                    <li><?php G5SHOP()->get_template("toolbar/{$k}.php") ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if ($shop_toolbar_right): ?>
                        <div class="g5shop__shop-toolbar-right">
                            <ul class="g5shop__shop-toolbar-list">
                                <?php foreach ($shop_toolbar_right as $k => $v): ?>
                                    <li><?php G5SHOP()->get_template("toolbar/{$k}.php") ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
	    <?php if ($shop_toolbar_mobile_left || $shop_toolbar_mobile_right): ?>
		    <div class="g5shop__shop-toolbar-mobile">
			    <div class="container">
				    <div class="g5shop__shop-toolbar-bottom-inner">
					    <?php if ($shop_toolbar_mobile_left): ?>
						    <div class="g5shop__shop-toolbar-left">
							    <ul class="g5shop__shop-toolbar-list">
								    <?php foreach ($shop_toolbar_mobile_left as $k => $v): ?>
									    <li><?php G5SHOP()->get_template("toolbar/{$k}.php") ?></li>
								    <?php endforeach; ?>
							    </ul>
						    </div>
					    <?php endif; ?>
					    <?php if ($shop_toolbar_mobile_right): ?>
						    <div class="g5shop__shop-toolbar-right">
							    <ul class="g5shop__shop-toolbar-list">
								    <?php foreach ($shop_toolbar_mobile_right as $k => $v): ?>
									    <li><?php G5SHOP()->get_template("toolbar/{$k}.php") ?></li>
								    <?php endforeach; ?>
							    </ul>
						    </div>
					    <?php endif; ?>
				    </div>
			    </div>
		    </div>
	    <?php endif; ?>
    </div>

	<?php do_action('g5shop_after_shop_toolbar') ?>
    <?php if (($shop_toolbar_top && isset($shop_toolbar_top['filter']))
        || ($shop_toolbar_left && isset($shop_toolbar_left['filter']))
        || ($shop_toolbar_right && isset($shop_toolbar_right['filter']))):  ?>

        <?php
        ob_start();
        the_widget( 'WC_Widget_Layered_Nav_Filters',array('title' => '') );
        $content_archive_filters =  ob_get_clean();
        ?>
        <?php if (!empty($content_archive_filters)): ?>
        <div class="g5shop__archive-filters">
            <div class="container">
                <?php echo $content_archive_filters;?>
            </div>
        </div>
         <?php endif; ?>
    <?php endif; ?>
</div>


<?php
/**
 * @var $icon_html
 * @var $icon_hover_html
 * @var $title
 * @var $content
 * @var $switch_show_button
 * @var $text_button
 * @var $title_class
 * @var $link
 * @var $button_style
 * @var $button_shape
 * @var $button_size
 * @var $button_color
 * @var $button_is_3d
 * @var $img_box_link
 * @var $description_class
 */
?>
    <div class="top-box">
        <?php if (!empty($title)): ?>
            <h4 class="<?php echo implode(' ', $title_class); ?>">
                <?php echo $img_box_link['before'] ?>
                <?php echo esc_html($title); ?>
                <?php echo $img_box_link['after'] ?>
            </h4>
        <?php endif; ?>
	    <?php if ($icon_html !== ''): ?>
		    <?php
		    $image_classes = array('image');
		    if ($icon_hover_html !== '') {
			    $image_classes[] = 'image-hover';
		    }
		    $image_class = implode(' ', $image_classes);
		    ?>
		    <div class="<?php echo esc_attr($image_class)?>">
			    <?php echo $img_box_link['before'] ?>
			    <?php echo wp_kses_post($icon_html); ?>
			    <?php if ($icon_hover_html !== ''): ?>
				    <?php echo wp_kses_post($icon_hover_html)?>
			    <?php endif; ?>
			    <?php echo $img_box_link['after'] ?>
		    </div>
	    <?php endif; ?>
    </div>
    <!--.top-box-->
<?php if (!empty($content)): ?>
    <div class="<?php echo implode(' ', $description_class); ?>">
	    <?php echo wpb_js_remove_wpautop($content, true); ?>
    </div>
<?php endif; ?>
<?php if (($img_box_link['before']) && ($switch_show_button === 'on') && ($text_button !== '')): ?>
    <div class="btn-box">
        <?php g5element_render_button($text_button, $link, $button_style, $button_shape, $button_size, $button_color, $button_is_3d); ?>
    </div>
<?php endif; ?>
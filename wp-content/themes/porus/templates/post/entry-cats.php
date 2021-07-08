<?php
$separate_meta = esc_html__( ', ', 'porus' );
// Get Categories for posts.
$categories_list = get_the_category_list( $separate_meta );

if (empty($categories_list)) {
	return;
}
?>
<span class="cat-tags-links">
	<span class="cat-links"><?php echo wp_kses_post($categories_list) ?></span>
</span>
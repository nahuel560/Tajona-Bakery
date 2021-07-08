<?php
/**
 * The template for displaying archive content layout
 *
 * @since 1.0
 * @version 1.0
 */
global $wp_query;

if (have_posts()) {
	/* Start the Loop */
	while ( have_posts() ) : the_post();
		porus_get_template('post/content');
	endwhile;
    echo paginate_links(array(
        'type' => 'list',
        'mid_size' => 1,
        'prev_text' => esc_html__('PREV', 'porus'),
        'next_text' => esc_html__('NEXT', 'porus'),
    ));
} else {
	porus_get_template( 'post/content-none');
}

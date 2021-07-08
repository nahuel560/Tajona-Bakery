<article id="post-<?php the_ID(); ?>" <?php post_class('article-post'); ?>>
	<?php porus_get_template('post/entry-thumbnail') ?>

	<header class="entry-header">
		<?php porus_get_template('post/entry-title') ?>
		<?php porus_get_template('post/entry-meta') ?>
	</header><!-- .entry-header -->


	<?php porus_get_template('post/entry-content') ?>
	<?php if (is_singular()): ?>
	<?php porus_get_template('post/entry-footer') ?>
	<?php endif; ?>
</article><!-- #post-## -->
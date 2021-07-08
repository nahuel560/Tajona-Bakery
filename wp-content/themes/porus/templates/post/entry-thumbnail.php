<?php if ( '' !== get_the_post_thumbnail()) : ?>
	<div class="post-thumbnail">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( 'porus-featured-image' ); ?>
		</a>
	</div><!-- .post-thumbnail -->
<?php endif; ?>
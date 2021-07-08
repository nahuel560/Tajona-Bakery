<?php if (has_tag()): ?>
	<div class="post-tags">
        <span><?php esc_html_e('TAGS:','porus') ?></span>
		<?php the_tags("",","); ?>
	</div>
<?php endif; ?>
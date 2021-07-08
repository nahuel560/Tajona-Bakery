<?php
$separate_meta = esc_html__( ', ', 'porus' );
// Get Categories for posts.
$categories_list = get_the_category_list( $separate_meta );

if ( empty( $categories_list ) ) {
	return;
}
?>
<ul class="entry-meta">
	<li class="meta-author">
		<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>">
			<?php esc_html_e('By','porus')?><span class="title-meta-author"><?php the_author() ?></span>
		</a>
	</li>
	<li class="meta-date">
		<a href="<?php echo esc_url( get_permalink() ) ?>"><?php echo get_the_time( get_option( 'date_format' ) ) ?></a>
	</li>
    <?php if (is_singular() && (comments_open() || get_comments_number())): ?>
        <li class="meta-comment">
            <i class="far fa-comment"></i>
            <?php comments_popup_link(esc_html__('0 Comments','porus'), esc_html__('1 Comment','porus'), esc_html__('% Comments','porus')) ?>
        </li>
    <?php endif; ?>
    <li class="meta-cate">
        <i class="far fa-folder"></i>
        <?php echo wp_kses_post( $categories_list ) ?>
    </li>
</ul>
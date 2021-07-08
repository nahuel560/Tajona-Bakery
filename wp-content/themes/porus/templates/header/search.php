<?php
/**
 * Header search template
 *
 * @since 1.0
 * @version 1.0
 */
?>
<div class="search-form-wrapper">
	<span class="search-icon"><i class="fal fa-search"></i></span>
	<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) )  ?>">
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Type & hit enter &hellip;', 'placeholder', 'porus' ) ?>" value="<?php echo get_search_query() ?>" name="s" />
		<button type="submit" class="search-submit"><i class="fal fa-search"></i></button>
	</form>
</div>

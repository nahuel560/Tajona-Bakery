<?php
/**
 * Site header template
 *
 * @since 1.0
 * @version 1.0
 */
?>
<header id="site-header" class="site-header">
	<div class="container">
		<div class="site-header-content">
			<div class="menu-toggle-button toggle-icon"><span></span></div>
			<?php porus_get_template( 'header/site-branding' ); ?>
			<?php porus_get_template( 'header/menu' ); ?>
			<?php porus_get_template( 'header/search' ); ?>
		</div>
	</div>
</header>
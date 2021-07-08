<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function porus_setup() {
	/*
	 * Make theme available for translation.
	 */
	load_theme_textdomain( 'porus' , get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// Add theme featured image size
	add_image_size( 'porus-featured-image', 1920, 1080, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 768;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => esc_html__( 'Main Menu', 'porus' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

    add_theme_support("custom-header");
    add_theme_support("custom-background");


	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	) );

	// Add theme support for Custom Logo.
	add_theme_support( 'custom-logo', array(
		'width'      => 240,
		'height'     => 80,
		'flex-width' => true,
	) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support( 'wp-block-styles' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */

	/**
	 * Editor style
	 */
	add_editor_style( get_parent_theme_file_uri( 'assets/css/editor-style.css' ) . '?v=' . uniqid() );

	add_theme_support( 'editor-styles' );

	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );

    add_theme_support('woocommerce');

    // Declare theme support for features.
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );



}

add_action( 'after_setup_theme', 'porus_setup' );


function porus_enqueue_block_editor_assets() {
	wp_enqueue_style( 'block-editor', get_parent_theme_file_uri( 'assets/css/editor-blocks.css?v=' . uniqid() ) );
}

add_action( 'enqueue_block_editor_assets', 'porus_enqueue_block_editor_assets' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 *
 * @return array $urls           URLs to print for resource hints.
 */
function porus_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'porus-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}

add_filter( 'wp_resource_hints', 'porus_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 */
function porus_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'porus' ),
		'id'            => 'sidebar-blog',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'porus' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}

add_action( 'widgets_init', 'porus_widgets_init' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function porus_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}

add_action( 'wp_head', 'porus_pingback_header' );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @param string $template front-page.php.
 *
 * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
 */
function porus_front_page_template( $template ) {
	return is_home() ? '' : $template;
}

add_filter( 'frontpage_template', 'porus_front_page_template' );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @param array $args Arguments for tag cloud widget.
 *
 * @return array The filtered arguments for tag cloud widget.
 */
function porus_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';

	return $args;
}

add_filter( 'widget_tag_cloud_args', 'porus_widget_tag_cloud_args' );


/**
 * Enqueue scripts and styles.
 */
function porus_scripts() {
	wp_deregister_style( 'font-awesome' );

	// 3rd party
	$rtl = is_rtl() ? '-rtl' : '';

	wp_enqueue_style( 'bootstrap', get_theme_file_uri( '/assets/vendors/bootstrap/css/bootstrap' . $rtl . '.min.css' ), array(), '4.3.1' );

	wp_enqueue_style( 'font-awesome', get_theme_file_uri( '/assets/vendors/font-awesome/css/fontawesome.css' ), array(), '5.8.2' );
	// Theme stylesheet.
	wp_enqueue_style( PORUS_FILE_HANDLER . 'style', get_template_directory_uri() . '/style.css', array(), PORUS_VERSION );

	wp_enqueue_script( PORUS_FILE_HANDLER . 'app', get_theme_file_uri( '/assets/js/app.min.js' ), array( 'jquery' ), PORUS_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'porus_scripts' ,20);

/**
 * Register custom fonts.
 */
function porus_fonts_url()
{
	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Libre Franklin, translate this to 'off'. Do not translate
	 * into your own language.
	 */

	$google_fonts = array();
	$fonts        = array();
	foreach (porus_font_default() as $font) {
		switch ($font['kind']) {
			case 'webfonts#webfont':
				$variants = isset($font['variants']) ? $font['variants'] : array();
				$variants = str_replace('italic','i',join($variants,',')) ;
				$google_fonts[] = "{$font['family']}:{$variants}";
				break;
			case 'custom':
				$fonts['porus-custom-font-' . sanitize_key($font['family'])] = $font['css_url'];
				break;
		}
	}

	if (!empty($google_fonts)) {
		$query_args = array(
			'family' => urlencode(implode('|', $google_fonts)),
			'subset' => urlencode('latin,latin-ext'),
		);

		$google_fonts_url              = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
		$fonts['google-fonts'] = $google_fonts_url;
	}

	return $fonts;
}

function porus_get_template_path( $slug ) {
	$template_name = "templates/{$slug}.php";
	$located       = trailingslashit( get_stylesheet_directory() ) . $template_name;
	if ( ! file_exists( $located ) ) {
		$located = trailingslashit( get_template_directory() ) . $template_name;
	}

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_name ), '1.0' );

		return '';
	}

	return $located;
}

function porus_get_template( $slug, $args = array() ) {
	if ( $args && is_array( $args ) ) {
		extract( $args );
	}
	$located = porus_get_template_path( $slug );
	if ( ! empty( $located ) ) {
		include( $located );
	}

}

function porus_get_page_title() {
	$page_title = '';
	if ( ( is_home() && ! is_front_page() ) || ( is_page() ) ) {
		$page_title = single_post_title( '', false );
	} elseif (is_singular('post')) {
		$page_title = esc_html__('Blog','porus');
	} elseif ( is_singular() ) {
		$page_title = get_the_title();
	} elseif ( is_search() ) {
		$page_title = sprintf( esc_html__( 'Search Results For: %s', 'porus' ), get_search_query() );
	} elseif ( is_404() ) {
		$page_title = esc_html__( 'Page Not Found', 'porus' );
	} elseif (is_home()) {
		$page_title = esc_html__('Blog','porus');
	} else {
		$page_title = get_the_archive_title();
	}

	return $page_title;
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function porus_content_width() {
	/**
	 * Filter content width of the theme.
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'porus_content_width', $GLOBALS['content_width'] );
}

add_action( 'template_redirect', 'porus_content_width', 0 );

/**
 * Checks to see if we're on the homepage or not.
 */
function porus_is_frontpage() {
	return ( is_front_page() && ! is_home() );
}

function porus_sidebar_primary() {
	return apply_filters( 'porus_sidebar_name', 'sidebar-blog' );
}

function porus_has_sidebar() {
	return apply_filters( 'porus_has_sidebar', is_active_sidebar( porus_sidebar_primary() ) && ! is_404() );
}

function porus_body_class( $classes ) {
	global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
	if ( $is_lynx ) {
		$classes[] = 'lynx';
	} elseif ( $is_gecko ) {
		$classes[] = 'gecko';
	} elseif ( $is_opera ) {
		$classes[] = 'opera';
	} elseif ( $is_NS4 ) {
		$classes[] = 'ns4';
	} elseif ( $is_safari ) {
		$classes[] = 'safari';
	} elseif ( $is_chrome ) {
		$classes[] = 'chrome';
	} elseif ( $is_IE ) {
		$classes[] = 'ie';
	} else {
		$classes[] = 'unknown';
	}
	if ( $is_iphone ) {
		$classes[] = 'iphone';
	}

	if ( ! porus_has_sidebar() ) {
		$classes[] = 'no-sidebar';
	} else {
		$classes[] = 'has-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'porus_body_class');

function porus_admin_body_class( $classes ) {
	if ( ! porus_has_sidebar() ) {
		$classes .= ' no-sidebar';
	} else {
		$classes .= ' has-sidebar';
	}

	return $classes;
}

add_filter( 'admin_body_class', 'porus_admin_body_class' );

function porus_nav_menu_item_title( $title, $item, $args, $depth ) {
	if ( $args->theme_location !== 'primary' ) {
		return $title;
	}

	if ( in_array( 'menu-item-has-children', $item->classes ) ) {
		return $title . sprintf( '<span class="caret"></span>' );
	}

	return $title;

}

add_filter( 'nav_menu_item_title', 'porus_nav_menu_item_title', 10, 4 );

/**
 * Add font url to editor style
 *
 * @param $stylesheets
 *
 * @return array
 */
function porus_custom_editor_styles( $stylesheets ) {
	foreach ( porus_fonts_url() as $url ) {
		$stylesheets[] = $url;
	}

	return $stylesheets;
}

function porus_scripts_font() {
	foreach ( porus_fonts_url() as $handler => $url ) {
		wp_enqueue_style( $handler, $url );
	}
}

add_filter( 'editor_stylesheets', 'porus_custom_editor_styles', 100 );
add_action( 'enqueue_block_editor_assets', 'porus_scripts_font', 100 );
add_action( 'wp_enqueue_scripts', 'porus_scripts_font', 100 );

function porus_has_request_pagination_ajax() {
    return isset( $_REQUEST['action'] ) && $_REQUEST['action'] === 'pagination_ajax';
}

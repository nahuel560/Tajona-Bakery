<?php
add_action( 'wp_enqueue_scripts', 'porus_child_theme_enqueue_styles', 100 );
function porus_child_theme_enqueue_styles() {
	wp_enqueue_style( 'porus-child-style', get_stylesheet_directory_uri() . '/style.css', array( basename(get_template_directory()) . '-style' ) );
}

add_action( 'after_setup_theme', 'porus_child_theme_setup');
function porus_child_theme_setup(){
	$language_path = get_stylesheet_directory() .'/languages';
	if(is_dir($language_path)){
		load_child_theme_textdomain('porus', $language_path );
	}
}
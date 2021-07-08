<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define('PORUS_VERSION', '1.0.0');
define('PORUS_FILE_HANDLER', basename(get_template_directory()) . '-');

/**
 * Inlcude theme functions
 */
include_once get_parent_theme_file_path('inc/require-plugin.php');
include_once get_parent_theme_file_path('inc/core-functions.php');
include_once get_parent_theme_file_path('inc/template-functions.php');
include_once get_parent_theme_file_path('inc/template-tags.php');
include_once get_parent_theme_file_path('inc/customizer.php');
include_once get_parent_theme_file_path('inc/setup-data.php');
include_once get_parent_theme_file_path('inc/custom-css.php');
if (function_exists('WC')) {
	include_once get_parent_theme_file_path('inc/woocommerce.php');
}
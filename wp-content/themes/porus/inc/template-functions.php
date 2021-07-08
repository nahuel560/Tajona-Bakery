<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Header template
 */
function porus_template_header()
{
    porus_get_template('header');
}

add_action('porus_before_page_wrapper_content', 'porus_template_header', 10);

/**
 * Footer template
 */
function porus_template_footer()
{
    porus_get_template('footer');
}

add_action('porus_after_page_wrapper_content', 'porus_template_footer', 10);

/**
 * Content Wrapper Start
 */
function porus_template_wrapper_start()
{
    porus_get_template('global/wrapper-start');
}

add_action('porus_main_wrapper_content_start', 'porus_template_wrapper_start', 10);

/**
 * Content Wrapper End
 */
function porus_template_wrapper_end()
{
    porus_get_template('global/wrapper-end');
}

add_action('porus_main_wrapper_content_end', 'porus_template_wrapper_end', 10);

/**
 * Archive content layout
 */
function porus_template_archive_content()
{
    porus_get_template('archive/layout');
}

add_action('porus_archive_content', 'porus_template_archive_content', 10);

/**
 * Single content layout
 */
function porus_template_single_content()
{
    porus_get_template('single/layout');
}

add_action('porus_single_content', 'porus_template_single_content', 10);

/**
 * Single content layout
 */
function porus_template_page_content()
{
    porus_get_template('page/layout');
}

add_action('porus_page_content', 'porus_template_page_content', 10);

/**
 * Search content layout
 */
function porus_template_search_content()
{
    porus_get_template('search/layout');
}

add_action('porus_search_content', 'porus_template_search_content', 10);

/**
 * 404 content layout
 */
function porus_template_404_content()
{
    porus_get_template('404/layout');
}

add_action('porus_404_content', 'porus_template_404_content', 10);

function porus_template_page_title()
{
    porus_get_template('page-title');
}

add_action('porus_before_main_content', 'porus_template_page_title', 10);


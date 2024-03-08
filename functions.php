<?php
/**
 * Project Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Project Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_PROJECT_CHILD_VERSION', '1.0.0' );

require_once 'src/PROJECT_Theme_Init.php';

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'project-child-theme-css', get_stylesheet_directory_uri() . '/style.css', ['project-theme-css'], CHILD_THEME_PROJECT_CHILD_VERSION, 'all' );
	
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );




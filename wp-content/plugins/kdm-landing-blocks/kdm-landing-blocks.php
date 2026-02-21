<?php
/**
 * Plugin Name:       KDM Landing Blocks
 * Description:       Custom Gutenberg blocks and patterns for Kingston Digital Media landing pages.
 * Version:           1.0.0
 * Author:            Kingston Digital Media
 * Text Domain:       kdm-landing-blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'KDM_LB_VERSION', '1.0.0' );
define( 'KDM_LB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'KDM_LB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Enqueue scripts and styles for the frontend and editor.
 */
function kdm_lb_enqueue_assets() {
	// Only load on the specific landing page if needed, or everywhere.
	// For now, let's load it on the frontend where block styles are needed.
	wp_enqueue_style(
		'kdm-landing-blocks-style',
		KDM_LB_PLUGIN_URL . 'assets/css/local-seo.css',
		array(),
		KDM_LB_VERSION
	);
	
	wp_enqueue_style(
		'kdm-landing-fonts',
		'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap',
		array(),
		null
	);
}
add_action( 'wp_enqueue_scripts', 'kdm_lb_enqueue_assets' );

// Also enqueue styles in the block editor so the preview matches the frontend.
add_action( 'enqueue_block_editor_assets', 'kdm_lb_enqueue_assets' );

/**
 * Register Custom Block Categories
 */
function kdm_lb_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'kdm-landing',
				'title' => __( 'KDM Landing Patterns', 'kdm-landing-blocks' ),
			),
		)
	);
}
add_filter( 'block_categories_all', 'kdm_lb_block_categories', 10, 2 );

/**
 * Run inclusion files
 */
require_once KDM_LB_PLUGIN_DIR . 'includes/register-patterns.php';

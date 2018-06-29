<?php
/**
 * @package   lsx
 * @author    David Cramer
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 David Cramer
 *
 * @wordpress-plugin
 * Plugin Name: LSX Landing Pages
 * Plugin URI:  http://cramer.co.za
 * Description: Landing Page Builder for LSX
 * Version:     1.0.1
 * Author:      David Cramer
 * Author URI:  http://cramer.co.za
 * Text Domain: lsx
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'LSXLDPG_PATH', plugin_dir_path( __FILE__ ) );
define( 'LSXLDPG_CORE', __FILE__ );
define( 'LSXLDPG_URL', plugin_dir_url( __FILE__ ) );
define( 'LSXLDPG_VER', '1.0.0' );

// Load instance
add_action( 'plugins_loaded', function() {

	// Post Type and Custom Fields
	require_once( LSXLDPG_PATH . '/classes/class-lsx-landing-pages-admin.php' );

	// Shortcode
	require_once( LSXLDPG_PATH . '/classes/class-lsx-landing-pages.php' );

	// include the library

	//include_once LSXLDPG_PATH . 'uix/uix.php';

	// include functions
	include_once LSXLDPG_PATH . 'includes/customizer-html.php';
	include_once LSXLDPG_PATH . 'includes/functions.php';

	// init actions
	include_once LSXLDPG_PATH . 'includes/actions.php';

	// get the structures
	// $pages = include LSXLDPG_PATH . 'includes/pages.php';
	$metaboxes = include LSXLDPG_PATH . 'includes/metaboxes.php';

	// initialize admin UI
	//$uix = \lsx\ui\uix::get_instance( 'lsx' );
	//$uix->register_pages( $pages );
	//$uix->register_metaboxes( $metaboxes );

	// init widgets
	include_once LSXLDPG_PATH . 'includes/widgets.php';

} );

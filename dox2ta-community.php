<?php
/**
 * Plugin Name: Dox2ta Community
 * Description: Shortcode to join the Dox2ta community with gamified Dota 2 themed UI and membership order tracking.
 * Version: 1.0.0
 * Author: MirzaFreddy
 * License: GPLv2 or later
 * Text Domain: dox2ta-community
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

// Constants
define( 'DOX2TA_COMMUNITY_VERSION', '1.0.0' );
define( 'DOX2TA_COMMUNITY_FILE', __FILE__ );
define( 'DOX2TA_COMMUNITY_PATH', plugin_dir_path( __FILE__ ) );
define( 'DOX2TA_COMMUNITY_URL', plugin_dir_url( __FILE__ ) );

// Autoload simple loader
spl_autoload_register( function ( $class ) {
    if ( strpos( $class, 'Dox2ta_' ) !== 0 ) return;
    $file = DOX2TA_COMMUNITY_PATH . 'includes/' . 'class-' . strtolower( str_replace( '_', '-', $class ) ) . '.php';
    if ( file_exists( $file ) ) require_once $file;
} );

// Activation: create DB table
register_activation_hook( __FILE__, function(){
    require_once DOX2TA_COMMUNITY_PATH . 'includes/class-dox2ta-activator.php';
    Dox2ta_Activator::activate();
});

// Init plugin
add_action( 'plugins_loaded', function(){
    load_plugin_textdomain( 'dox2ta-community', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
});

// Enqueue assets only when shortcode is present
function dox2ta_register_assets(){
    wp_register_style( 'dox2ta-community', DOX2TA_COMMUNITY_URL . 'assets/css/dox2ta-community.css', [], DOX2TA_COMMUNITY_VERSION );
    wp_register_script( 'dox2ta-community', DOX2TA_COMMUNITY_URL . 'assets/js/dox2ta-community.js', [ 'jquery' ], DOX2TA_COMMUNITY_VERSION, true );
}
add_action( 'init', 'dox2ta_register_assets' );

// Shortcode
add_action( 'init', function(){
    require_once DOX2TA_COMMUNITY_PATH . 'includes/class-dox2ta-shortcode.php';
    ( new Dox2ta_Shortcode() )->register();
});

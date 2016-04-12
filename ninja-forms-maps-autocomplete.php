<?php
/**
 * Plugin Name: Ninja Forms Google Maps autocomplete Field
 * Plugin URI: http://www.rlaurent.com
 * Description: This plugin uses Google Maps autocomplete field on front-end for adding address.
 * Version: 1.0
 * Author: Romain LAURENT
 * Author URI: http://www.rlaurent.com
 * License:  GPLv2 or later
 */
 
 
// Extension directory
define("NINJA_FORMS_MAPS_AUTOCOMPLETE_FIELD_DIR", WP_PLUGIN_DIR."/".basename( dirname( __FILE__ ) ) );

// Google API Key
define("GOOGLE_API_KEY", "" );
 
// Check if Ninja Forms plugin is activated
if( in_array( 'ninja-forms/ninja-forms.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	// Load field file
	require_once( NINJA_FORMS_MAPS_AUTOCOMPLETE_FIELD_DIR . "/includes/fields/maps.php" );

}
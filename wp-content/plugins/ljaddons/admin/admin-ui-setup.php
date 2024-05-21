<?php
/**
 * Admin setup for the plugin
 *
 * @since 1.0
 * @function	ljaddons_add_menu_links()		Add admin menu pages
 * @function	ljaddons_register_settings	Register Settings
 * @function	ljaddons_validater_and_sanitizer()	Validate And Sanitize User Input Before Its Saved To Database
 * @function	ljaddons_get_settings()		Get settings from database
 */

// Exit if accessed directly
if ( ! defined('ABSPATH') ) exit; 
 
/**
 * Add admin menu pages
 *
 * @since 1.0
 * @refer https://developer.wordpress.org/plugins/administration-menus/
 */
function ljaddons_add_menu_links() {
	add_menu_page ( __('Addon Options','ljaddons'), __('Addon Options','ljaddons'), 'update_core', 'ljaddons','ljaddons_admin_interface_render'  );
}
add_action( 'admin_menu', 'ljaddons_add_menu_links' );

/**
 * Register Settings
 *
 * @since 1.0
 */
function ljaddons_register_settings() {

	// Register Setting
	register_setting( 
		'ljaddons_settings_group', 			// Group name
		'ljaddons_settings', 					// Setting name = html form <input> name on settings form
		'ljaddons_validater_and_sanitizer'	// Input sanitizer
	);
	
	// Register A New Section
    add_settings_section(
        'ljaddons_general_settings_section',							// ID
        __('General Settings', 'ljaddons'),		// Title
        'ljaddons_general_settings_section_callback',					// Callback Function
        'ljaddons'											// Page slug
    );
	
	// General Settings - Default Level
    add_settings_field(
        'ljaddons_general_settings_field',							// ID
        __('Enable companies', 'ljaddons'),					// Title
        'ljaddons_general_settings_field_callback',					// Callback function
        'ljaddons',											// Page slug
        'ljaddons_general_settings_section'							// Settings Section ID
    );

	
}
add_action( 'admin_init', 'ljaddons_register_settings' );

/**
 * Validate and sanitize user input before its saved to database
 *
 * @since 1.0
 */
function ljaddons_validater_and_sanitizer ( $settings ) {
	
	// Sanitize text field
	$settings['companies_enabled'] = sanitize_text_field($settings['companies_enabled']);
	return $settings;
}

/**
 * Get settings from database
 *
 * @return	Array	A merged array of default and settings saved in database. 
 *
 * @since 1.0
 */
function ljaddons_get_settings() {

	$defaults = array(
				'companies_enabled' 	=> 0,
			);

	$settings = get_option('ljaddons_settings', $defaults);
	
	return $settings;
}
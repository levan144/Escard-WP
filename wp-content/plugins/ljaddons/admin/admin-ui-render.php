<?php
/**
 * Admin UI setup and render
 *
 * @since 1.0
 * @function	ljaddons_general_settings_section_callback()	Callback function for General Settings section
 * @function	ljaddons_general_settings_field_callback()	Callback function for General Settings field
 * @function	ljaddons_admin_interface_render()				Admin interface renderer
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Callback function for General Settings section
 *
 * @since 1.0
 */
function ljaddons_general_settings_section_callback() {
	// echo '<p>' . __('A long description for the settings section goes here.', 'loyalty-points') . '</p>';
}

/**
 * Callback function for General Settings field
 *
 * @since 1.0
 */
function ljaddons_general_settings_field_callback() {	

	// Get Settings
	$settings = ljaddons_get_settings();

	// General Settings. Name of form element should be same as the setting name in register_setting(). 
	?>
	<fieldset>
	    <label for="companies_enabled_on">ON</label>
	    <input type="radio" id="companies_enabled_on" value="1" <?php if($settings['companies_enabled']): ?> checked <?php endif; ?> name="ljaddons_settings[companies_enabled]">
	    <label for="companies_enabled_off">OFF</label>
	    <input type="radio" id="companies_enabled_off" value="0" <?php if(!$settings['companies_enabled']): ?> checked <?php endif; ?> name="ljaddons_settings[companies_enabled]">
	</fieldset>
	<?php if($settings['companies_enabled']){ ?>
	<code>companies Carousel Shortcode: [companies_carousel category="ids: 1,2,3"]</code>
	<?php
	}
}

/**
 * Admin interface renderer
 *
 * @since 1.0
 */ 
function ljaddons_admin_interface_render () {
	
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	/**
	 * If settings are inside WP-Admin > Settings, then WordPress will automatically display Settings Saved. If not used this block
	 * @refer	https://core.trac.wordpress.org/ticket/31000
	 * If the user have submitted the settings, WordPress will add the "settings-updated" $_GET parameter to the url
	 *
	if ( isset( $_GET['settings-updated'] ) ) {
		// Add settings saved message with the class of "updated"
		add_settings_error( 'loyalty_settings_saved_message', 'loyalty_settings_saved_message', __( 'Settings are Saved', 'ljaddons' ), 'updated' );
	}
 
	// Show Settings Saved Message
	settings_errors( 'ljaddons_settings_saved_message' ); */?> 
	
	<div class="wrap">	
		<h1>Lj Addons Options</h1>
		
		<form action="options.php" method="post">		
			<?php
			// Output nonce, action, and option_page fields for a settings page.
			settings_fields( 'ljaddons_settings_group' );
			
			// Prints out all settings sections added to a particular settings page. 
			do_settings_sections( 'ljaddons' );	// Page slug
			
			// Output save settings button
			submit_button( __('Save Settings', 'ljaddons') );
			?>
		</form>
	</div>
	<?php
}
<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load basic setup. Plugin list links, text domain, footer links etc. 
require_once( LOYALTY_STARTER_PLUGIN_DIR . 'admin/admin-ui-setup.php' );
require_once( LOYALTY_STARTER_PLUGIN_DIR . 'admin/admin-ui-render.php' );
require_once( LOYALTY_STARTER_PLUGIN_DIR . 'public/companies_shortcodes.php' );
require_once( LOYALTY_STARTER_PLUGIN_DIR . 'admin/partials/companies_cpt.php' );
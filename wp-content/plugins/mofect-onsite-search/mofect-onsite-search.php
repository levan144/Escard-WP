<?php
/*
Plugin Name: Mofect On-Site Search
Plugin URI: https://mofect.com/demo/onsite-search
Author: Mofect
Author URI: https://mofect.com
Version: 1.0.1
Description: This plugin offers enhanced On-Site Search for your WordPress blog or WooCommerce online shop, response the value-based content when the visitors are searching on your site, help you improve the direction of content building or sales conversion rate effectively.
Text Domain: moss
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( !class_exists('woocommerce')){

}

if(!class_exists('Mofect_OnSite_Search')){
	class Mofect_OnSite_Search{

		/**
         * Instance of Mofect_OnSite_Search
         * @since 1.0.0
         */
		public static function instance() {
			// Store the instance locally to avoid private static replication
			static $instance = null;
			
			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self;
				$instance->init();
			}

			return $instance;
		}
        
        /**
         * Initialize
         * @since 1.0.0
         */
		private function init() {

			register_activation_hook( __FILE__, array( 'Mofect_OnSite_Search', 'activate' ) );

			self::define_constants();

			$this->include_files();

			add_action( 'plugins_loaded', array( $this, 'version_check' ) );
			add_action( 'init', 		  array( $this, 'textdomain' ) ); 
			add_action( 'setup_theme', 	  array( $this, 'load_files' ) ); 
			add_action( 'wp_enqueue_scripts', 		array( $this, 'scripts' ) ); 
		}

		/**
		 * Define constants
		 * @since 1.0.0
		 */ 
		static private function define_constants()
		{
			define('MOSS_VERSION', '1.0.0');
			define('MOSS_FILE', __FILE__ );
			define('MOSS_DEBUG', FALSE );
			define('MOSS_DIR', plugin_dir_path(__FILE__));
			define('MOSS_URI', plugins_url( '', MOSS_FILE ));
		}

		/**
         * Localize
         * @since 1.0.0
         */
		public function textdomain() {
		    $domain = 'moss';
		    $locale = apply_filters('plugin_locale', get_locale(), $domain);
		    load_textdomain($domain, WP_LANG_DIR.'/mofect-onsite-search/'.$domain.'-'.$locale.'.mo');
		    load_plugin_textdomain($domain,FALSE,dirname(plugin_basename(__FILE__)).'/languages/');
		}

		/**
         * Include Files
         * @since 1.0.0
         */
		public function include_files(){
			require_once MOSS_DIR.'inc/metabox/init.php';
			require_once MOSS_DIR.'inc/class-template-loader.php';
			require_once MOSS_DIR.'inc/class-post-type.php';
			require_once MOSS_DIR.'inc/class-shortcodes.php';
			require_once MOSS_DIR.'inc/class-keyword.php';
			require_once MOSS_DIR.'inc/class-search.php';
		}

		/**
         * Load Files
         * @since 1.0.0
         */
		public function load_files(){
			require_once MOSS_DIR.'inc/functions.php';
			self::load_folder(MOSS_DIR.'inc/shortcodes/');
			self::load_folder(MOSS_DIR.'inc/widgets/');
			self::load_folder(MOSS_DIR.'inc/ua-parser/');
			require_once MOSS_DIR.'inc/class-admin.php';
			require_once MOSS_DIR.'inc/class-tracking.php';
		}

		/** 
		 * recursively include Folder 
		 * @since 1.0.0
		 */
	    public static function load_folder($path){
           
            $module_path = glob( $path . '*.php' );
            foreach($module_path as $file){
                require_once	($file);
			}

			$sub_dirs = scandir($path);
			foreach($sub_dirs as $tmp_dir){
				if( $tmp_dir != "." && $tmp_dir != ".." && $tmp_dir != ".DS_Store"){
					$path_value = $path.$tmp_dir;
					if(is_dir($path_value)){
						Mofect_OnSite_Search::load_folder($path_value.DIRECTORY_SEPARATOR);
					}
				}
			}
	    }
		
		/**
	     * Load Templates
	     * @since 1.0.0
		 */
		public static function load_template($template_name, $args = array()){
			if(!isset($args)){
		 		$args = array();
			}
			require_once(MOSS_DIR.'inc/class-template-loader.php');

			$templates = new MOSS_Template_Loader;
	          
          	$data = array();

          	if(isset($args) && null !== $args){
	         	foreach($args as $key => $value){
	               $data[$key] = $value; 
	        	}
            }
          
	        $templates
	            ->set_template_data( $data, 'mofect_data')
	            ->get_template_part( $template_name );
		}


		public static function activate() {
			add_option( 'moss_initial_version', MOSS_VERSION, '', 'no' );
		}

		/**
		 * Trigger a moss_version_changed action if the version has changed
		 * @since 1.0.0
		 */
		public function version_check(){
			$active_version = get_option( 'moss_initial_version', false );
			if( empty( $active_version ) || $active_version !== MOSS_VERSION ) {
				do_action( 'moss_version_changed' );
				update_option( 'moss_initial_version', MOSS_VERSION );
			}
		}

		/**
		 * Scripts
		 * @since 1.0.0
		 */
		public function scripts(){
			  $minify = !MOSS_DEBUG ? '.min' : '';

		      wp_enqueue_style( 'moss-styles', MOSS_URI . '/assets/css/moss'.$minify.'.css' );
		      wp_enqueue_script('moss-script', MOSS_URI .'/assets/js/moss'.$minify.'.js', array('jquery'), null, true );
			  wp_localize_script( 'moss-script', 'moss_data', array(
			  		'home_url' => home_url('/'),
			   		'ajax_url' => admin_url( 'admin-ajax.php' ),
			   		'live_search' => (null !== get_option('moss_live_search') && get_option('moss_live_search') == 1)? 1 : 0
			  ));
		}
		
	}

	Mofect_OnSite_Search::instance();
}

require_once(MOSS_DIR."inc/plugin-update-checker/plugin-update-checker.php");
$MOSS_UpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://www.themevan.com/update-server/?action=get_metadata&slug=mofect-onsite-search',
	__FILE__,
	'mofect-onsite-search'
);

<?php
/**
 * Abstract class for Shortcodes
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

if ( ! class_exists( 'MOSS_Shortcode' ) ) {
	abstract class MOSS_Shortcode {
		private $shortcode_name;
		public static $data_context = 'mofect_data';

		public function __construct($shordcode_name) {
		  	$this->shortcode_name = $shordcode_name;
		  	add_shortcode( $this->get_shortcode_name(),array($this, 'register_shortcode'));
		  	add_action( 'vc_before_init', array( $this, 'wpb_map_options' ) );
		  	add_action( 'init', array( $this, 'kc_map_options' ) );
		}
		
		public function get_shortcode_name(){
			return $this->shortcode_name;
		}

		// Register the shortcode with WordPress
		public abstract function register_shortcode( $atts, $content = null );

		// Map the shortcode parameters with the Visual Composer editor
		public abstract function wpb_map_options();

		// Map the shortcode parameters with the King Composer editor
		public abstract function kc_map_options();

		// Load Shortcode Template
		public function shortcode_template($element_vars){
		   Mofect_OnSite_Search::load_template(self::get_shortcode_name(),$element_vars);
		}
	}
}
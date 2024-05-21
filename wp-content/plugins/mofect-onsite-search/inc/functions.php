<?php
/**
 * Reflection Functions of Mofect On-Site Search Classes
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

/* Load Templates */
if(!function_exists('moss_load_template')){
	function moss_load_template($template_name, $args = array()){
		 if(!isset($args)){
		 	$args = array();
		 }
		 Mofect_OnSite_Search::load_template($template_name, $args);
	}
}

 /* Out search bar */
if(!function_exists('moss_searchbar')){
	function moss_searchbar($args){
		 if(!isset($args)){
		 	$args = array(
		 	  'label'          => esc_html__('Search Product','moss'),
		 	  'placeholder'    => esc_html__('Keyword','moss'),
		 	  'post_type'      => 'post',
		 	  'posts_per_page' => '12',
		 	  'orderby'        => 'date',
		 	  'order'          => 'desc',
		 	  'category'	   => '',
		 	  'layout'		   => 'list',
		 	  'extra_class'	   => ''
		    );
		 }
		 
		 Mofect_OnSite_Search::load_template('mofect_searchbar',$args);
	}
}
<?php
/**
 * Custom Post Type
 * @package Mofect On-Site Search
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'MOSS_PostType' ) ) {
	class MOSS_PostType{

		public $post_type = 'moss-keyword';
		public $post_type_slug = 'moss-keyword';
		public $post_type_description;
		public $post_type_supports = array( 'title');
		public $post_type_labels;
		public $public = true;
		public $publicly_queryable = true;
		public $show_ui = true;
		public $show_in_menu = true;
		public $query_var = true;
		public $rewrite = true;
		public $capability_type = 'post';
		public $has_archive = true;
		public $hierarchical = false;
		public $menu_position = null;
		public $exclude_from_search = false;
		public $show_in_nav_menus = false;
		public $show_in_admin_bar = false;
		public $menu_icon = null;
		public $map_meta_cap = null;
		public $can_export = true;
		public $show_in_rest = false;

		public $register_taxonomy = true;
		public $taxonomy   = 'moss-keyword-cat';
		public $taxonomy_slug = 'moss-keyword-cat';
		public $taxonomy_labels;
		public $taxonomy_public = true;
		public $taxonomy_publicly_queryable = true;
		public $taxonomy_show_ui = true;
		public $taxonomy_show_in_menu = true;
		public $taxonomy_show_in_nav_menus = false;
		public $taxonomy_show_tagcloud = false;
		public $taxonomy_show_in_rest = false;
		public $taxonomy_show_in_quick_edit = true;
		public $taxonomy_show_admin_column = false;
		public $taxonomy_query_var = true;

		/**
		 * Init Portfolio Class
		 */
		public function __construct(){
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'register_taxonomies'), 0 );
		}

		/**
		 * Register Portfolio Post Type
		 */
		public function register_post_type(){
			/**
			 * Register a book post type.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/register_post_type
			 */
			
			$args = apply_filters('moss_post_type_args', array(
				'labels'             => $this->post_type_labels,
		        'description'        => $this->post_type_description,
				'public'             => $this->public,
				'publicly_queryable' => $this->publicly_queryable,
				'show_ui'            => $this->show_ui,
				'show_in_menu'       => $this->show_in_menu,
				'show_in_nav_menus'  => $this->show_in_nav_menus,
				'show_in_admin_bar'  => $this->show_in_admin_bar,
				'query_var'          => $this->query_var,
				'capability_type'    => $this->capability_type,
				'has_archive'        => $this->has_archive,
				'hierarchical'       => $this->hierarchical,
				'menu_position'      => $this->menu_position,
				'menu_icon'			 => $this->menu_icon,
				'supports'           => $this->post_type_supports,
				'exclude_from_search'=> $this->exclude_from_search,
				'map_meta_cap'		 => $this->map_meta_cap,
				'can_export'		 => $this->can_export,
				'show_in_rest'		 => $this->show_in_rest
			));

			if($this->rewrite){
				$args['rewrite'] = array('slug' => $this->post_type_slug );
			}else{
				$args['rewrite'] = $this->rewrite;
			}

			register_post_type( $this->post_type, $args );
		}

		/**
		 * Register Taxonomy
		 */
		public function register_taxonomies() {
			
			if(!$this->register_taxonomy){
				return;
			}

			$args = apply_filters('moss_taxonomy_labels', array(
				'hierarchical'       => true,
				'labels'             => $this->taxonomy_labels,
				'public'             => $this->taxonomy_public,
				'publicly_queryable' => $this->taxonomy_publicly_queryable,
				'show_ui'            => $this->taxonomy_show_ui,
				'show_in_menu'       => $this->taxonomy_show_in_menu,
				'show_in_nav_menus'  => $this->taxonomy_show_in_nav_menus,
				'show_admin_column'  => $this->taxonomy_show_admin_column,
				'show_tagcloud'      => $this->taxonomy_show_tagcloud,
				'show_in_rest'  	 => $this->taxonomy_show_in_rest,
				'show_in_quick_edit' => $this->taxonomy_show_in_quick_edit,
				'query_var'  		 => $this->taxonomy_query_var,
				'rewrite'            => array( 'slug' => $this->taxonomy_slug ),
			));

			register_taxonomy( $this->taxonomy, $this->post_type, $args );
		}
	}
}
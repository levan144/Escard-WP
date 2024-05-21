<?php
/**
 * Keyword Post Type
 * @package Mofect On-Site Search
 * @since 1.0.0
 */

if(!class_exists('MOSS_Keyword')){
	class MOSS_Keyword{
		public function __construct() {
		  	$this->keyword_post_type();

		  	add_action( 'cmb2_admin_init', array( $this, 'metabox' ) );
		  	add_action( 'add_meta_boxes', array( $this, 'add_statistics_meta_boxes' ) );
		}

		/**
		 * Define constants
		 * @since 1.0.0
		 */ 
		public function keyword_post_type(){
			$moss_keyword = new MOSS_PostType();

			$moss_keyword->post_type 		= 'moss-keyword';
			$moss_keyword->post_type_slug   = 'moss-keyword';
			$moss_keyword->show_in_menu     = false;
			$moss_keyword->publicly_queryable = false;
            $moss_keyword->public = false;
            $moss_keyword->has_archive = false;
            $moss_keyword->query_var = false;
            $moss_keyword->rewrite = true;
            $moss_keyword->exclude_from_search = true;

		  	$moss_keyword->post_type_labels = array(
                    'name'               => _x( 'OnSite Search', 'post type general name', 'moss' ),
                    'singular_name'      => _x( 'Keyword', 'post type singular name', 'moss' ),
                    'menu_name'          => _x( 'OnSite Search', 'admin menu', 'moss' ),
                    'name_admin_bar'     => _x( 'Keyword', 'add new on admin bar', 'moss' ),
                    'add_new'            => _x( 'Add New Keyword', 'portfolio', 'moss' ),
                    'add_new_item'       => __( 'Add New Keyword', 'moss' ),
                    'new_item'           => __( 'New Keyword', 'moss' ),
                    'edit_item'          => __( 'Edit Keyword', 'moss' ),
                    'view_item'          => __( 'View Keyword', 'moss' ),
                    'all_items'          => __( 'All Keywords', 'moss' ),
                    'search_items'       => __( 'Search Keywords', 'moss' ),
                    'parent_item_colon'  => __( 'Parent Keywords:', 'moss' ),
                    'not_found'          => __( 'No keywords found.', 'moss' ),
                    'not_found_in_trash' => __( 'No keywords found in Trash.', 'moss' )
            );

            $moss_keyword->taxonomy       = 'moss-keyword-cat';
		    $moss_keyword->taxonomy_slug = 'moss-keyword-cat';
		    $moss_keyword->taxonomy_show_in_menu   = false;
		    $moss_keyword->taxonomy_publicly_queryable = false;
            $moss_keyword->taxonomy_public = false;
            $moss_keyword->taxonomy_query_var = false;

            $moss_keyword->taxonomy_labels =  array(
                    'name'              => _x( 'Keyword Categories', 'taxonomy general name', 'moss' ),
                    'singular_name'     => _x( 'Keyword Category', 'taxonomy singular name', 'moss' ),
                    'search_items'      => __( 'Search Keyword Categories', 'moss' ),
                    'all_items'         => __( 'All Keyword Categories', 'moss' ),
                    'parent_item'       => __( 'Parent Keyword Category', 'moss' ),
                    'parent_item_colon' => __( 'Parent Keyword Category:', 'moss' ),
                    'edit_item'         => __( 'Edit Keyword Category', 'moss' ),
                    'update_item'       => __( 'Update Keyword Category', 'moss' ),
                    'add_new_item'      => __( 'Add New Keyword Category', 'moss' ),
                    'new_item_name'     => __( 'New Keyword Category Name', 'moss' ),
                    'menu_name'         => __( 'Keyword Category', 'moss' ),
            );
		}

		/**
		 * Metabox
		 * @since 1.0.0
		 */ 
		public function metabox(){
			$prefix = 'moss_keyword_';

			/**
			 * Sample metabox to demonstrate each field type included
			 */
			$moss_keyword = new_cmb2_box( array(
				'id'            => $prefix . 'metabox',
				'title'         => esc_html__( 'Keyword Setting', 'moss' ),
				'object_types'  => array( 'moss-keyword' )
			) );

			$moss_keyword->add_field( array(
				'name'       => esc_html__( 'Specific Content', 'moss' ),
				'desc'       => esc_html__( 'Search the post title and assign the specific content to the result of this keyword. When the visitor searching this keyword, these posts will be suggested to the visitor.', 'moss' ),
				'id'         => $prefix . 'specific_content',
				'type'       => 'post_search_ajax',
				'limit'      	=> 50, 		// Limit selection to X items only (default 1)
				'sortable' 	 	=> true, 	// Allow selected items to be sortable (default false)
				'query_args'	=> array(
					'post_type'			=> apply_filters('moss_post_type_support', array('post','product','download')),
					'post_status'		=> array( 'publish' ),
					'posts_per_page'	=> -1
				)
			) );
		}

		/**
		 * Define metabox for keyword statistics
		 * @since 1.0.0
		 */
		public function add_statistics_meta_boxes() {
		    add_meta_box( 
		        'moss-keyword-statistics',
		        __( 'Keyword Statistics','moss' ),
		        array($this,'render_keyword_statistics_metabox'),
		        'moss-keyword',
		        'normal',
		        'high'
		    );
		}

		/**
		 * Render metabox for keyword statistics
		 * @since 1.0.0
		 */
		public function render_keyword_statistics_metabox(){
			 global $post;
			 $keyword_statistics = get_post_meta($post->ID, '_moss_keyword_statistics', true);
			 echo '<div class="mofect_keywords_statistics">
					  <div class="status">'.esc_html__('Status','moss').'</div>
					  <div class="post_type">'.esc_html__('Post Type','moss').'</div>
					  <div class="date">'.esc_html__('Date','moss').'</div>
					  <div class="ip">'.esc_html__('IP','moss').'</div>
					  <div class="country">'.esc_html__('Country','moss').'</div>
					  <div class="city">'.esc_html__('city','moss').'</div>
					  <div class="device">'.esc_html__('Device','moss').'</div>
					  '.do_action('moss_keywords_statistics_column').'
				   </div>';

			 echo '<div class="mofect_keywords_statistics">';
			 
			 for($i=0;$i<count($keyword_statistics);$i++){
			 	foreach($keyword_statistics[$i] as $key => $val){
					 if($key == "status"){
						if($val == "true"){
							echo'<div class="'.$key.'">'.esc_html__('Success', 'moss').'</div>';
						}else{
							echo'<div class="'.$key.'">'.esc_html__('No result','moss').'</div>';
						}
					 }else{
						echo'<div class="'.$key.'">'.$val.'</div>';
					 }
			    }
			 }
			 echo'</div>';
		}

		/**
		 *	Static Filter
		 */
		public static function statistics_filter(){
			echo '
			<a class="keyword-statistics-filter" data-ajax-action="moss_ajax_statistics_year">'.esc_html__('Year','moss').'</a>
			<a class="keyword-statistics-filter" data-ajax-action="moss_ajax_statistics_month">'.esc_html__('Month','moss').'</a>
			<a class="keyword-statistics-filter" data-ajax-action="moss_ajax_statistics_week">'.esc_html__('Week','moss').'</a>
			<a class="keyword-statistics-filter" data-ajax-action="moss_ajax_statistics_yesterday">'.esc_html__('Yesterday','moss').'</a>
			<a class="keyword-statistics-filter" data-ajax-action="moss_ajax_statistics_today">'.esc_html__('Today','moss').'</a>';
		}
	}

	return new MOSS_Keyword();
}
<?php
/**
 * Search Class for On-Site Search Features
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

if ( ! class_exists( 'MOSS_Search' ) ) {
	class MOSS_Search {
		
		public function __construct() {
		  	add_action('wp_ajax_moss_ajax_search', 		  array($this, 'moss_ajax_search') );
			add_action('wp_ajax_nopriv_moss_ajax_search', array($this, 'moss_ajax_search') );
			add_action('wp_ajax_moss_save_search_keyword', 	  array($this, 'moss_ajax_save_search_keyword') );
			//add_action('wp_footer',        			  array($this, 'search_action') );
			add_action('moss_save_search_keyword',        array($this, 'save_keyword') );

			add_action('wp_ajax_moss_get_tracking_data',         array($this, 'get_tracking_data') );
            add_action('wp_ajax_nopriv_get_tracking_data',       array($this, 'get_tracking_data') );
            add_action('wp_ajax_moss_get_recent_tracking_data',  array($this, 'get_recent_tracking_data') );
			add_action('wp_ajax_nopriv_get_recent_tracking_data',array($this, 'get_recent_tracking_data') );
			
			//keyword statistics filter
			add_action('wp_ajax_moss_ajax_statistics_year', 		  array($this, 'moss_ajax_statistics_year'));
			add_action('wp_ajax_moss_ajax_statistics_month', 		  array($this, 'moss_ajax_statistics_month') );
			add_action('wp_ajax_moss_ajax_statistics_week', 		  array($this, 'moss_ajax_statistics_week'));
			add_action('wp_ajax_moss_ajax_statistics_yesterday', 	  array($this, 'moss_ajax_statistics_yesterday') );
			add_action('wp_ajax_moss_ajax_statistics_today', 	      array($this, 'moss_ajax_statistics_today') );
		}

		/**
		 * Ajax Search
		 * @since 1.0.0
		 */
		public function moss_ajax_search(){
			  $keyword  	  = sanitize_title($_POST['keyword']);
			  $post_type	  = isset($_POST['post_type']) ? $_POST['post_type'] : '';
			  $posts_per_page = isset($_POST['posts_per_page']) ? $_POST['posts_per_page'] : '20';
			  $order		  = isset($_POST['order']) ? $_POST['order'] : 'desc';
			  $orderby		  = isset($_POST['orderby']) ? $_POST['orderby'] : 'date';
			  $category		  = isset($_POST['category']) ? $_POST['category'] : '';

			  /* Assign related result */
			  $this->assigned_results($keyword);

			  $args = array(
			    's' 				=> $keyword
			  );

			  if($posts_per_page !==''){
			  	$args['posts_per_page'] = $posts_per_page;
			  }

			  if($post_type !==''){
			  	$args['post_type'] = $post_type;

			  	if($category !== '' ){
				  	switch($post_type){
					  	 case 'product':
					  	   $args['product_cat'] = $category;
					  	   break;

					  	 case 'download':
					  	   $args['download_category'] = $category;
					  	   break;

					  	 default:
					  	   $args['category'] = $category;
					}
			    }
			  }else{
			  	if($category !== '' ){
			  		$args['category'] = $category;
			  	}
			  }

			  if($order !==''){
			  	$args['order'] = $order;
			  }

			  if($orderby !==''){
			  	$args['orderby'] = $orderby;
			  }

			  $moss_query = new WP_Query($args); 
			 
			  if ($moss_query->have_posts()):
			       while ($moss_query->have_posts()) : $moss_query->the_post(); 
					 	Mofect_OnSite_Search::load_template('mofect_search',$args);
				   endwhile;
				   wp_reset_postdata();
			  else:
			  	  if($this->is_assigned($keyword)){
			  	   	  return;
			  	  }
			      
			      Mofect_OnSite_Search::load_template('mofect_search_none', array('keyword' => $keyword));

			      if(null !== get_option('moss_show_featured') && get_option('moss_show_featured') == 1){
			          $this->featured_results();
			  	  }
			  endif;

			  exit;
		}

		/**
		 * Assign the related results
		 * @since 1.0.0
		 */
		public function assigned_results($keyword){

			$get_keyword = get_page_by_title($keyword, OBJECT, 'moss-keyword');

			$ids = get_post_meta($get_keyword->ID,'moss_keyword_specific_content',true);

			if($ids == ''){
				return;
			}

			if(isset($ids) && $ids !== ''){
				$args = array(
					'post__in' => $ids,
					'posts_per_page' => count($ids),
					'orderby'	=> 'post__in',
	          		'order'  	=> 'desc',
	          		'post_type' => apply_filters('moss_post_type_support', array('post','product','download'))
				);

				$moss_query = new WP_Query($args); 
				while ($moss_query->have_posts()) : $moss_query->the_post();  
					Mofect_OnSite_Search::load_template('mofect_search_'.get_post_type());
			    endwhile;
			    wp_reset_postdata();

			    return true;
			}
		}

		/**
		 * Add the featured results
		 * @since 1.0.0
		 */
		public function featured_results(){

			$ids = get_option('moss_featured_content');
			$ids = explode(',', $ids);

			if(isset($ids) && $ids !== ''){
				$args = array(
					'post__in' => $ids,
					'posts_per_page' => count($ids),
					'orderby'	=> 'post__in',
	          		'order'  	=> 'desc',
	          		'post_type' => apply_filters('moss_post_type_support', array('post','product','download'))
				);

				$moss_query = new WP_Query($args); 
				while ($moss_query->have_posts()) : $moss_query->the_post();  
					Mofect_OnSite_Search::load_template('mofect_search_'.get_post_type());
			    endwhile;
			    wp_reset_postdata();

			    return true;
			}
		}

		/**
		 * Check if assigned content exist
		 * @since 1.0.0
		 */
		public function is_assigned($keyword){

			$get_keyword = get_page_by_title($keyword, OBJECT, 'moss-keyword');

			if(isset($get_keywords->ID)){
			   $ids = get_post_meta($get_keyword->ID,'moss_keyword_specific_content',true);
		    }

			if(isset($ids) && $ids !== ''){
			    return true;
			}else{
				return false;
			}
		}

		/**
		 * Define hook in search page
		 * @since 1.0.0
		 */
		public function search_action(){
			if(is_search()){
				do_action('moss_save_search_keyword');
			}
		}

		/**
		 * Save Keyword
		 * Meta Key: _moss_keyword_count, 
		 			 _moss_keyword_status, 
		 			 _moss_visitor_location,
		 			 _moss_visitor_device,
		 			 _moss_visitor_ip,
		 			 _moss_keyword_date,

		 * @since 1.0.0
		 */
		public function save_keyword(){
			  if(!isset($_GET['s']) || $_GET['s'] == ''){
			  	 return;
			  }

			  if(null == get_option('moss_tracking_keyword') || get_option('moss_tracking_keyword') == 0){
			  	 return;
			  }

			  $keyword   = sanitize_title($_GET['s']);
			  $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : esc_html__('All','moss');
			  $this->_save_search_key($keyword, $post_type);
		}

		public function moss_ajax_save_search_keyword(){
			$keyword  	  = sanitize_title($_POST['keyword']);
			$post_type	  = $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : esc_html__('All','moss');
			$this->_save_search_key($keyword, $post_type);
		}

		private function _save_search_key($keyword, $post_type){
			  /* Get Keyword Status */
			  $result = new WP_Query(array( 's' => $keyword, 'post_type'=>$post_type ));
			  //$keyword_status = $result->have_posts() ? esc_html__('Success', 'moss'):esc_html__('No result','moss');
			  $keyword_status = $result->have_posts() ? "true" : "false"; 
			
			  /* Get visitors info */
			  $tracking = new MOSS_tracking();

			  $keyword_info = array();
			  $keyword_statistics = array(
			  	     'status'   => $keyword_status,
			  	     'post_type'=> $post_type,
	               	 'date'     => date( 'd F Y H:i:s', current_time( 'timestamp', 0 ) ),
	               	 'ip'	    => $tracking->ip,
	               	 'country'  => $tracking->country,
	               	 'city'  	=> $tracking->city,
	               	 'device'   => $tracking->user_agent->device->family
			  );
			  
			  $keyword_statistics = apply_filters('moss_keyword_statistics_data',$keyword_statistics);
	          $keyword_info[] = $keyword_statistics;

			  /* Check if the keyword exist */
			  $existing_keywords = get_page_by_title($keyword, OBJECT, 'moss-keyword');
			  
	          if ( !$existing_keywords ){
	          	   $new_keyword = array(
		                'post_type' => 'moss-keyword',
		                'post_title' => $keyword,
		                'post_content' => '',
		                'post_status' => 'publish',
		                'post_author' => 1
		           );
	               $new_keyword_id = wp_insert_post($new_keyword);	
	               add_post_meta($new_keyword_id, '_moss_keyword_statistics', $keyword_info);	
	          }else{
					$current_meta_value = get_post_meta($existing_keywords->ID, '_moss_keyword_statistics', true);
					$current_meta_value[] = $keyword_statistics;
			       	update_post_meta($existing_keywords->ID, '_moss_keyword_statistics', $current_meta_value);	
			  }
		}

		public function moss_ajax_statistics_year(){
			$results = MOSS_tracking::retrive_user_track_data_year();
			wp_send_json_success($return);
		}

		public function moss_ajax_statistics_month(){
			$results = MOSS_tracking::retrive_user_track_data_month();
			wp_send_json_success($results);
		}

		public function moss_ajax_statistics_week(){
			$results = MOSS_tracking::retrive_user_track_data_week();
			wp_send_json_success($results);
		}

		public function moss_ajax_statistics_yesterday(){
			$results = MOSS_tracking::retrive_user_track_data_yesterday();
			wp_send_json_success($results);
		}

		public function moss_ajax_statistics_today(){
			$results = MOSS_tracking::retrive_user_track_data_today();
			wp_send_json_success($results);
		}

		public function get_tracking_data(){
            $tracked_data = MOSS_tracking::retrive_user_track_data();
			$tracked_json_data = json_encode($tracked_data);
			wp_send_json_success($tracked_json_data);
        }

        public function get_recent_tracking_data(){
            $tracked_data = MOSS_tracking::retrive_rencent_user_track_data();
            $tracked_json_data = json_encode($tracked_data);
            wp_send_json_success($tracked_json_data);
        }
	}

	return new MOSS_Search();
}
<?php
/**
 * MOSS_Admin Class for On-Site Search Features
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

if ( ! class_exists( 'MOSS_Admin' ) ) {
	class MOSS_Admin {
		
		public $options = array();

		public function __construct() {
			
			$this->options();

			add_action( 'admin_menu', 	  			array( $this, 'menu_page' ) );
		  	add_action( 'admin_enqueue_scripts', 	array( $this, 'admin_scripts' ) ); 
			add_action( 'moss_admin_page_header', array( $this, 'admin_page_header' ) );
			add_action( 'moss_admin_page_footer', array( $this, 'admin_page_footer' ) );
			add_action( 'admin_init', 				array( $this, 'register_settings' ) );
			add_action( 'moss_settings', 			array( $this, 'setting_fields' ) );
		}

		/**
		 * Add admin scripts
		 * @since 1.0.0
		 */
		public function admin_scripts(){
			  $minify = !MOSS_DEBUG ? '.min' : '';

			  wp_enqueue_style( 'moss-admin-styles', MOSS_URI .'/inc/admin/assets/css/admin'.$minify.'.css' );
			  wp_enqueue_script('moss-chart-script', MOSS_URI .'/inc/admin/assets/js/Chart.js', array('jquery'), null, true );
			  wp_enqueue_script('moss-echart-script', MOSS_URI .'/inc/admin/assets/js/echarts.min.js', array('jquery'), null, true );
			  wp_enqueue_script('moss-echart-word-map-script', MOSS_URI .'/inc/admin/assets/js/world.js', null, null, true );
			  wp_enqueue_script('moss-admin-script', MOSS_URI .'/inc/admin/assets/js/admin'.$minify.'.js', array('jquery'), null, true );
			  wp_localize_script( 'moss-admin-script', 'mofect_admin_data', array(
					   'ajax_url' => admin_url( 'admin-ajax.php' ),
					   'edit_post_url' => admin_url( 'post.php' ),
					   'no_records_message' => esc_html__('No Records','moss'), 
			  ));
		}

		/**
	     * Register Admin Page
	     * @since 1.0.0
		 */
		public function menu_page() {
			add_menu_page( 
				esc_html__('Mofect Onsite Search','moss'), 
				esc_html__('Mofect Search','moss'), 
				'manage_options', 
				'mofect-onsite-search', 
				array($this,'menu_page_ui'), 
				MOSS_URI.'/inc/admin/assets/img/icon.png' 
			);

			add_submenu_page( 
				'mofect-onsite-search', 
				esc_html__('Keywords List','moss'), 
				esc_html__('Keywords','moss'), 
				'manage_options', 
				'edit.php?post_type=moss-keyword', 
				NULL 
			);

			add_submenu_page( 
				'mofect-onsite-search', 
				esc_html__('Keywords Categories','moss'), 
				esc_html__('Keywords Categories','moss'), 
				'manage_options', 
				'edit-tags.php?taxonomy=moss-keyword-cat&post_type=moss-keyword', 
				NULL 
			);

			add_submenu_page( 
				'mofect-onsite-search', 
				esc_html__('Settings','moss'), 
				esc_html__('Settings','moss'), 
				'manage_options', 
				'admin.php?page=mofect-onsite-search&tab=settings', 
				NULL 
			);
		}


		/**
	     * Admin Page UI
	     * @since 1.0.0
		 */
		public function menu_page_ui() {
			require_once MOSS_DIR.'inc/admin/index.php';
		}

		/**
	     * Admin Page Header
	     * @since 1.0.0
		 */
		public function admin_page_header(){
			echo '<div id="mofect-admin-header">
					 <h1><img src="'.MOSS_URI.'/inc/admin/assets/img/logo.png" /></h1>
					 '.$this->admin_page_header_menu().'
				  </div>';
		}

		/**
	     * Admin Page Header Menu
	     * @since 1.0.0
		 */
		public function admin_page_header_menu(){

		    $current_tab = isset($_GET['tab'])? $_GET['tab'] : '';

			$admin_menu = array(
				'dashboard' => array(
					'url' => esc_url(admin_url('admin.php?page=mofect-onsite-search&tab=dashboard')),
					'icon' => 'dashicons-before dashicons-dashboard',
					'name' => esc_html__('Dashboard','moss')
				),
				'settings' => array(
					'url' => esc_url(admin_url('admin.php?page=mofect-onsite-search&tab=settings')),
					'icon' => 'dashicons-before dashicons-admin-settings',
					'name' => esc_html__('Settings','moss')
				),
				'getting-started' => array(
					'url' => esc_url(admin_url('admin.php?page=mofect-onsite-search&tab=getting-started')),
					'icon' => 'dashicons-before dashicons-sos',
					'name' => esc_html__('Getting Started','moss')
				),
			);

			$html = '<div id="mofect-admin-menu">
					 <ul>';
					 foreach($admin_menu as $key => $val){
					 	 $active = ($current_tab == $key)?'active':'';
						 $html .='<li class="'.$active.'"><a href="'.$val['url'].'"><i class="'.$val['icon'].'"></i> '.$val['name'].'</a></li>';
					 }
			$html .='</ul> 
				  </div>';

		    return $html;
		}

		/**
	     * Admin Page Footer Page
	     * @since 1.0.0
		 */
		public function admin_page_footer(){
			require_once MOSS_DIR.'inc/admin/settings.php';
			require_once MOSS_DIR.'inc/admin/getting-started.php';
		}

		/**
	     * Define Options
	     * @since 1.0.0
		 */
		public function options(){
			$this->options = apply_filters('moss_register_options', array(
				array(
			        'id'   => 'search_form_section_title',
			        'title' => esc_html__('Global Settings', 'moss'),
			        'field' => 'section_start'
 			    ),

 			    array(
			        'id'   => 'moss_live_search',
			        'title' => esc_html__('Enable Live Search', 'moss'),
			        'desc'=> esc_html__('If you disable it, live search feature will be turned off on the global site. ', 'moss'),
			        'type' => 'int',
			       	'sanitize_callback' => 'esc_attr',
			        'default' => '0',
			        'field' => 'toggle'
 			    ),

 			    array(
			        'id'   => 'moss_tracking_keyword',
			        'title' => esc_html__('Enable Keyword Tracking', 'moss'),
			        'desc'=> esc_html__('If you enable the tracking feature, the keyword will be saved while the user is searching.', 'moss'),
			        'type' => 'int',
			       	'sanitize_callback' => 'esc_attr',
			        'default' => '0',
			        'field' => 'toggle'
 			    ),

 			    array(
			        'id'   => 'moss_show_featured',
			        'title' => esc_html__('Show Featured Content', 'moss'),
			        'desc'=> esc_html__('When there\'s nothing in the search result, show the featured content.', 'moss'),
			        'type' => 'int',
			       	'sanitize_callback' => 'esc_attr',
			        'default' => '0',
			        'field' => 'toggle'
 			    ),

 			    array(
			        'id'   => 'moss_featured_content',
			        'title' => esc_html__('Featured Content', 'moss'),
			        'desc'=> esc_html__('Please add the featured post id, multiple ids should be separated by English comma.', 'moss'),
			        'type' => 'int',
			        'placeholder' => 'id1,id2,id3',
			       	'sanitize_callback' => 'esc_attr',
			        'default' => '',
			        'field' => 'text'
 			    ),

 			    array(
			        'id'   => 'search_form_section_end',
			        'field' => 'section_end'
 			    ),
			) );

		}

		/**
		 * Register Settings
		 * @since 1.0.0
		 */
	    public function register_settings(){

			foreach($this->options as $option){

				   if($option['field'] == 'section_title'){
				   	  return;
				   }

				   register_setting('moss_option_group',$option['id'],array(
		           	 'type' 			 => isset($option['type']) ? $option['type'] : '',
		           	 'sanitize_callback' => isset($option['sanitize_callback']) ? $option['sanitize_callback'] : '',
		           	 'default' 			 => isset($option['default']) ? $option['default'] : ''
		           ));
		    }

	    }

	    /**
		 * Field Types
		 * @since 1.0.0
		 */
	    public function setting_fields(){

	    	$html = '';

			foreach($this->options as $option){

				$id   	 = isset($option['id']) ? $option['id'] : '';
				$field   = isset($option['field']) ? $option['field'] : 'text';
				$title   = isset($option['title']) ? $option['title'] : '';
				$desc    = isset($option['desc']) ? $option['desc'] : '';
				$placeholder    = isset($option['placeholder']) ? $option['placeholder'] : '';
				$choices = isset($option['choices']) ? $option['choices'] : '';
				$default = isset($option['default']) ? $option['default'] : '';
				$value   = (get_option($option['id']) !== '') ? get_option($option['id']) : $default;

				switch($field){

					case 'section_start':
					  $html .= '<div class="box"><h2 class="title">'.$title.'</h2>';
					  break;

					case 'section_end':
					  $html .= '</div>';
					  break;

					case 'text':
					  $html .= '<div class="mofect_field">
					  <label for="'.esc_html($id).'">'.esc_html($title).'</label>
					  <input type="text" name="'.esc_html($id).'" id="'.esc_html($id).'" placeholder="'.$placeholder.'" value="'.esc_html($value).'" />
					  <span class="desc">'.$desc.'</span>
					  </div>';
					  break;

					case 'number':
					  $html .= '<div class="mofect_field">
					  <label for="'.esc_html($id).'">'.esc_html($title).'</label>
					  <input type="number" name="'.esc_html($id).'" placeholder="'.$placeholder.'" id="'.esc_html($id).'" value="'.esc_html($value).'" />
					  <span class="desc">'.$desc.'</span>
					  </div>';
					  break;

					case 'email':
					  $html .= '<div class="mofect_field">
					  <label for="'.esc_html($id).'">'.esc_html($title).'</label>
					  <input type="email" name="'.esc_html($id).'" placeholder="'.$placeholder.'" id="'.esc_html($id).'" value="'.esc_html($value).'" />
					  <span class="desc">'.$desc.'</span>
					  </div>';
					  break;

					case 'select':
					  $options = '';
					  foreach($choices as $val => $label){
					  	$options .= '<option value="'.$val.'" '.$this->is_selected($value, $default, $val, 'selected').'>'.$label.'</option>';
					  }
					  $html .= '<div class="mofect_field">
					  <label for="'.esc_html($id).'">'.esc_html($title).'</label>
					  <select name="'.esc_html($id).'" id="'.esc_html($id).'">'.$options.'</select>
					  <span class="desc">'.$desc.'</span>
					  </div>';
					  break;

					case 'checkbox':
					  $html .= '<div class="mofect_field">
								    <label for="'.esc_html($id).'">'.esc_html($title).'</label>
									<label class="mofect-custom-checkbox">
								  	<input type="checkbox" name="'.$id.'" id="'.$id.'" value="1" '.$this->is_checked($value, $default).'>
								  	<span class="checkmark"></span>
								  	</label>
								  	<span class="desc">'.$desc.'</span>
								</div>';
					  break;

					case 'multi-checkbox':
					  $i = 1;
					  $html .= '<div class="mofect_field">
					  			  <label for="'.esc_html($id).'">'.esc_html($title).'</label>';
					  foreach($choices as $val => $label){
					  	$html .= '<label class="mofect-custom-checkbox">
					  				<input type="checkbox" name="'.$id.'['.$i.']" id="'.$id.'['.$i.']" value="'.$default.'" '.$this->is_selected($value, $default, $val, 'checked').'><span>'.$label.'</span>
					  				<span class="checkmark"></span>
					  			  </label>';
					    $i++;
					  }
					  $html .= '<span class="desc">'.$desc.'</span></div>';
					  break;

					case 'radio':
					  $html .= '<div class="mofect_field">
					  			  <label for="'.esc_html($id).'">'.esc_html($title).'</label>';
					  foreach($choices as $val => $label){
					  	$html .= '<label class="mofect-custom-radio">
					  				<input type="radio" name="'.$id.'" value="'.$val.'" '.$this->is_selected($value, $default, $val,'checked').'><span>'.$label.'</span>
					  				<span class="checkmark"></span>
					  				</label>';
					  }
					  $html .= '<span class="desc">'.$desc.'</span></div>';
					  break;

					case 'radio-image':
					  $html .= '<div class="mofect_field">
					  			  <label for="'.esc_html($id).'">'.esc_html($title).'</label>';
					  foreach($choices as $val => $label){
					  	$html .= '<label class="mofect-custom-radio-image">
					  				<input type="radio" name="'.$id.'" value="'.$val.'" '.$this->is_selected($value, $default, $val, 'checked').'>
					  				<img src="'.$label['image'].'" />';

					  	    if(isset($label['title']) && '' !== $label['title']){
					  		   $html .= '<span class="wx-custom-radio-image-title">'.$label['title'].'</span>';
					  	    }
					  	    $html .= '</label>';
					  }
					  $html .= '<span class="desc">'.$desc.'</span></div>';
					  break;

					case 'toggle':
					  $html .= '<div class="mofect_field">
					  			  <label for="'.esc_html($id).'">'.esc_html($title).'</label>
					 		      <label class="switch">
								  <input type="checkbox" name="'.$id.'" id="'.$id.'" value="1" '.$this->is_checked($value).'>
								  <span class="slider round"></span>
								  </label>
								  <span class="desc">'.$desc.'</span>
							   </div>';
					  break;
				}
		    }

			echo $html;
	    }

	    /**
		 * Check if selected for select, radio and checkbox field.
		 * @return selected
		 */
		private function is_selected($current_value, $default_value, $choice_value, $output){
			$selected = '';
			
			if(isset($current_value) && $current_value == $choice_value){
				$selected = $output;
			}elseif(isset($default_value) && $default_value == $choice_value){
				$selected  = $output;
			}

			return $selected;
		}

		 /**
		 * Check if checked for toggle and checkbox field.
		 * @return selected
		 */
		private function is_checked($current_value){
			$selected = '';

			if($current_value == '1'){
				$selected = 'checked';
			}

			return $selected;
		}


		
	}

	return new MOSS_Admin();
}
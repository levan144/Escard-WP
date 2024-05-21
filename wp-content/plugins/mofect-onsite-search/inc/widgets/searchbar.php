<?php
/**
 * Search Bar Widget
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

class MOSS_SearchBar_Widget extends WP_Widget {
	
	
	/**
	 * Register widget
	**/
	public function __construct() {
		
		parent::__construct(
	 		'mofect_searchbar', // Base ID
			__( 'On-site Search', 'moss' ), // Name
			array( 'description' => __( 'Advanced search bar for On-Site Search', 'moss' ), ) // Args
		);
		
	}

	
	/**
	 * Front-end display of widget
	**/
	public function widget( $args, $instance ) {
				
		extract( $args );

		$title = apply_filters('moss_searchbar_widget_title', isset( $instance['title'] ) ? esc_attr( $instance['title'] ) :'' );
		$label 			= isset( $instance['label'] ) ? esc_attr( $instance['label'] ) :'';
		$placeholder    = isset( $instance['placeholder'] ) ? $instance['placeholder'] : '';
		$posts_per_page = isset( $instance['posts_per_page'] ) ? $instance['posts_per_page'] : '12';
		$category       = isset( $instance['category'] ) ? $instance['category'] : '';
		$post_type      = isset( $instance['post_type'] ) ? $instance['post_type'] : 'post';
		$orderby        = isset( $instance['order'] ) ? $instance['order'] : 'date';
		$order          = isset( $instance['orderby'] ) ? $instance['orderby'] : 'desc';
		$extra_class    = isset( $instance['extra_class'] ) ? $instance['extra_class'] : '';
		
		echo $before_widget;
			
		if ( $title ) echo $before_title . $title . $after_title;

		$args = array(
                  'label'   	  =>  $label,
                  'placeholder'   =>  $placeholder,
                  'layout'		  =>  'list',
                  'post_type'	  =>  $post_type,
                  'posts_per_page'=>  $posts_per_page,
                  'category'      =>  $category,
                  'orderby'       =>  $orderby,
                  'order'		  =>  $order,
                  'extra_class'   =>  $extra_class
                );

        Mofect_OnSite_Search::load_template('mofect_searchbar',$args);
		echo $after_widget;
		
	}
	
	
	/**
	 * Sanitize widget form values as they are saved
	**/
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();

		/* Strip tags to remove HTML. For text inputs and textarea. */
		$instance['title']			 = strip_tags( $new_instance['title'] );
		$instance['label']			 = strip_tags( $new_instance['label'] );
		$instance['placeholder'] 	 = strip_tags( $new_instance['placeholder'] );
		$instance['post_type']  	 = strip_tags( $new_instance['post_type'] );
		$instance['posts_per_page']  = strip_tags( $new_instance['posts_per_page'] );
		$instance['category'] 		 = strip_tags( $new_instance['category'] );
		$instance['orderby'] 		 = strip_tags( $new_instance['orderby'] );
		$instance['order'] 			 = strip_tags( $new_instance['order'] );
		$instance['extra_class'] 	 = strip_tags( $new_instance['extra_class'] );
		
		return $instance;
		
	}
	
	
	/**
	 * Back-end widget form
	**/
	public function form( $instance ) {
		
		/* Default widget settings. */
		$defaults = array(
			'title'          => 'Search',
			'label'          => 'Search',
			'placeholder'    => 'Keyword',
			'posts_per_page' => '',
			'category'		 => '',
			'post_type'		 => '',
			'orderby'		 => '',
			'order'		     => '',
			'extra_class'    => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'moss'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'label' ); ?>"><?php _e('Label:', 'moss'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>" value="<?php echo $instance['label']; ?>" class="widefat" /> 
			<span class="desc"><?php esc_html_e('If you leave it empty, the label text will be hidden.', 'moss');?></span>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'placeholder' ); ?>"><?php _e('Placeholder:', 'moss'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" value="<?php echo $instance['placeholder']; ?>" class="widefat" />
			<span class="desc"><?php esc_html_e('If you leave it empty, the placeholder text will be hidden.', 'moss');?></span>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _e('Number of Result Per Page:', 'moss'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php echo $instance['posts_per_page']; ?>" class="widefat" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e('Category', 'moss'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>" value="<?php echo $instance['category']; ?>" class="widefat" />
			<span class="desc"><?php esc_html_e('If you want to limit the search result within the specific categories, please add the category slugs here, multiple categories should be separated by English comma.', 'moss');?></span>
		</p>

		<p>
            <label for="<?php echo esc_attr($this->get_field_id('post_type')); ?>"><?php _e('Post Type:','moss'); ?></label>
            <select name="<?php echo $this->get_field_name('post_type'); ?>" class="widefat" id="<?php echo $this->get_field_id('post_type'); ?>">
            	<?php $post_type = $instance['post_type'];?>
                <option value="" <?php if($post_type == '')echo 'selected="selected"';?>><?php esc_html_e('All Post Types','moss');?></option>
                <option value="post" <?php if($post_type == 'post')echo 'selected="selected"';?>><?php esc_html_e('Post', 'moss'); ?></option>
                <option value="product" <?php if($post_type == 'product')echo 'selected="selected"';?>><?php esc_html_e('Product', 'moss'); ?></option>
                <?php do_action('moss_search_widget_post_type', $post_type);?>
            </select>
        </p>

        <p>
			<input type="hidden" id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" value="<?php echo $instance['orderby']; ?>" class="widefat" />
		</p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('order')); ?>"><?php _e('Order:','moss'); ?></label>
            <select name="<?php echo $this->get_field_name('order'); ?>" class="widefat" id="<?php echo $this->get_field_id('order'); ?>">
                <option value="desc" <?php if($instance['order'] == 'desc')echo'selected="selected"';?>><?php echo esc_html_e('From Newest to Oldest By Publish Time','moss'); ?></option>
                <option value="asc" <?php if($instance['order'] == 'asc')echo'selected="selected"';?>><?php echo esc_html_e('From Oldest to Newest By Publish Time','moss'); ?></option>
 
            </select>
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'extra_class' ); ?>"><?php _e('Extra Class:', 'moss'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'extra_class' ); ?>" name="<?php echo $this->get_field_name( 'extra_class' ); ?>" value="<?php echo $instance['extra_class']; ?>" class="widefat" />
			<span class="desc"><?php esc_html_e('You can add extra CSS Class selector here', 'moss');?></span>
		</p>
		
	<?php
	}

}
register_widget( 'MOSS_SearchBar_Widget' );
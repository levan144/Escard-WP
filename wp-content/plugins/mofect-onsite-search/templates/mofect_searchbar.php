<?php
/**
 * Search Bar Template
 * @package  Mofect On-Site Search
 * @since 1.0.0
 */

/* Receive Data */
$label   		 =   $mofect_data->label;
$placeholder     =   $mofect_data->placeholder;
$layout   	     =   $mofect_data->layout;
$post_type   	 =   $mofect_data->post_type;
$posts_per_page  =   $mofect_data->posts_per_page;
$orderby 		 =   $mofect_data->orderby;
$order 			 =   $mofect_data->order;
$category 		 =   $mofect_data->category;
$extra_class 	 =   $mofect_data->extra_class;

/* HTML Makeup below */
?>

<div class="moss-searchbar <?php echo $extra_class;?>">
	<form action="<?php echo home_url('/');?>" method="get" name="moss-search-form">
		
		<?php if( isset($label) && $label !== '' ):?>
		  <label for="moss-search-keyword"><?php echo esc_attr($label);?></label>
	    <?php endif;?>

	    <input type="search" name="s" class="moss-search-keyword" value="" placeholder="<?php echo esc_attr($placeholder);?>" /><a href="javascript:void(0);" class="close-live-search">&times;</a>

		<?php if( isset($post_type) && $post_type !== '' ):?>
		<input type="hidden" name="post_type" value="<?php echo esc_html($post_type);?>" />
		<?php endif;?>
	
		<?php if( isset($posts_per_page) && $posts_per_page !=='' ):?>
	    <input type="hidden" name="posts_per_page" value="<?php echo esc_html($posts_per_page);?>" />
	    <?php endif;?>

		<?php if( isset($orderby) && $orderby !=='' ):?>
	    <input type="hidden" name="orderby" value="<?php echo esc_html($orderby);?>" />
		<?php endif;?>
	    
		<?php if( isset($order) && $order =='' ):?>
	    <input type="hidden" name="order" value="<?php echo esc_html($order);?>" />
	    <?php endif;?>

	    <?php if( isset($category) && $category !=='' ):?>
	    <input type="hidden" name="category" value="<?php echo esc_html($category);?>" />
		<?php endif;?>

		<button type="submit"><?php echo esc_html__('Search','moss');?></button>
	</form>

	<div class="moss-live-result moss-<?php echo esc_html($layout);?>">
		<div class="moss-result">
			
			<?php do_action('moss_before_results');?>
			
			<ul class="moss-result-wrapper cols-4"></ul>
			
			<?php do_action('moss_after_results');?>
			
			<a class="read-more"><?php esc_html_e('View all related results', 'moss');?> &rarr;</a>
		</div>
	</div>
</div>
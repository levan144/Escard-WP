<?php
/**
 * Getting Started Page
 * @package   Mofect On-Site Search
 * @since 1.0.0
 */

if(!isset($_GET['tab']) || $_GET['tab']!=='getting-started'){
	return;
}
?>
<section id="getting-started" class="mofect-admin-page">
   <div class="box">
	   <h2 class="title"><?php esc_html_e('Getting Started','moss');?></h2>
	   <div>
	   	 <p><?php esc_html_e('Thanks for purchased Mofect On-Site Search plugin. There are three ways to use Mofect Search Bar on your site.','moss');?></p>
	   	 <ol>
	   	 	<li><?php _e('Use Shortcode: <code>[mofect_searchbar]</code> You can embed the shortcode to anywhere you want. The following parameters are available to use.','moss');?>
	   	 		<ul>
	   	 			<li><strong>label = 'Search'</strong></li>
	   	 			<li><strong>placeholder = 'Enter Keyword'</strong></li>
	   	 			<li><strong>layout = 'list' or 'grid'</strong></li>
	   	 			<li><strong>post_type = 'post'</strong> <?php esc_html_e('All post types are supported by default, you can specific one for the search result such as "product"','moss');?>.</li>
	   	 			<li><strong>posts_per_page = '12'</strong>. <?php esc_html_e('The number of result will be appeared in the result list.','moss');?></li>
	   	 			<li><strong>orderby = 'date' or 'rand'</strong></li>
	   	 			<li><strong>order = 'desc' or 'asc'</strong>.</li>
	   	 			<li><strong>category = 'cat_slug1,cat_slug2'</strong>. <?php esc_html_e('You can limit the search range within the specific categories, just add category slugs here, multiple category slugs should be separated by English comma.','moss');?> </li>
	   	 			<li><strong>extra_class = 'your_custom_search'</strong>. <?php esc_html_e('The safe way to customize the search bar style is set the extra CSS selector with this parametor, then you can write your custom CSS in your theme as .your_custom_search{.....}','moss');?> </li>
	   	 	   </ul>
	   	 	</li>
	   	 	<li><?php esc_attr_e('Use PHP function: <?php moss_searchbar($args);?>. If you want to replace the search form in your theme file, you can use this method and the following arguments you can use in this function and the usage as same as the shortcode ','moss');?>
	   	 		<pre>
	   	 			$args = array(
				 	  'label'          => esc_html__('Search Product','moss'),
				 	  'placeholder'    => esc_html__('Keyword','moss'),
				 	  'post_type'      => 'post',
				 	  'posts_per_page' => '12',
				 	  'orderby'        => 'date',
				 	  'order'          => 'desc',
				 	  'category'	   => '',
				 	  'extra_class'    => '',
				 	  'layout'	   => 'list'
				        );
	   	 		</pre>
	   	 	</li>
	   	 	<li><?php esc_html_e('Mofect Search Bar is integrated into the popular page builder such as WPBakery Page Builder, KingComposer, Elementor and Beaver Builder. You can find Mofect Search element in these page builder panel, just drag it and drop to the page.','moss');?></li>
	   	 	<li><?php esc_html_e('If you want to add the search bar as a widget to your page, just go to Appearance > Widgets, you will find On-Site Search Widget is available to use.','moss');?></li>
	   	 </ol>
	   </div>
	</div>
</section>
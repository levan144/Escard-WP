<?php
/**
 * All Post Type Loop template
 * @package  Mofect On-Site Search
 * @since 1.0.0
 */
	
if(get_post_type(get_the_ID()) == 'product'){
	moss_load_template('mofect_search_product');
}elseif(get_post_type(get_the_ID()) == 'download'){
	moss_load_template('mofect_search_download');
}else{
	moss_load_template('mofect_search_post');
}
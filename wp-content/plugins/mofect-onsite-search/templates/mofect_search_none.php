<?php
/**
 * Not found template
 * @package  Mofect On-Site Search
 * @since 1.0.0
 */
?>

<li class="no-product">
   <?php if(null !== get_option('moss_show_featured') && get_option('moss_show_featured') == 1):?>
     <p><?php printf(__('No "%s" related things were found matching your selection, but we recommend the following content you may like.','moss'), $mofect_data->keyword);?></p>
   <?php else:?>
     <p><?php esc_html_e('No related things were found matching your selection. ','moss');?></p>
   <?php endif;?>
</li>
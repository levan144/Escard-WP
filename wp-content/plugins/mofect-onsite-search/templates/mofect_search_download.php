<?php
/**
 * Downloads Loop template
 * @package  Mofect On-Site Search
 * @since 1.0.0
 */

global $product;
?>
<li class="result">

	<?php if(has_post_thumbnail()):?>
	<a href="<?php echo esc_url(get_permalink());?>" class="moss-result-thumbnail">
        <?php echo get_the_post_thumbnail(get_the_ID(),'large');?>
	</a>
	<?php endif;?>

	<a href="<?php echo esc_url(get_permalink());?>" class="moss-result-title">
	<?php the_title();?>
	</a>

    <span class="price"><?php edd_price(get_the_ID(),true);?></span>
       
    <?php endif;?>
</li>
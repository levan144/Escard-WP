<?php
/**
 * Products Loop template
 * @package  Mofect On-Site Search
 * @since 1.0.0
 */

global $product;
?>
<li class="result">

	<?php if(has_post_thumbnail()):?>
	<a href="<?php echo esc_url(get_permalink());?>" class="moss-result-thumbnail">
		
		<?php if($product->is_featured()): ?>
             <span class="featured"><?php esc_html_e('Featured!','tmvc');?></span>
        <?php endif;?>

        <?php echo get_the_post_thumbnail(get_the_ID(),'large');?>
	</a>
	<?php endif;?>

	<a href="<?php echo esc_url(get_permalink());?>" class="moss-result-title">

	<?php if($product->is_on_sale()):?>
       <span class="onsale"><?php esc_html_e('Sale!','moss')?></span>
	<?php endif;?>
	
	<?php the_title();?>
	</a>

	<?php if($product->get_sale_price()<>'' && $product->get_sale_price()<>'0'): ?>

       <span class="sale-price"><?php echo wc_price($product->get_sale_price());?></span>
       <del class="regular-price"><?php echo wc_price($product->get_regular_price())?></del>

    <?php else: ?>

       <span class="price"><?php wc_price($product->get_price());?></span>
       
    <?php endif;?>
</li>
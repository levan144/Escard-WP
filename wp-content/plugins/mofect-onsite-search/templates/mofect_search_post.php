<?php
/**
 * Posts Loop template
 * @package  Mofect On-Site Search
 * @since 1.0.0
 */
?>
<li class="result">

	<?php if(has_post_thumbnail()):?>
	<a href="<?php echo esc_url(get_permalink());?>" class="moss-result-thumbnail">
        <?php echo get_the_post_thumbnail(get_the_ID(),'large');?>
	</a>
	<?php endif;?>

	<a href="<?php echo esc_url(get_permalink());?>" class="moss-result-title"><?php the_title();?></a>

	<?php
	 foreach(get_the_category() as $category){
	    echo '<a class="moss-result-cat" href="'.get_category_link($category->cat_ID).'">'.$category->cat_name.'</a>';
	 }
	?>
</li>
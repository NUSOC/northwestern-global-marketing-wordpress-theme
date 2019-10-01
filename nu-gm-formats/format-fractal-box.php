<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" class="feature-box fractal-box" itemprop="itemListElement" itemscope itemid="#post-<?php the_ID(); ?>" itemtype="<?php echo nu_gm_schema(); ?>" itemref="footer-publisher-info">
	<div class="feature-copy">
		<h4 itemprop="name headline" id="post-<?php the_ID(); ?>-title"><?php the_title(); ?></h4>
    <?php if(in_array(get_post_type(), array('post', 'nu_gm_news'))): ?><p class="post-date">Posted <?php echo get_the_time(get_option('date_format')); ?></p><?php endif; ?>
		<?php echo gm_custom_excerpt(25); ?>
	</div>
	<a class="button" href="<?php the_permalink() ?>" itemprop="url mainEntityOfPage" aria-label="<?php echo str_replace('"', '', get_the_title()); ?>">Read More</a>
  <?php get_template_part( 'meta-author' ); ?>
  <meta itemprop="datePublished" content="<?php echo get_the_time('Y-m-d'); ?>" hidden />
  <meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
	<?php echo nu_gm_get_the_loop_index(); ?>
</article>

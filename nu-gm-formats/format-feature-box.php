<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" class="feature-box" itemprop="itemListElement" itemscope itemid="#post-<?php the_ID(); ?>" itemtype="<?php echo nu_gm_schema(); ?>" itemref="footer-publisher-info">
  <div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
    <a class="feature-box-image-link" tabindex="-1" href="<?php the_permalink() ?>">
      <?php if(is_fullwidth()): ?>
        <meta itemprop="width" content="360" hidden />
        <meta itemprop="height" content="200" hidden />
        <?php if( has_post_thumbnail() ): ?>
          <?php the_post_thumbnail('feature-box-3', array('itemprop' => 'url')); ?>
        <?php else: ?>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-feature-box-3.jpg" width="360" height="200" alt="<?php the_title(); ?>" itemprop="url" />
        <?php endif; ?>
      <?php else: ?>
        <meta itemprop="width" content="550" hidden />
        <meta itemprop="height" content="310" hidden />
        <?php if( has_post_thumbnail() ): ?>
          <?php the_post_thumbnail('feature-box-2', array('itemprop' => 'url')); ?>
        <?php else: ?>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-feature-box-2.jpg" width="550" height="310" alt="<?php the_title(); ?>" itemprop="url" />
        <?php endif; ?>
      <?php endif; ?>
    </a>
  </div>
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
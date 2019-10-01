<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" class="photo-feature" itemprop="itemListElement" itemscope itemid="#post-<?php the_ID(); ?>" itemtype="<?php echo nu_gm_schema(); ?>" itemref="footer-publisher-info">
	<a href="<?php the_permalink() ?>">
    <div class="front">
      <div class="img-wrap" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
        <?php if(is_fullwidth()): ?>
          <meta itemprop="width" content="480" hidden />
          <meta itemprop="height" content="350" hidden />
          <?php if( has_post_thumbnail() ): ?>
            <?php the_post_thumbnail('photo-feature-3', array('itemprop' => 'url')); ?>
          <?php else: ?>
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-photo-feature-3.jpg" width="480" height="350" alt="<?php the_title(); ?>" itemprop="url" />
          <?php endif; ?>
        <?php else: ?>
          <meta itemprop="width" content="720" hidden />
          <meta itemprop="height" content="350" hidden />
          <?php if( has_post_thumbnail() ): ?>
            <?php the_post_thumbnail('photo-feature-2', array('itemprop' => 'url')); ?>
          <?php else: ?>
            <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-photo-feature-2.jpg" width="720" height="350" alt="<?php the_title(); ?>" itemprop="url" />
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="text-over-image">
        <h4 id="post-<?php the_ID(); ?>-title" itemprop="name headline"><?php the_title(); ?></h4>
        <p class="link">Read more</p>
      </div>
    </div>
    <div class="back">
      <div class="back-text">
        <h4><?php the_title(); ?></h4>
        <?php if(get_post_type() == 'post'): ?>
          <p class="tags"><?php echo strip_tags(get_the_term_list( get_the_ID(), 'category', __( 'Categories:', 'nu_gm' ) . ' ', ', ' )) ?></p>
        <?php else: ?>
          <?php if( $subtitle = get_post_meta(get_the_id(), 'nu_gm_hero_banner_subtitle', true) ): ?><p itemprop="alternativeHeadline"><?php echo $subtitle; ?></p><?php endif; ?>
        <?php endif; ?>
        <p class="link">Read more</p>
      </div>
    </div>
    <link itemprop="url mainEntityOfPage" href="<?php the_permalink() ?>" />
	</a>
  <?php get_template_part( 'meta-author' ); ?>
  <meta itemprop="datePublished" content="<?php echo get_the_time('Y-m-d'); ?>" hidden />
  <meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
  <?php echo nu_gm_get_the_loop_index(); ?>
</article>
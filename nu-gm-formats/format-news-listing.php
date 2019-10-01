<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" class="news" itemscope itemid="#post-<?php the_ID(); ?>" itemprop="itemListElement" itemtype="<?php echo nu_gm_schema(); ?>" itemref="footer-publisher-info">
  <div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
    <?php if( has_post_thumbnail() ): ?>
      <?php the_post_thumbnail('news-listing', array('itemprop' => 'url')); ?>
    <?php else: ?>
      <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-news-listing.jpg" width="170" height="170" alt="<?php the_title(); ?>" itemprop="url" />
    <?php endif; ?>
    <meta itemprop="width" content="170" hidden />
    <meta itemprop="height" content="170" hidden />
  </div>
  <meta itemprop="headline name" content="<?php the_title(); ?>" />
	<h4 class="news-title" id="post-<?php the_ID(); ?>-title"><a href="<?php the_permalink(); ?>" itemprop="url mainEntityOfPage"><?php the_title(); ?></a></h4>
  <div class="news-date"><time class="updated entry-time" datetime="<?php echo get_the_time('Y-m-d'); ?>" itemprop="datePublished"><?php echo get_the_time(get_option('date_format')); ?></time></div>
	<div class="news-description" itemprop="description"><?php echo gm_custom_excerpt(140); ?></div>              
  <link itemprop="url mainEntityOfPage" href="<?php the_permalink() ?>" />
  <span itemprop="author" itemscope itemtype="http://schema.org/Person" hidden style="display:none;">
    <meta itemprop="url mainEntityOfPage" content="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" hidden />
    <?php if($display_name = get_the_author()): ?><meta itemprop="name" content="<?php echo $display_name; ?>" hidden /><?php endif; ?>
    <?php if($first_name == get_the_author_meta( 'first_name' )): ?><meta itemprop="givenName" content="<?php echo $first_name; ?>" hidden /><?php endif; ?>
    <?php if($last_name == get_the_author_meta( 'last_name' )): ?><meta itemprop="familyName" content="<?php echo $last_name; ?>" hidden /><?php endif; ?>
  </span>
  <meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
  <?php echo nu_gm_get_the_loop_index(); ?>
</article>
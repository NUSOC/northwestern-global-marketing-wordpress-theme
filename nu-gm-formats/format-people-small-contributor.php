<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" class="people-card people-small people-small-project-contributor" itemprop="author contributor" itemscope itemtype="<?php echo nu_gm_schema(); ?>">
  <?php $show_image = get_theme_mod('nu_gm_directory_item_list_show_img_setting', true); ?>
  <div class="people-wrap <?php if(!$show_image) { echo 'no-image'; } ?>">
    <div class="people-image">
      <?php if( $show_image ): ?>
        <?php if( has_post_thumbnail() ): ?>
          <?php the_post_thumbnail('people-small-listing', array('itemprop' => 'image')); ?>
        <?php else: ?>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-people-small.gif" width="170" height="170" alt="<?php the_title(); ?>" itemprop="image" />
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <div class="people-content">
      <?php
        $last_name = get_post_meta(get_the_ID(), 'nu_gm_last_name', true);
        $first_name = get_post_meta(get_the_ID(), 'nu_gm_first_name', true);
      ?>
      <meta itemprop="name" content="<?php echo $first_name . ' ' . $last_name; ?>" hidden />
      <h4 id="post-<?php the_ID(); ?>-title"><span itemprop="givenName"><?php echo $first_name; ?></span> <span itemprop="familyName"><?php echo $last_name; ?></span></h4>
      <?php if(!empty($contributor_pair['nu_gm_project_team_role'])): ?><p class="job-title"><?php echo $contributor_pair['nu_gm_project_team_role']; ?></p><?php endif; ?>
      <meta itemprop="url mainEntityOfPage" content="<?php the_permalink(); ?>" hidden />
    </div>
  </div>
</article>
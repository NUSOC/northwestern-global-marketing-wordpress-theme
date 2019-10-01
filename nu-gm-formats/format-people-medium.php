<?php global $wp_query; ?>
<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" class="people-card people-medium" itemscope <?php if($wp_query->is_post_type_archive('nu_gm_directory_item')): ?>itemprop="itemListElement" <?php endif; ?> itemtype="<?php echo nu_gm_schema(); ?>">
  <?php $show_image = get_theme_mod('nu_gm_directory_item_list_show_img_setting', true); ?>
  <div class="people-wrap <?php if(!$show_image) { echo 'no-image'; } ?>">
    <div class="people-image">
      <?php if( $show_image ): ?>
        <?php if( has_post_thumbnail() ): ?>
          <?php the_post_thumbnail('people-medium', array('itemprop' => 'image')); ?>
        <?php else: ?>
          <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-people-medium.png" width="265" height="265" alt="<?php the_title(); ?>" itemprop="image" />
        <?php endif; ?>
      <?php endif; ?>
    </div>
    <div class="people-content">
      <?php
        $last_name = get_post_meta(get_the_ID(), 'nu_gm_last_name', true);
        $first_name = get_post_meta(get_the_ID(), 'nu_gm_first_name', true);
      ?>
      <meta itemprop="name" content="<?php echo $first_name . ' ' . $last_name; ?>" hidden />
      <h4 id="post-<?php the_ID(); ?>-title"><span itemprop="familyName"><?php echo $last_name; ?></span>, <span itemprop="givenName"><?php echo $first_name; ?></span></h4>
      <?php if($professional_title = get_post_meta(get_the_ID(), 'nu_gm_professional_title', true)): ?><p itemprop="jobTitle"><?php echo $professional_title; ?></p><?php endif; ?>
      <?php if($phone_number = get_post_meta(get_the_ID(), 'nu_gm_phone_number', true)): ?><p>Phone number: <a class="tel-link" href="tel:<?php echo preg_replace('/[^0-9]/', '', $phone_number); ?>" title="Call <?php echo $first_name.' '.$last_name; ?>" itemprop="telephone"><?php echo $phone_number; ?></a><br/><?php endif; ?>
      <?php if($email = get_post_meta(get_the_ID(), 'nu_gm_email', true)): ?><a href="mailto:<?php echo $email; ?>" itemprop="email"><?php echo $email; ?></a></p><?php endif; ?>
      <meta itemprop="url mainEntityOfPage" content="<?php the_permalink(); ?>" hidden />
    </div>
  </div>
  <?php if(is_single()): ?><div itemprop="description"><?php the_content(); ?></div><?php endif; ?>
</article>
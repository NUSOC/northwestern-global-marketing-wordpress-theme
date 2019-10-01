    <?php $homepage_hero_banner_img_url = get_theme_mod('homepage_hero_banner_img_setting', get_template_directory_uri().'/library/images/default-hero.jpg'); ?>
    <?php if(!empty($homepage_hero_banner_img_url) && homepage_hero_banner_visible() &&
             ((nu_gm_get_current_page_id() !== get_option('page_for_posts', '-') && is_home() && !is_paged()) ||
              (get_option('show_on_front', 'posts') == 'page' && get_page_template_slug(get_option('page_on_front', 0)) == 'page-home.php' && is_front_page()))): ?>

        <section class="hero contain-1440" aria-label="Hero Banner for <?php echo get_bloginfo('name'); ?>">

          <div id="hero-image-link" itemprop="image" itemscope itemtype="http://schema.org/ImageObject" itemref="footer-publisher-info" hidden>
            <meta itemprop="url" content="<?php echo $homepage_hero_banner_img_url; ?>" />
            <meta itemprop="width" content="1440" />
            <meta itemprop="height" content="420" />
            <meta itemprop="representativeOfPage" content="1" />
            <meta itemprop="name" content="<?php echo get_theme_mod('homepage_hero_banner_title_setting', get_bloginfo('name')); ?> Hero Image" />
          </div>

          <div class="hero-image" style="background: #4e2a84 url('<?php echo $homepage_hero_banner_img_url; ?>') no-repeat center / cover;">

            <div class="contain-1120">

              <h2><?php echo get_theme_mod('homepage_hero_banner_title_setting', get_bloginfo('name')); ?></h2>

              <p><?php echo get_theme_mod('homepage_hero_banner_subhead_setting', get_bloginfo('description')); ?></p>

              <?php $homepage_hero_banner_btn_url = get_theme_mod('homepage_hero_banner_link_btn_url_setting', home_url()); ?>
              <?php if(!empty($homepage_hero_banner_btn_url)): ?>
                <a class="button" href="<?php echo $homepage_hero_banner_btn_url; ?>"><?php echo get_theme_mod('homepage_hero_banner_link_btn_label_setting', 'More'); ?></a>
              <?php endif; ?>
            </div>

          </div>

        </section>

    <?php elseif( is_singular() && has_post_thumbnail() ): ?>

      <div id="hero-image-link" class="nu-gm-microdata" itemprop="image" itemscope itemid="<?php echo home_url(); ?>#attachment-<?php echo get_post_thumbnail_id( $wp_query->post->ID ); ?>" itemtype="http://schema.org/ImageObject" itemref="footer-publisher-info" hidden style="display:none;">
        <meta itemprop="url mainEntityOfPage" content="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $wp_query->post->ID ), 'hero-standard')[0]; ?>" />
        <meta itemprop="width" content="1440" />
        <meta itemprop="height" content="420" />
        <meta itemprop="representativeOfPage" content="1" />
        <meta itemprop="name" content="<?php the_title(); ?> Hero Image" />
      </div>

      <?php if(get_post_meta($wp_query->post->ID, 'nu_gm_hide_hero_banner_image', true) != true): ?>

        <section class="hero contain-1440" aria-label="Hero Banner for <?php the_title(); ?>">

          <div class="hero-image" style="background: #4e2a84 url('<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( $wp_query->post->ID ), 'hero-standard')[0]; ?>') no-repeat center / cover;">

            <div class="contain-1120">

              <?php if( $subtitle = get_post_meta(get_the_id(), 'nu_gm_hero_banner_subtitle', true) ): ?><p itemprop="alternativeHeadline"><?php echo $subtitle; ?></p><?php endif; ?>

              <?php $page_hero_banner_btn = rwmb_meta('nu_gm_hero_banner_btn'); ?>
              <?php if(!empty($page_hero_banner_btn)): ?>
                <ul class="center-list">
                  <?php foreach($page_hero_banner_btn as $btn): ?>
                    <?php if(!empty($btn['nu_gm_hero_banner_btn_url']) && !empty($btn['nu_gm_hero_banner_btn_text'])): ?>
                      <li><a class="button" href="<?php echo $btn['nu_gm_hero_banner_btn_url']; ?>"><?php echo $btn['nu_gm_hero_banner_btn_text']; ?></a></li>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
              
            </div>

          </div>

        </section>

      <?php endif; ?>

    <?php elseif( $dynamic_banner = nu_gm_get_dynamic_banner() ): ?>
      <?php if( !empty($dynamic_banner['image']) ): ?>

        <div id="hero-image-link" class="nu-gm-microdata" itemprop="image" itemscope itemtype="http://schema.org/ImageObject" itemref="footer-publisher-info" hidden style="display:none;">
          <meta itemprop="url" content="<?php echo $dynamic_banner['image']; ?>" />
          <meta itemprop="width" content="1440" />
          <meta itemprop="height" content="420" />
          <meta itemprop="representativeOfPage" content="1" />
          <meta itemprop="name" content="<?php the_archive_title(); ?> Hero Image" />
        </div>
        
        <?php
          $aria_label = "Hero Banner";
          if(!empty($dynamic_banner['title'])) {
            $aria_label .= " for " . $dynamic_banner['title'];
          } else if (!empty($dynamic_banner['subtitle'])) {
            $aria_label .= " for " . $dynamic_banner['subtitle'];
          }
        ?> 
        
        <section class="hero contain-1440" aria-label="<?php echo $aria_label; ?>">

          <div class="hero-image" style="background: #4e2a84 url('<?php echo $dynamic_banner['image']; ?>') no-repeat center / cover;">

            <div class="contain-1120">

              <?php if(!empty($dynamic_banner['title'])): ?><h2><?php echo $dynamic_banner['title']; ?></h2><?php endif; ?>

              <?php if(!empty($dynamic_banner['subtitle'])): ?><p><?php echo $dynamic_banner['subtitle']; ?></p><?php endif; ?>

            </div>

          </div>

        </section>

      <?php endif; ?>

    <?php else: ?>

      <script type="text/javascript">
        document.querySelector('body').classList.add('nu-gm-no-hero-banner');
      </script>

    <?php endif; ?>

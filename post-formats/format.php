
              <?php
                /*
                 * This is the default post format.
                 *
                 * So basically this is a regular post. if you don't want to use post formats,
                 * you can just copy ths stuff in here and replace the post format thing in
                 * single.php.
                 *
                 * The other formats are SUPER basic so you can style them as you like.
                 *
                 * Again, If you want to remove post formats, just delete the post-formats
                 * folder and replace the function below with the contents of the "format.php" file.
                */
              ?>
              
              <?php get_sidebar(); ?>
              
              <main id="main-content" tabindex="-1" class="content m-all t-2of3 d-5of7 cf">

                <?php nu_gm_breadcrumbs(); ?>

                <article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" <?php post_class('cf'); ?> itemscope itemid="<?php the_permalink(); ?>" itemtype="<?php echo nu_gm_schema(); ?>" itemref="hero-image-link footer-publisher-info">

                  <div class="article-header entry-header">

                    <h2 class="entry-title single-title" itemprop="headline name" rel="bookmark" id="post-<?php the_ID(); ?>-title"><?php the_title(); ?></h2>

                    <p class="byline entry-meta vcard">

                      <?php printf( __( 'Posted', 'nu_gm' ).' %1$s %2$s',
                         /* the time the post was published */
                         '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                         /* the author of the post */
                         '<span class="by">'.__( 'by', 'nu_gm').'</span> <span class="entry-author author"><a class="url" href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '"><span class="fn">'. get_the_author() . '</span></a></span>'
                      ); ?>
                      <meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
                      <meta itemprop="mainEntityOfPage" content="<?php the_permalink(); ?>" hidden />

                      <?php get_template_part( 'meta-author' ); ?>
                      
                    </p>

                    <?php nu_gm_singular_header(); ?>

                  </div> <?php // end article header ?>

                  <div class="entry-content cf" itemprop="articleBody text">
                    <?php
                      // the content (pretty self explanatory huh)
                      the_content();

                      /*
                       * Link Pages is used in case you have posts that are set to break into
                       * multiple pages. You can remove this if you don't plan on doing that.
                       *
                       * Also, breaking content up into multiple pages is a horrible experience,
                       * so don't do it. While there are SOME edge cases where this is useful, it's
                       * mostly used for people to get more ad views. It's up to you but if you want
                       * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
                       *
                       * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
                       *
                      */
                      wp_link_pages( array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'nu_gm' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                      ) );
                    ?>
                  </div> <?php // end article section ?>

                  <div class="article-footer">

                    <?php
                      $category_list = get_the_category_list(', ');
                      if(!empty($category_list)) {
                        printf( '<p class="article-taxonomy-list categories">'.__( 'Categories', 'nu_gm' ).': %1$s</p>', $category_list );
                      }
                    ?>

                    <?php the_tags( '<p class="article-taxonomy-list tags"><span class="tags-title">' . __( 'Tags:', 'nu_gm' ) . '</span> ', ', ', '</p>' ); ?>

                    <?php nu_gm_singular_footer(); ?>

                  </div> <?php // end article footer ?>

                  <?php comments_template(); ?>

                </article> <?php // end article ?>

              </main>

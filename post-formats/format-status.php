              <?php get_sidebar(); ?>
              
              <main id="main-content" tabindex="-1" class="content m-all t-2of3 d-5of7 cf" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/WebPageElement ">

                <article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> itemscope itemid="<?php the_permalink(); ?>" itemtype="http://schema.org/BlogPosting">

                  <header class="article-header">

                    <h1 class="entry-title single-title" itemprop="name"><?php the_title(); ?></h1>

                    <p class="byline vcard">
                      <?php printf( __( 'Posted', 'nu_gm' ).' %1$s %2$s',
                         /* the time the post was published */
                         '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                         /* the author of the post */
                         '<span class="by">'.__( 'by', 'nu_gm' ).'</span> <span class="entry-author author">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
                      ); ?>
                      <meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" />
                      <meta itemprop="mainEntityOfPage" content="<?php the_permalink(); ?>" />

                      <?php get_template_part( 'meta-author' ); ?>
                      
                    </p>

                  </header> <?php // end article header ?>

                  <section class="entry-content cf" itemprop="articleBody">
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
                  </section> <?php // end article section ?>

                  <footer class="article-footer">
                    <?php the_tags( '<p class="tags"><span class="tags-title">' . __( 'Tags:', 'nu_gm' ) . '</span> ', ', ', '</p>' ); ?>

                  </footer> <?php // end article footer ?>

                  <?php //comments_template(); ?>

                </article> <?php // end article ?>

              </main>

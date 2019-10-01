<?php get_header(); ?>

			<main id="main-content" tabindex="-1" class="content m-all t-2of3 d-5of7 cf">

        <?php nu_gm_breadcrumbs(); ?>
        
				<h2 class="archive-title"><span><?php _e( 'Search Results for:', 'nu_gm' ); ?></span> <?php echo esc_attr(get_search_query()); ?></h2>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" <?php post_class('cf format-standard'); ?>>

						<div class="entry-header article-header">

							<h4 class="search-title entry-title" id="post-<?php the_ID(); ?>-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>

              						<p class="byline entry-meta vcard">
                							<?php printf( __( 'Posted %1$s %2$s', 'nu_gm' ),
               							    /* the time the post was published */
               							    '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                  							    /* the author of the post */
                   							    '<span class="by">'.__( 'by', 'nu_gm').'</span> <span class="entry-author author"><a href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '">'. get_the_author() . '</a></span>'
                							); ?>
              						</p>

						</div>

						<div class="entry-content">
								<?php the_excerpt( '<span class="read-more">' . __( 'Read more &raquo;', 'nu_gm' ) . '</span>' ); ?>

						</div>

						<div class="article-footer">

							<?php if(get_the_category_list(', ') != ''): ?>
              					<?php printf( '<p class="article-taxonomy-list footer-category">' . __('Categories', 'nu_gm' ) . ': %1$s</p>' , get_the_category_list(', ') ); ?>
              					<?php endif; ?>

             					<?php the_tags( '<p class="article-taxonomy-list tags"><span class="tags-title">' . __( 'Tags:', 'nu_gm' ) . '</span> ', ', ', '</p>' ); ?>

						</div> <!-- end article footer -->

					</article>

				<?php endwhile; ?>

						<?php nu_gm_page_navi(); ?>

							<article id="post-not-found" class="hentry cf" aria-label="Didn't find what you're looking for?">
								<div class="entry-header article-header">
									<h4 class="search-title entry-title">Didn't find what you're looking for?</h4>
								<div class="entry-content">
									<p><?php _e( 'Try your search again, or try searching all of Northwestern using the global search tool below:', 'nu_gm' ); ?></p>
									<div class="searchblox">
										<form action="https://searchsite.northwestern.edu/searchblox/default_frontend/index.html" method="get" role="search" class="web-form">
											<div class="field">
												<label for="q-desktop"><strong>Search Northwestern:</strong></label>
												<input type="text" id="q-desktop" name="query" placeholder="Search web or people" value="<?php echo esc_attr(get_search_query()); ?>" />
											</div>
											<input type="hidden" name="advanced" value="false"/>
											<input type="hidden" name="tune.field" value="size"/>
											<input type="hidden" name="t.size.factor" value="10"/>
											<input type="hidden" name="t.size.modifier" value="reciprocal"/>
											<div class="field">
												<input type="submit" value="Search"/>
											</div>
										</form>
									</div>
								</div>
							</article>

					<?php endif; ?>

				</main>

					<?php get_sidebar(); ?>

<?php get_footer(); ?>

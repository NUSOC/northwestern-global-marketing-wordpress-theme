<?php get_header(); ?>

				<main id="main-content" tabindex="-1" class="content<?php if(!get_theme_mod('archive_display_sidebar_setting', true)): ?>-full<?php endif; ?> m-all t-2of3 d-5of7 cf">

					<?php nu_gm_breadcrumbs(); ?>

					<div itemscope itemtype="http://schema.org/ItemList">
						<?php
	            $wp_directory_item_query = new WP_Query( array(
	              'post_type'  => 'nu_gm_directory_item',
	            	'meta_key'   => 'nu_gm_wp_user',
	              'meta_value' => $wp_query->query_vars['author'],
	            ));
            ?>

            <?php if($wp_directory_item_query->have_posts()): ?>

            	<div class="archive-header">

								<h2 class="page-title">Profile</h2>

								<?php the_archive_title( '<h3 class="nu-gm-microdata" itemprop="name" hidden>', '</h3>' ); ?>

	            	<?php nu_gm_archive_header(); ?>

	            </div>

            <?php else: ?>

							<div class="archive-header">

								<?php the_archive_title( '<h2 class="page-title" itemprop="name">', '</h2>' ); ?>

	            	<?php nu_gm_archive_header(); ?>

	            </div>

							<?php if(!is_paged()): ?>

								<?php the_archive_description( '<div class="archive-description" itemprop="description">', '</div>' ); ?>

							<?php endif; ?>

            <?php endif; ?>

						<?php global $wp_query; $main_query = clone $wp_query; ?>

            <?php if ($wp_directory_item_query->have_posts()) : while ( $wp_directory_item_query->have_posts() ) : $wp_directory_item_query->the_post(); ?>

            	<?php if(!$main_query->is_paged()): // Start directory card display ?>

              	<?php get_template_part('nu-gm-formats/format', 'people-small'); ?>

              <?php else: ?>

              	<?php the_title( '<h4 class="author-title">', '</h4>' ); ?>

              <?php endif; // End directory card display ?>

            <?php endwhile; endif; ?>

          	<?php $wp_query = clone $main_query; wp_reset_postdata(); ?>

						<?php echo nu_gm_post_format_wrapper('start'); ?>

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php if(get_theme_mod('post_list_format_setting', 'standard') == 'standard'): ?>

								<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf format-standard' ); ?> aria-labelledby="post-<?php the_ID(); ?>-title" itemscope itemid="#post-<?php the_ID(); ?>" itemprop="listItem" itemtype="<?php echo nu_gm_schema(); ?>" itemref="footer-publisher-info">

									<div class="entry-header article-header">

										<h4 class="h2 entry-title" id="post-<?php the_ID(); ?>-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
										<p class="byline entry-meta vcard">
											<?php printf( __( 'Posted', 'nu_gm' ).' %1$s %2$s',
			              							     /* the time the post was published */
			              							     '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
			                   								/* the author of the post */
			                   								'<span class="by">'.__( 'by', 'nu_gm').'</span> <span class="entry-author author"><a href="' . get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '">'. get_the_author() . '</a></span>'
			                							); ?>

											<?php get_template_part( 'meta-author' ); ?>

  										<meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
  										<meta itemprop="name headline" content="<?php the_title(); ?>" hidden />

										</p>

									</div>

									<div class="entry-content cf">

										<?php the_post_thumbnail( 'nu-gm-thumb-300' ); ?>

										<?php the_excerpt(); ?>

									</div>

									<div class="article-footer">

										<?php nu_gm_archive_footer(); ?>

									</div>

    							<link itemprop="url mainEntityOfPage" href="<?php the_permalink() ?>" />
								<?php echo nu_gm_get_the_loop_index(); ?>

								</article>

							<?php else: ?>

								<?php get_template_part( 'nu-gm-formats/format', get_theme_mod('post_list_format_setting', 'standard') ); ?>

							<?php endif; ?>

						<?php endwhile; ?>

						<?php echo nu_gm_post_format_wrapper('end'); ?>

					</div>

							<?php nu_gm_page_navi(); ?>

					<?php else : ?>

							<article id="post-not-found" class="hentry cf">
								<div class="article-header">
									<h2><?php _e( 'Oops, Post Not Found!', 'nu_gm' ); ?></h2>
								</div>
								<div class="entry-content">
									<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'nu_gm' ); ?></p>
								</div>
								<div class="article-footer">
										<p><?php _e( 'This is the error message in the archive.php template.', 'nu_gm' ); ?></p>
								</div>
							</article>

					<?php endif; ?>

				</main>

			<?php get_sidebar(); ?>

<?php get_footer(); ?>

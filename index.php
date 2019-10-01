<?php get_header(); ?>

				<main id="main-content" tabindex="-1" class="m-all t-2of3 d-5of7 cf content<?php if(is_fullwidth()): ?>-full<?php endif; ?>">

					<?php nu_gm_breadcrumbs(); ?>

					<?php get_sidebar('homepage'); ?>

					<div itemscope itemtype="http://schema.org/ItemList">

						<div class="archive-header">
							<h2 itemprop="name">Recent Posts</h2>
							<?php nu_gm_archive_header(); ?>
						</div>

						<?php echo nu_gm_post_format_wrapper('start'); ?>

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php if(get_theme_mod('post_list_format_setting', 'standard') == 'standard'): ?>

								<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" <?php post_class( 'cf format-standard' ); ?> itemscope itemid="#post-<?php the_ID(); ?>" itemprop="itemListElement" itemtype="<?php echo nu_gm_schema(); ?>" itemref="footer-publisher-info">

									<div class="article-header">

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
										<?php the_content(); ?>
									</div>

									<div class="article-footer cf">
										<p class="footer-comment-count">
											<?php comments_number( __( '<span>No</span> Comments', 'nu_gm' ), __( '<span>One</span> Comment', 'nu_gm' ), __( '<span>%</span> Comments', 'nu_gm' ) );?>
										</p>


			             	<?php printf( '<p class="article-taxonomy-list footer-category">' . __('Categories', 'nu_gm' ) . ': %1$s</p>' , get_the_category_list(', ') ); ?>

			              <?php the_tags( '<p class="article-taxonomy-list footer-tags tags"><span class="tags-title">' . __( 'Tags:', 'nu_gm' ) . '</span> ', ', ', '</p>' ); ?>

										<?php nu_gm_archive_footer(); ?>


									</div>

    							<link itemprop="url mainEntityOfPage" href="<?php the_permalink() ?>" />
								<?php echo nu_gm_get_the_loop_index(); ?>

								</article>

							<?php else: ?>

								<?php get_template_part( 'nu-gm-formats/format', get_theme_mod('post_list_format_setting') ); ?>

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
										<p><?php _e( 'This is the error message in the index.php template.', 'nu_gm' ); ?></p>
								</div>
							</article>

					<?php endif; ?>


				</main>

			<?php get_sidebar(); ?>


<?php get_footer(); ?>

<?php get_header(); ?>

				<main id="main-content" tabindex="-1" class="content<?php if(!get_theme_mod('archive_display_sidebar_setting', true)): ?>-full<?php endif; ?> m-all t-2of3 d-5of7 cf">

					<?php nu_gm_breadcrumbs(); ?>

					<div itemscope itemtype="http://schema.org/ItemList">

						<div class="archive-header">
							<?php
							the_archive_title( '<h2 class="page-title" itemprop="name">', '</h2>' );
							nu_gm_archive_header();
							?>
						</div>
						<?php the_archive_description( '<div class="archive-description" itemprop="description">', '</div>' ); ?>

						<?php echo nu_gm_post_format_wrapper('start', 'nu_gm_project'); ?>

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php if(get_theme_mod('nu_gm_project_list_format_setting', 'feature-box') == 'standard'): ?>

								<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf format-standard' ); ?> aria-labelledby="post-<?php the_ID(); ?>-title" itemscope itemid="#post-<?php the_ID(); ?>" itemprop="itemListElement" itemtype="<?php echo nu_gm_schema(); ?>" itemref="footer-publisher-info">

									<div class="entry-header article-header">

										<h4 class="h2 entry-title" id="post-<?php the_ID(); ?>-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
										<p class="byline entry-meta vcard">
											<?php printf( __( 'Posted', 'nu_gm' ).' %1$s %2$s',
			              							     /* the time the post was published */
			              							     '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
			                   								/* the author of the post */
			                   								'<span class="by">'.__('by', 'nu_gm').'</span> <span class="entry-author author" itemprop="author" itemscope itemtype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
			                							); ?>
  										<meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
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

								<?php get_template_part( 'nu-gm-formats/format', get_theme_mod('nu_gm_project_list_format_setting', 'feature-box') ); ?>

							<?php endif; ?>

						<?php endwhile; ?>

						<?php echo nu_gm_post_format_wrapper('end', 'nu_gm_project'); ?>

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

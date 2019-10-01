<?php get_header(); ?>

				<main id="main-content" tabindex="-1" class="content<?php if(is_fullwidth()): ?>-full standard-page<?php endif; ?> m-all t-2of3 d-5of7 cf">

					<?php nu_gm_breadcrumbs(); ?>

					<div itemscope itemtype="http://schema.org/ItemList">

						<div class="archive-header">
							<?php
							the_archive_title( '<h2 class="page-title" itemprop="name">', '</h2>' );
							nu_gm_archive_header();
							?>
						</div>
						<?php the_archive_description( '<div class="archive-description" itemprop="description">', '</div>' ); ?>

						<?php echo nu_gm_post_format_wrapper('start', 'nu_gm_directory_item'); ?>

						<?php
							$letters_with_entries = array();
							$post_count = 0;
							$display_letter_grouping = get_theme_mod('nu_gm_directory_item_list_group_by_initial_setting', true);
						?>

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php if($display_letter_grouping): ?>

								<?php $first_letter = strtoupper(substr(get_post_meta(get_the_ID(), 'nu_gm_last_name', true),0,1)); ?>
								<?php if ($first_letter != $curr_letter): ?>
									<?php if (++$post_count > 1): ?>
											</div><!-- End directory-items-starting-with-letter -->
										</div><!-- End of letter-group -->
									<?php endif; ?>
									<?php
										$curr_letter = $first_letter;
										$letters_with_entries[] = $curr_letter;
									?>
									<div class="letter-group" id="starts-with-<?php echo $curr_letter; ?>">
										<div id="letter-cell-<?php echo $curr_letter; ?>" class="letter-cell"><h3><?php echo $curr_letter; ?></h3></div>
										<div id="directory-items-starting-with-<?php echo $curr_letter; ?>" class="directory-items-starting-with-letter">
								<?php endif; ?>

							<?php endif; ?>

							<?php if(get_theme_mod('nu_gm_directory_item_list_format_setting', 'people-medium') == 'standard'): ?>

								<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf format-standard' ); ?> aria-labelledby="post-<?php the_ID(); ?>-title" itemscope itemid="#post-<?php the_ID(); ?>" itemprop="itemListElement" itemtype="<?php echo nu_gm_schema(); ?>">

									<div class="entry-header article-header">

										<h4 class="h2 entry-title" id="post-<?php the_ID(); ?>-title" itemprop="name"><?php the_title(); ?></h4>

									</div>

									<div class="entry-content cf">

										<?php the_post_thumbnail( 'nu-gm-thumb-300' ); ?>

										<?php the_excerpt(); ?>

									</div>

									<div class="article-footer">

										<?php nu_gm_archive_footer(); ?>

									</div>

    							<link itemprop="url mainEntityOfPage" href="<?php the_permalink() ?>" aria-hidden="true" />

								</article>

							<?php else: ?>

								<?php get_template_part( 'nu-gm-formats/format', get_theme_mod('nu_gm_directory_item_list_format_setting', 'people-medium') ); ?>

							<?php endif; ?>

						<?php endwhile; ?>

						<?php if($display_letter_grouping): ?>
											</div><!-- End directory-items-starting-with-letter -->
										</div><!-- End of letter-group -->
						<?php endif; ?>

						<?php echo nu_gm_post_format_wrapper('end', 'nu_gm_directory_item'); ?>

					</div>

							<?php nu_gm_directory_page_navi(); ?>

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

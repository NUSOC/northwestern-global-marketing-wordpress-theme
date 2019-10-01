<?php get_header(); ?>

				<main id="main-content" tabindex="-1" class="content<?php if(is_fullwidth()): ?>-full<?php endif; ?> m-all t-2of3 d-5of7 cf">

					<?php nu_gm_breadcrumbs(); ?>

					<div itemscope itemtype="http://schema.org/ItemList">

						<div class="archive-header">
							<?php
							the_archive_title( '<h2 class="page-title" itemprop="name">', '</h2>' );
							nu_gm_archive_header();
							?>
						</div>
						<?php the_archive_description( '<div class="archive-description" itemprop="description">', '</div>' ); ?>

						<div class="standard-page">
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<?php get_template_part( 'nu-gm-formats/format-event', get_post_format() ); ?>

						<?php endwhile; ?>

						<div class="article-footer">

							<?php nu_gm_page_navi(); ?>

							<?php nu_gm_archive_footer(); ?>

						</div>

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
						</div>

					</div>

				</main>

			<?php get_sidebar(); ?>

<?php get_footer(); ?>

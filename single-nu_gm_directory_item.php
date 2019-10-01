<?php get_header(); ?>			

				<?php get_sidebar(); ?>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
              
          <main id="main-content" tabindex="-1" class="content m-all t-2of3 d-5of7 cf">

            <?php nu_gm_breadcrumbs(); ?>

            <div class="article-header entry-header">

	            <h2 class="entry-title single-title" rel="bookmark"><?php the_title(); ?></h2>

							<?php nu_gm_singular_header(); ?>

						</div>

						<?php
							/*
							 * Ah, post formats. Nature's greatest mystery (aside from the sloth).
							 *
							 * So this function will bring in the needed template file depending on what the post
							 * format is. The different post formats are located in the post-formats folder.
							 *
							 *
							 * REMEMBER TO ALWAYS HAVE A DEFAULT ONE NAMED "format.php" FOR POSTS THAT AREN'T
							 * A SPECIFIC POST FORMAT.
							 *
							 * If you want to remove post formats, just delete the post-formats folder and
							 * replace the function below with the contents of the "format.php" file.
							*/
							get_template_part( 'nu-gm-formats/format-people-medium', get_post_format() );
						?>

						<meta itemprop="url mainEntityOfPage" content="<?php the_permalink(); ?>" hidden />

            <div class="article-footer">

              <?php nu_gm_singular_footer(); ?>

            </div> <?php // end article footer ?>

					</main>

				<?php endwhile; ?>

				<?php else : ?>
					<main id="main-content" tabindex="-1" class="m-all t-2of3 d-5of7 cf" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
						<article id="post-not-found" class="hentry cf">
								<div class="article-header">
									<h2><?php _e( 'Oops, Post Not Found!', 'nu_gm' ); ?></h2>
								</div>
								<div class="entry-content">
									<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'nu_gm' ); ?></p>
								</div>
								<div class="article-footer">
										<p><?php _e( 'This is the error message in the single.php template.', 'nu_gm' ); ?></p>
								</div>
						</article>
					</main>
				<?php endif; ?>


<?php get_footer(); ?>

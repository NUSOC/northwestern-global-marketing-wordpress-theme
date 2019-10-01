<?php
/*
 Template Name: Page with Sidebar
*/
?>

<?php get_header(); ?>

				<main id="main-content" tabindex="-1" class="content m-all t-2of3 d-5of7 cf">

					<?php nu_gm_breadcrumbs(); ?>

					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" <?php post_class( 'cf' ); ?> itemscope itemid="<?php the_permalink(); ?>" itemtype="<?php echo nu_gm_schema(); ?>">

						<div class="article-header">

							<h2 class="page-title" id="post-<?php the_ID(); ?>-title" itemprop="name"><?php the_title(); ?></h2>

            				<?php nu_gm_singular_header(); ?>

						</div>

						<div class="entry-content cf" itemprop="text">
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
						</div>

						<div class="article-footer cf">

							<?php nu_gm_singular_footer(); ?>

						</div>

					</article>

					<?php endwhile; endif; ?>

				</main>

				<?php get_sidebar(); ?>

<?php get_footer(); ?>

<?php
/*
 Template Name: Page Full-Width
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

				<main id="main-content" tabindex="-1" class="content-full m-all t-2of3 d-5of7 cf">

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


						<div class="article-footer">

							<?php nu_gm_singular_footer(); ?>

						</div>

					</article>

					<?php endwhile; else : ?>

							<article id="post-not-found" class="hentry cf">
									<div class="article-header">
										<h2><?php _e( 'Oops, Post Not Found!', 'nu_gm' ); ?></h2>
								</div>
									<div class="entry-content">
										<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'nu_gm' ); ?></p>
								</div>
								<div class="article-footer">
										<p><?php _e( 'This is the error message in the page-custom.php template.', 'nu_gm' ); ?></p>
								</div>
							</article>

					<?php endif; ?>

				</main>


<?php get_footer(); ?>

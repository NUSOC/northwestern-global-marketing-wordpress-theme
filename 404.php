<?php get_header(); ?>

			<main id="main-content" tabindex="-1" class="content m-all t-2of3 d-5of7 cf" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

				<article id="post-not-found" class="hentry cf">

					<div class="article-header">

						<h2><?php _e( 'Epic 404 - Article Not Found', 'nu_gm' ); ?></h2>

					</div>

					<div class="entry-content">

						<p><?php _e( 'The article you were looking for was not found, but maybe try looking again!', 'nu_gm' ); ?></p>

					</div>

					<div class="search">

							<p><?php get_search_form(); ?></p>

					</div>

					<div class="article-footer">

							<p><?php _e( 'This is the 404.php template.', 'nu_gm' ); ?></p>

					</div>

				</article>

			</main>

<?php get_footer(); ?>

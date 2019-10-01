<?php get_header(); ?>

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

              <main id="main-content" tabindex="-1" class="content-full m-all t-2of3 d-5of7 cf">

                <?php nu_gm_breadcrumbs(); ?>

                <article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" <?php post_class('cf'); ?> itemscope itemid="<?php the_permalink(); ?>" itemtype="<?php echo nu_gm_schema(); ?>" itemref="hero-image-link footer-publisher-info">

                  <div class="article-header entry-header">

                    <h2 class="entry-title single-title" id="post-<?php the_ID(); ?>-title" itemprop="name" rel="bookmark"><?php the_title(); ?></h2>

                    <meta itemprop="datePublished" content="<?php echo get_the_time('Y-m-d'); ?>" hidden />
                    <meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
                    <meta itemprop="mainEntityOfPage" content="<?php the_permalink(); ?>" hidden />
                    <time class="updated nu-gm-microdata" datetime="<?php echo get_the_time('c'); ?>" hidden><?php echo the_modified_time('Y-m-d'); ?></time>

                    <?php nu_gm_singular_header(); ?>

                  </div> <?php // end article header ?>

                  <?php /* Project Examples Section */ ?>
                  <div class="project-info">
                    <?php
                      $tabbed_display          = get_post_meta(get_the_ID(), 'nu_gm_project_tabbed_display', true);
                      $project_url             = get_post_meta(get_the_ID(), 'nu_gm_project_url',            true);
                      $project_images          = get_post_meta(get_the_ID(), 'nu_gm_project_images',         false);
                      $project_videos          = get_post_meta(get_the_ID(), 'nu_gm_project_video',          true);
                      $project_team            = rwmb_meta('nu_gm_project_team');
                      $category_list           = get_the_term_list(get_the_ID(), 'nu_gm_project_category',    '<ul><li>', '</li><li>', '</li></ul>');
                      $services_list           = get_the_term_list(get_the_ID(), 'nu_gm_project_service', '<ul><li>', '</li><li>', '</li></ul>');
                    ?>
                    <?php if($tabbed_display): ?>
                      <div id="tab-container">

                        <ul id="tabs" role="tablist">
                          <li role="presentation"><a class="active" aria-controls="tab-panel-description" aria-selected="true" href="#tab-panel-description" id="tab1" role="tab">Description</a></li>
                          <?php if(!empty($project_team)): ?><li role="presentation"><a class="" aria-controls="tab-panel-contributors" aria-selected="false" href="#tab-panel-contributors" id="project-contributors" role="tab">Contributors</a></li><?php endif; ?>
                          <?php if(!empty($project_images)): ?><li role="presentation"><a aria-controls="tab-panel-images" aria-selected="false" href="#tab-panel-images" id="project-images" role="tab">Images</a></li><?php endif; ?>
                          <?php if(!empty($project_videos)): ?><li role="presentation"><a aria-controls="tab-panel-videos" aria-selected="false" href="#tab-panel-videos" id="project-videos" role="tab">Videos</a></li><?php endif; ?>
                        </ul>

                        <div id="tab-content" class="clearfix">
                    <?php endif; ?>

                          <?php /* Description Tab */ ?>
                          <div aria-labelledby="tab1" id="tab-panel-description" role="tabpanel">
                            <?php if(!empty($services_list) || !empty($category_list) || !empty($project_url)): ?>
                              <div id="sidebar">
                                <div class="box">

                                  <?php /* Project URL Button */ ?>
                                  <?php if(!empty($project_url)): ?>
                                    <div class="project-examples-url" itemprop="about" itemscope itemtype="http://schema.org/CreativeWork">
                                      <a href="<?php echo $project_url; ?>" class="button" target="_blank" itemprop="url">View Project</a>
                                      <meta itemprop="name" content="<?php echo the_title(); ?>" hidden />
                                    </div>
                                    <?php if(!empty($services_list) || !empty($category_list)): ?>
                                      <br>
                                    <?php endif; ?>
                                  <?php endif; ?>

                                  <?php /* Project Category List */ ?>
                                  <?php if(!empty($category_list)): ?>
                                    <h4>Categories</h4>
                                    <?php echo $category_list; ?>
                                  <?php endif?>

                                  <?php /* Project Service List */ ?>
                                  <?php if(!empty($services_list)): ?>
                                    <h4>Services</h4>
                                    <?php echo $services_list; ?>
                                  <?php endif?>

                                </div>
                              </div>
                            <?php endif?>

                            <div class="entry-content cf" itemprop="text">
                              <?php
                                // the content (pretty self explanatory huh)
                                the_content();
                              ?>

                              <?php
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
                            </div> <?php // end article section ?>
                          </div>

                          <?php /* Contributors Tab */ ?>
                          <?php if(!empty($project_team)): ?>
                            <div class="project-contributors clearfix" aria-labelledby="project-contributors" id="tab-panel-contributors" role="tabpanel">
                              <?php if(!$tabbed_display): ?><h3 class="section-title">Contributors</h3><?php endif; ?>
                              <?php foreach ($project_team as $contributor_pair): ?>
                                <?php $contributor_query = new WP_Query( array(
                                  'post__in' => array($contributor_pair['nu_gm_project_team_contributor']),
                                  'post_type' => 'nu_gm_directory_item',
                                )); ?>
                                <?php while ( $contributor_query->have_posts() ) : $contributor_query->the_post(); ?>
                                  <?php include(locate_template('nu-gm-formats/format-people-small-contributor.php')); ?>
                                <?php endwhile; ?>
                                <?php wp_reset_postdata(); ?>
                              <?php endforeach; ?>
                            </div>
                          <?php endif; ?>

                          <?php /* Images Tab */ ?>
                          <?php if(!empty($project_images)): ?>
                            <div class="project-examples-images clearfix" aria-labelledby="project-images" id="tab-panel-images" role="tabpanel">
                              <?php if(!$tabbed_display): ?><h3 class="section-title">Photos</h3><?php endif; ?>
                              <div class="photo-grid">
                                <?php
                                  $images = rwmb_meta( 'nu_gm_project_images', 'type=image&size=people-small' );
                                  if ( !empty( $images ) ) {
                                    foreach ( $images as $image ): ?>
                                      <article class="photo-box">
                                        <a href="<?php echo $image['full_url']; ?>" target="_blank">
                                          <img src="<?php echo $image['url']; ?>" width="<?php echo $image['width']; ?>" height="<?php echo $image['height']; ?>" alt="<?php echo $image['alt']; ?>" />
                                        </a>
                                        <?php if(!empty($image['caption'])): ?>
                                          <p><?php echo $image['caption']; ?></p>
                                        <?php endif; ?>
                                      </article>
                                    <?php endforeach;
                                  }
                                ?>
                              </div>
                            </div>
                          <?php endif; ?>

                          <?php /* Videos Tab */ ?>
                          <?php if(!empty($project_videos)): ?>
                            <div class="project-examples-videos clearfix" aria-labelledby="project-videos" id="tab-panel-videos" role="tabpanel">
                              <?php if(!$tabbed_display): ?><h3 class="section-title">Videos</h3><?php endif; ?>
                              <?php echo rwmb_meta('nu_gm_project_video', 'type=oembed', get_the_ID()); ?>
                            </div>
                          <?php endif; ?>

                  <?php if($tabbed_display): ?>

                        </div> <?php // end tab content section ?>

                      </div> <?php // end tab container section ?>

                    <?php endif; ?>

                  </div>

                  <div class="article-footer">

                    <?php nu_gm_singular_footer(); ?>

                  </div> <?php // end article footer ?>

                </article> <?php // end article ?>

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

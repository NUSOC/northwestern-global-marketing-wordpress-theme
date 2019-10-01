          <?php if ( is_active_sidebar( 'homepage' ) && (is_home() || is_front_page()) && nu_gm_get_current_page_id() !== get_option('page_for_posts', '-')): ?>
            <?php if(max( 1, get_query_var('paged') ) <= 1): ?>
              <section id="homepage-widget-area" class="three-column-links widget-area" aria-label="homepage widget area">
  							<?php dynamic_sidebar( 'homepage' ); ?>
  						</section>
  					<?php endif; ?>
          <?php endif; ?>

					<?php if ( is_active_sidebar( 'sidebar1' ) && !is_fullwidth() ) : ?>
						<nav role="complementary" aria-label="section navigation menu widget-area" id="left-nav" tabindex="-1">
							<?php dynamic_sidebar( 'sidebar1' ); ?>
						</nav>
					<?php endif; ?>

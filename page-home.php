<?php
/*
 Template Name: Homepage
*/
?>
<?php if(is_front_page()): ?>
	<?php get_header(); ?>

					<main id="main-content" tabindex="-1" class="m-all t-2of3 d-5of7 cf content-full">
											
						<?php get_sidebar('homepage'); ?>

					</main>


	<?php get_footer(); ?>
<?php else: ?>
	<?php 
  $page_full_template = file_exists(get_stylesheet_directory() . '/page-full.php') ? get_stylesheet_directory() . '/page-full.php' : get_template_directory() . '/page-full.php';
  include($page_full_template);
  ?>
<?php endif;
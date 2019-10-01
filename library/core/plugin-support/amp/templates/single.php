<!doctype html>
<html amp>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no">
		<?php do_action( 'amp_post_template_head', $this ); ?>

		<style amp-custom>
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
		</style>
	 	<script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
	</head>
	<body class="standard-page">
		<header class="amp-wp-title-bar">
			<div id="mini">
		    <div id="top-bar">
		      <div class="contain-1120 group">
		        <div id="left"><a id="site-name-link" href="http://www.northwestern.edu/"><amp-img src="<?php echo get_template_directory_uri(); ?>/library/images/northwestern.svg" width="170" height="21" alt="Northwestern University"></amp-img></a></div>
		      </div>
		    </div>
		  </div>
			<div class="bottom-bar contain-1120">
				<div id="department">
					<h1>
						<a href="<?php echo esc_url( $this->get( 'home_url' ) ); ?>">
							<?php echo esc_html( $this->get( 'blog_name' ) ); ?>
						</a>
					</h1>
				</div>
				<div id="mobile-links">
	        <button class="mobile-link mobile-nav-link" id="mobile-nav-link" on="tap:mobile-nav.toggle"><span class="hide-label">Menu</span></button>
	      </div>
			</div>
		</header>
    <amp-sidebar id="mobile-nav" side="right" layout="nodisplay">
      <?php wp_nav_menu(array(
                     'container' => false,                           // remove nav container
                     'container_class' => 'menu cf',                 // class of container (should you choose to use it)
                     'menu' => __( 'The Main Menu', 'nu_gm' ),  // nav name
                     'menu_class' => 'nav top-nav cf',               // adding custom nav class
                     'theme_location' => 'main-nav',                 // where it's located in the theme
                     'before' => '',                                 // before the menu
                           'after' => '',                                  // after the menu
                           'link_before' => '',                            // before each link
                           'link_after' => '',                             // after each link
                           'depth' => 1,                                   // limit the depth of the nav
                     'fallback_cb' => ''                             // fallback function (if there is one)
          )); ?>
    </amp-sidebar>
	  <?php if( has_post_thumbnail() ): ?>
	  <section class="hero contain-1440">
	    <div class="hero-image">
	      <div class="contain-1120">
	        <h2><?php the_title(); ?></h2>
	        <?php if( $subtitle = get_post_meta(get_the_id(), 'nu_gm_hero_banner_subtitle', true) ): ?><p><?php echo $subtitle; ?></p><?php endif; ?>
	      </div>
	    </div>
	  </section>
		<?php endif; ?>
		<div id="page">
			<div class="amp-wp-content contain-1120">
				<h2 class="amp-wp-title"><?php echo wp_kses_data( $this->get( 'post_title' ) ); ?></h2>
				<ul class="amp-wp-meta">
					<?php $this->load_parts( apply_filters( 'amp_post_template_meta_parts', array( 'meta-author', 'meta-time', 'meta-taxonomy' ) ) ); ?>
				</ul>
				<main class="content-full">
					<?php echo $this->get( 'post_amp_content' ); // amphtml content; no kses ?>
				</main>
			</div>
		</div>
		<footer>
	    <div id="footer-publisher-info" class="contain-970">
	      <div class="footer-content">
	        <a href="http://www.northwestern.edu/" title="Northwestern University logo">
	          <amp-img alt="Northwestern University logo" src="<?php echo get_template_directory_uri(); ?>/library/images/northwestern-university.svg" width="170" height="52"></amp-img>
	        </a>
	        <ul>
	          <li>&copy; <?php echo date("Y") ?> <span>Northwestern University</span></li>
	          <li><a href="http://www.northwestern.edu/contact.html">Contact Northwestern University</a></li>
	          <li><a href="http://www.northwestern.edu/hr/careers/">Careers</a></li>
	          <li><a href="http://www.northwestern.edu/disclaimer.html">Disclaimer</a></li>
	          <li><a href="http://www.northwestern.edu/emergency/index.html">Campus Emergency Information</a></li>
	          <li><a href="http://policies.northwestern.edu/">University Policies</a></li>
	        </ul>
	      </div>
	      <div class="footer-content contact">
	        <?php
	          $footer_contact_info_address_line_1 = get_theme_mod('footer_contact_info_address_line_1_setting', '633 Clark Street');
	          $footer_contact_info_address_line_2 = get_theme_mod('footer_contact_info_address_line_2_setting', 'Evanston, IL 60208');
	          $footer_contact_info_phone_1_label = get_theme_mod('footer_contact_info_phone_1_label_setting', 'Evanston');
	          $footer_contact_info_phone_1_number = get_theme_mod('footer_contact_info_phone_1_number_setting', '(847) 491-3741');
	          $footer_contact_info_phone_2_label = get_theme_mod('footer_contact_info_phone_2_label_setting', 'Chicago');
	          $footer_contact_info_phone_2_number = get_theme_mod('footer_contact_info_phone_2_number_setting', '(312) 503-8649');
	        ?>
	        <?php if(!empty($footer_contact_info_address_line_1)): ?>
	          <ul>
	            <li class="footer-pin-icon"><span class="hide-label">Address</span></li>
	            <li itemprop="address"><?php echo $footer_contact_info_address_line_1; ?>
	            <?php if(!empty($footer_contact_info_address_line_2)): ?><br><?php echo $footer_contact_info_address_line_2; ?><?php endif; ?></li>
	          </ul>
	        <?php endif; ?>
	        <?php if(!empty($footer_contact_info_phone_1_number)): ?>
	          <ul>
	            <li class="footer-phone-icon"><span class="hide-label">Phone number</span></li>
	            <?php if(!empty($footer_contact_info_phone_1_label)): ?><li><strong><?php echo $footer_contact_info_phone_1_label; ?></strong></li><?php endif; ?>
	            <li itemprop="telephone"><?php echo $footer_contact_info_phone_1_number; ?></li>
	          </ul>
	        <?php endif; ?>
	        <?php if(!empty($footer_contact_info_phone_2_number)): ?>
	          <ul>
	            <?php if(empty($footer_contact_info_phone_1_number)): ?><li class="footer-phone-icon"><span class="hide-label">Phone number</span></li><?php endif; ?>
	            <?php if(!empty($footer_contact_info_phone_2_label)): ?><li><strong><?php echo $footer_contact_info_phone_2_label; ?></strong></li><?php endif; ?>
	            <li><?php echo $footer_contact_info_phone_2_number; ?></li>
	          </ul>
	        <?php endif; ?>
	      </div>
	      <div class="footer-content">
	        <p><strong>Social Media</strong></p>
	        <a class="social rss" href="<?php bloginfo('rss2_url'); ?>"></a>
	        <?php 
	        $social_media_options = get_supported_social_media();
	        foreach ($social_media_options as $social_media_option) {
	          $key = str_replace(' ', '-', strtolower($social_media_option));
	          $social_media_setting_key = 'footer_social_media_links_'.$key.'_setting';
	          $social_media_account_url = get_theme_mod($social_media_setting_key, '');
	          if(!empty($social_media_account_url)) {
	            echo '<a class="social '.$key.'" href="'.$social_media_account_url.'"></a>';
	          }
	        }
	        ?>
	      </div>
	      <div class="footer-content">
	        <?php wp_nav_menu( array( 'theme_location' => 'footer-links', 'depth' => 1 ) ); ?>
	      </div>
	    </div>
	  </footer>
		<?php do_action( 'amp_post_template_footer', $this ); ?>
	</body>
</html>

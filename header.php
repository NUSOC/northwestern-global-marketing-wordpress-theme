<?php global $wp_query; ?>
<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head itemref="footer-publisher-info searchform">
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-touch-icon.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
    <meta name="theme-color" content="#4e2a84">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

	</head>

	<body id="nu-gm-body" <?php body_class(); ?>>
    <header id="nu">
      <a class="screen-reader-shortcut" href="#page">Skip to main content</a>
      <div id="mini">
        <div id="top-bar">
          <div class="contain-1120 group">
            <div id="left">
              <a href="<?php echo nu_gm_top_bar_northwestern_logo_url(); ?>">
                <img id="top-bar-northwestern-logo" src="<?php echo nu_gm_top_bar_northwestern_logo_img(); ?>" alt="Northwestern University Homepage" class="<?php echo nu_gm_top_bar_northwestern_logo_img_class(); ?>">
                <span class="hide-label">Northwestern University</span>
              </a>
            </div>
            <?php wp_nav_menu(array(
              'container' => false,                           // remove nav container
              'container_class' => 'menu cf',                 // class of container (should you choose to use it)
              'menu' => __( 'The Main Menu', 'nu_gm' ), // nav name
              'menu_class' => 'nav top-nav cf',               // adding custom nav class
              'menu_id' => 'right',
              'theme_location' => 'upper-nav',                // where it's located in the theme
              'before' => '',                                 // before the menu
              'after' => '',                                  // after the menu
              'link_before' => '',                            // before each link
              'link_after' => '',                             // after each link
              'depth' => 1,                                   // limit the depth of the nav
              'fallback_cb' => '',                            // fallback function (if there is one)
              'items_wrap' => '<div id="%1$s"><ul class="%2$s" aria-label="upper nav menu">%3$s</ul></div>'
            )); ?>
          </div>
        </div>
      </div>
      <div class="bottom-bar contain-1120 group">
        <?php $header_lockup_format = get_theme_mod('header_lockup_format_setting') ?: 'opt_1'; ?>
        <div id="department" class="gm-lockup-<?php echo $header_lockup_format; ?>">
          <?php get_template_part( 'nu-gm-formats/format', 'header-lockup-'.$header_lockup_format ); ?>
        </div>
        <div id="search" class="hide-mobile">
          <div class="search-form">
            <form role="search" method="get" id="searchform" class="searchform" action="<?php echo home_url(); ?>" aria-label="site search form">
              <div>
                <label id="q-label" for="q" class="hide-label screen-reader-text">Search for:</label>
                <input type="search" id="q" name="s" placeholder="Search this Site" aria-label="search this site">
                <button type="submit" id="searchsubmit" aria-label="submit search"><span class="hide-label">Search</span></button>
              </div>
            </form>
          </div>
        </div>
        <!-- <div id="mobile-links"><a href="#mobile-nav" class="mobile-link mobile-nav-link"><span class="hide-label">Menu</span></a></div> -->
        <div id="mobile-links"><a class="mobile-link mobile-search-link" href="#mobile-search"><span class="hide-label">Search</span></a>
          <div id="mobile-search" style="display: none;">
            <div class="search-form group">
              <form method="get" action="<?php echo home_url(); ?>" role="search" aria-label="site mobile search form">
                <label id="q-mobile-label" class="hide-label screen-reader-text" for="q-mobile">Search for:</label>
                <input type="search" id="q-mobile" name="s" placeholder="Search this Site" aria-label="search this site">
                <button type="submit" aria-label="submit search"><span class="hide-label">Search</span></button>
              </form>
            </div>
          </div>
          <a class="mobile-link mobile-nav-link" id="mobile-nav-link" href="#mobile-nav"><span class="hide-label">Menu</span></a>
        </div>
        <nav id="mobile-nav" style="display: none;">
          <?php wp_nav_menu(array(
                'container' => false,                           // remove nav container
                'container_class' => 'menu cf',                 // class of container (should you choose to use it)
                'menu' => 'main-nav', // nav name
                'menu_class' => 'nav top-nav cf',               // adding custom nav class
                'menu_id' => 'mobile-nav-inner',
                'theme_location' => 'main-nav',                 // where it's located in the theme
                'before' => '',                                 // before the menu
                'after' => '',                                  // after the menu
                'link_before' => '',                            // before each link
                'link_after' => '',                             // after each link
                'depth' => 0,                                   // limit the depth of the nav
                'fallback_cb' => '',                            // fallback function (if there is one)
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>'
              )); ?>
        </nav>
      </div>
    </header>

    <?php wp_nav_menu(array(
      'container' => 'nav',                           // remove nav container
      'container_class' => nu_gm_top_nav_classes(), 
      'container_id' => 'top-nav',                 // class of container (should you choose to use it)
      'menu' => 'main-nav', // nav name
      'items_wrap' => '<div class="contain-1120"><ul id="%1$s" class="%2$s" itemid="'.home_url().'#top-nav-inner" itemscope itemtype="http://schema.org/SiteNavigationElement" role="menubar">%3$s</ul></div>',
      'menu_class' => 'nav top-nav',               // adding custom nav class
      'menu_id' => 'top-nav-inner',
      'theme_location' => 'main-nav',                 // where it's located in the theme
      'before' => '',                                 // before the menu
      'after' => '',                                  // after the menu
      'link_before' => '',                            // before each link
      'link_after' => '',                             // after each link
      'depth' => 2,                                   // limit the depth of the nav
      'fallback_cb' => '',                            // fallback function (if there is one)
      'walker' => new NU_GM_Sublevel_Walker
    )); ?>

    <?php
      $hero_template = file_exists(get_stylesheet_directory() . '/hero.php') ? get_stylesheet_directory() . '/hero.php' : get_template_directory() . '/hero.php';
      require_once($hero_template);
    ?>

    <div id="page" <?php if(is_fullwidth()): ?>class="contain-1440"<?php endif; ?>>
      <?php if(is_fullwidth()): ?><div class="contain-1120"><?php endif; ?>

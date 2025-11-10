<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/*
This is the core NU_GM file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.
*/

// CUSTOMIZE THE WORDPRESS ADMIN
require_once( dirname( __FILE__ ) . '/core/admin.php' );

// LOAD SUPPORT FOR DEPRICATED FUNCTIONS
require_once( dirname( __FILE__ ) . '/core/depricated.php' );

// LOAD IMAGE SIZE HELPER FUNCTIONS
require_once( dirname( __FILE__ ) . '/core/image_helper.php' );

// LOAD WP CUSTOMIZER SETTINGS
require_once( dirname( __FILE__ ) . '/core/customizer.php' );

// LOAD PLUGIN SUPPORT
require_once( dirname( __FILE__ ) . '/core/plugin-support/amp/amp.php' );
require_once( dirname( __FILE__ ) . '/core/plugin-support/formidable.php' );
require_once( dirname( __FILE__ ) . '/core/plugin-support/divi/divi.php' );

// LOAD CUSTOM WIDGETS
require_once( dirname( __FILE__ ) . '/core/widgets/widget-text-fullwidth.php' );
require_once( dirname( __FILE__ ) . '/core/widgets/widget-news.php' );
require_once( dirname( __FILE__ ) . '/core/widgets/widget-posts.php' );
require_once( dirname( __FILE__ ) . '/core/widgets/widget-statistics.php' );
require_once( dirname( __FILE__ ) . '/core/widgets/widget-planitpurple.php' );

// LOAD WP CUSTOM CONTENT TYPES (NOT COMPATIBLE WITH CAMPUSPRESS)
if(strpos(get_home_url(1), 'sites.northwestern.edu') === false) {
  require_once( dirname( __FILE__ ) . '/core/custom-content-types/post-type-nu_gm_news.php' );
  require_once( dirname( __FILE__ ) . '/core/custom-content-types/post-type-nu_gm_project.php' );
  require_once( dirname( __FILE__ ) . '/core/custom-content-types/post-type-nu_gm_directory_item.php' );
  require_once( dirname( __FILE__ ) . '/core/custom-content-types/post-type-nu_gm_event.php' );
} else {
  // LOAD CUSTOM CAMPUSPRESS-ONLY CODE
  require_once( dirname( __FILE__ ) . '/nusites/nu_gm_nusites.php' );
}

// LOAD WP CUSTOM FIELDS
require_once( dirname( __FILE__ ) . '/core/custom-fields/fields-hero_banner.php' );

// USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
// require_once( 'library/custom-post-type.php' );


/*********************
CLEANUP THEME
*********************/

// Clean up unwanted actions in wp_head
function nu_gm_cleanup() {
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
  // remove WP version from css
  add_filter( 'style_loader_src', 'nu_gm_remove_wp_ver_css_js', 9999 );
  // remove Wp version from scripts
  add_filter( 'script_loader_src', 'nu_gm_remove_wp_ver_css_js', 9999 );
  // force fresh WP version for css
  add_filter( 'style_loader_src', 'nu_gm_fresh_wp_ver_css_js', 10000 );
  // force fresh Wp version for scripts
  add_filter( 'script_loader_src', 'nu_gm_fresh_wp_ver_css_js', 10000 );

  // Set default format for post list appearance
  if(!get_theme_mod('nu_gm_post_list_format_setting', false))
    set_theme_mod('nu_gm_post_list_format_setting', 'photo-feature');
}

// A better title
// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
function rw_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ) {
    $title .= get_bloginfo( 'name' );
  } else {
    $title = get_bloginfo( 'name' ) . $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title .= " {$sep} {$site_description}";
  }

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'nu_gm' ), max( $paged, $page ) );
  }

  return $title;

}

// remove WP version from RSS
function nu_gm_rss_version() { return ''; }

// remove WP version from scripts
function nu_gm_remove_wp_ver_css_js( $src ) {
  if ( strpos( $src, 'ver=' ) ) {
    $src = remove_query_arg( 'ver', $src );
  }
  return $src;
}

// force fresh WP version from scripts
function nu_gm_fresh_wp_ver_css_js( $src ) {
  if(isset($_GET['fresh'])) {
    $src = add_query_arg( 'ver', time(), $src );
  } else if(preg_match('/\/wp\-content\/themes\/(nu_gm[^\/]*)\/(?:library|assets)\/(?:css|js)\//i', $src, $matches)) {
    if(!empty($matches[1]) && $theme = wp_get_theme($matches[1])) {
      $version = $theme->get('Version');
    }
    if(empty($version)) {
      $theme = wp_get_theme('nu_gm');
      $version = $theme->get('Version');
    }
    $src = add_query_arg( 'ver', $version, $src );
  }
  return $src;
}

// remove injected CSS for recent comments widget
function nu_gm_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function nu_gm_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

// remove injected CSS from gallery
function nu_gm_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}

// Add dropdown arrow to menus
function nu_gm_add_menu_dropdown_arrows($item_output, $item, $depth, $args){
  if (!empty($args) && !empty($args->menu_id) && $args->menu_id == 'mobile-nav-inner') {
    if (in_array('menu-item-has-children', $item->classes)) {
      $item_output .= '<span class="arrow"><a aria-haspopup="true" href="#" role="button"><span>Expand</span>About Submenu</a></span>';
    }
  } else if (!empty($args) && !empty($args->menu_id) && $args->menu_id == 'top-nav-inner') {
    if ($item->menu_item_parent == 0) {
      if(in_array('menu-item-has-children', $item->classes)) {
        $item_output = str_replace('<a ', '<a role="menuitem" aria-haspopup="true" ', $item_output);
        $item_output = str_replace('</a>', '<span class="dropdown-arrow" aria-hidden="true"></span></a>', $item_output);
      } else {
        $item_output = str_replace('<a ', '<a role="menuitem" ', $item_output);
      }
    } else {
      $item_output = str_replace('<a ', '<a role="menuitem" ', $item_output);
    }
  }
  return $item_output;
}
add_filter('walker_nav_menu_start_el', 'nu_gm_add_menu_dropdown_arrows', 10, 4);

// Break submenus into links and intro sections
class NU_GM_Sublevel_Walker extends Walker_Nav_Menu
{
  function __construct() {
    $this->use_fullwidth_dropdown = !in_array( 'narrow-dropdown', apply_filters( 'nu_gm_top_nav_classes', array() ) );
  }
  public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    parent::start_el( $output, $item, $depth, $args, $id );
    if($depth === 1) {
      $output = str_replace('<li ', '<li ', $output);
    }
    
    if($depth === 0) {
      $classes = empty( $item->classes ) ? array() : (array) $item->classes;
      $classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
      if(in_array('menu-item-has-children', $classes)) {
        $title = apply_filters( 'the_title', $item->title, $item->ID );
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );
        $aria_label = ! empty( $title ) ? ' aria-label="dropdown menu for '.$title.'"' : '';
        $output .= "<ul class='sub-menu dropdown' aria-expanded=
        'false' role='menu'".$aria_label.">";
      }
    }
  }
  public function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    parent::end_el( $output, $item, $depth, $args, $id );
    if($depth === 0) {
      $classes = empty( $item->classes ) ? array() : (array) $item->classes;
      $classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );
      if(in_array('menu-item-has-children', $classes)) {
        $output = $output.'</ul>';
      }
    }
  }
  public function start_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat("\t", $depth);
    if($this->use_fullwidth_dropdown) {
      $output .= "<li class='nav-intro'></li><li class='nav-links' role='presentation'>";
    } else {
      $output .= "<li>";
    }
    $output .= "<ul>\n";
  }
  public function end_lvl( &$output, $depth = 0, $args = array() ) {
    $indent = str_repeat("\t", $depth);
    $output .= "$indent</ul></li>\n";
  }
}

/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function nu_gm_scripts_and_styles() {

  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  if (!is_admin()) {
    // Set $default_version based on $_GET['fresh']
    $default_version = '';

		// modernizr (without media query polyfill)
		wp_register_script( 'nu_gm-modernizr', get_template_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );

		// register main stylesheet
		wp_register_style( 'nu_gm-stylesheet', get_template_directory_uri() . '/library/css/style.css', array(), $default_version, 'all' );

    // ie-only style sheet
    wp_register_style( 'nu_gm-ie-only', get_template_directory_uri() . '/library/css/ie.css', array(), $default_version );

    // NU GM style sheet
    wp_register_style( 'nu_gm-styles', get_template_directory_uri() . '/library/css/gm-styles.css', array(), $default_version );

    // NU GM print style sheet
    wp_register_style( 'nu_gm-print', get_template_directory_uri() . '/library/css/gm-print.css', array(), $default_version, 'print' );

    // NU GM Fancybox JS and Style Sheet (only registered, not enqueued here, only enqued where needed)
    wp_register_style( 'nu_gm-fancybox-css', get_template_directory_uri() . '/library/js/libs/fancybox/jquery.fancybox.css', array(), $default_version, '' );
    wp_register_script( 'nu_gm-fancybox-js', get_template_directory_uri() . '/library/js/libs/fancybox/jquery.fancybox.js', array( 'jquery' ), $default_version, '' );

    // comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
		  wp_enqueue_script( 'comment-reply' );
    }

		// enqueue styles and scripts
    wp_enqueue_style( 'nu_gm-styles' );
		wp_enqueue_script( 'nu_gm-modernizr' );
		wp_enqueue_style( 'nu_gm-stylesheet' );
    wp_enqueue_style( 'nu_gm-ie-only' );
    wp_enqueue_style( 'nu_gm-print');

		$wp_styles->add_data( 'nu_gm-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet


    //adding scripts file in the footer
    wp_enqueue_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', array(), $default_version );
    wp_register_script( 'nu_gm-js-wp', get_template_directory_uri() . '/library/js/scripts.js', array( 'jquery' ), $default_version, true );
		wp_enqueue_script( 'nu_gm-js-wp' );
    wp_enqueue_script( 'nu_gm-common', get_template_directory_uri().'/library/js/gm-scripts.js', array( 'jquery' ), $default_version );
    wp_enqueue_script( 'nu_gm-expander', get_template_directory_uri().'/library/js/gm-expander.js', array( 'jquery', 'nu_gm-common' ), $default_version );

    // Load swiper components
    wp_register_style( 'nu_gm-swiper', get_template_directory_uri() . '/library/js/libs/swiper/swiper.min.css', array(), $default_version );
    wp_enqueue_style( 'nu_gm-swiper' );
    wp_enqueue_script( 'swiper', get_template_directory_uri().'/library/js/libs/swiper/swiper.jquery.min.js', array( 'jquery' ), $default_version, true  );
    wp_enqueue_script( 'content-slider', get_template_directory_uri().'/library/js/content-slider.js', array( 'jquery', 'swiper' ), $default_version, true  );

	}
}

// loading admin scripts
function nu_gm_admin_scripts_and_styles() {
  // Load admin stylesheet
  wp_register_style( 'nu_gm-admin-style', get_template_directory_uri() . '/library/css/admin-style.css', array(), '' );
  wp_enqueue_style( 'nu_gm-admin-style' );
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function nu_gm_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(125, 125, true);

  // title tag
  add_theme_support( 'title-tag' );

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',                // title less blurb
			// 'gallery',           // gallery of images
			// 'link',              // quick link to other site
			// 'image',             // an image
			// 'quote',             // a quick quote
			// 'status',            // a Facebook like status update
			// 'video',             // video
			// 'audio',             // audio
      // 'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav'     => __( 'The Main Menu', 'nu_gm' ),   // main nav in header
			'footer-links' => __( 'Footer Links', 'nu_gm' ),    // secondary nav in footer
      'upper-nav'    => __( 'Upper Navigation', 'nu_gm' ) // navigation links it top purple menu bar
		)
	);

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
    'search-form',
    'gallery',
		'comment-form'
	) );

}


/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using nu_gm_related_posts(); )
function nu_gm_related_posts() {
	echo '<ul id="nu-gm-related-posts">';
	global $post;
	$tags = wp_get_post_tags( $post->ID );
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr .= $tag->slug . ',';
		}
		$args = array(
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts( $args );
		if($related_posts) {
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; }
		else { ?>
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'nu_gm' ) . '</li>'; ?>
		<?php }
	}
	wp_reset_postdata();
	echo '</ul>';
}

/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function nu_gm_page_navi() {
  global $wp_query;
  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;
  echo '<div class="standard-page center-list pagination-wrapper"><nav class="pagination">';
  echo paginate_links( array(
    'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    'format'       => '',
    'current'      => max( 1, get_query_var('paged') ),
    'total'        => $wp_query->max_num_pages,
    'prev_text'    => '&larr;',
    'next_text'    => '&rarr;',
    'type'         => 'list',
    'end_size'     => 3,
    'mid_size'     => 3
  ) );
  echo '</nav></div>';
}

/*********************
RANDOM CLEANUP ITEMS
*********************/

// Add appropriate page classes to the body
add_filter( 'body_class','nu_gm_body_classes', 10 );
function nu_gm_body_classes( $classes ) {
  if ( is_fullwidth() ) {
    $classes[] = 'landing-page';
  } else {
    $classes[] = 'standard-page';
  }

  return $classes;
}

// Add appropriate page classes to the body
add_filter( 'body_class','nu_gm_narrow_page_body_class', 2000, 1 );
function nu_gm_narrow_page_body_class( $classes ) {
  if( is_singular() &&
      in_array('standard-page', $classes) &&
      !in_array('nu-gm-divi-page', $classes) &&
      !in_array('narrow-page', $classes) &&
      ( in_array('page-template-page-full', $classes) || // Page fullwidth template
        in_array('single-format-aside', $classes) // Post fullwidth template
      )
    ) {
    $classes[] = 'narrow-page';
  }
  return $classes;
}

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function nu_gm_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function nu_gm_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '';
}

// Adds microdata to category listing - itemprop="genre"
function nu_gm_category_microdata($thelist, $separator = '', $parents = '') {
  if(is_admin() || !$separator) return $thelist;

  $listitems = explode($separator, $thelist);

  $new_listitems = array();
  foreach($listitems as $item) {
    if ($new_item = preg_replace_callback('!(<\s*a\s*)([^>]*)(>)(.*?)(<\s*/a[^>]*>)!im', function($regex_parts){
      if( !$regex_parts || count((array)$regex_parts) < 4)
        return $regex_parts;

      // Prevents printing of "Uncategorized" category
      if ( in_array($regex_parts[4], array('Uncategorized')) )
        return '';

      // Add microdata: itemprop="genre"
      if ( strpos($regex_parts[4], 'itemprop') == false ) {
        $regex_parts[4] = '<span itemprop="genre">'.$regex_parts[4].'</span>';
        array_shift($regex_parts);
        $updated_cat = implode('', $regex_parts);
        array_unshift($regex_parts, $updated_cat);
      }

      return $regex_parts[0];
    }, $item)) {
      $new_listitems[] = $new_item;
    }
  }


  $thelist = implode($separator, $new_listitems);
  return $thelist;
}

// Adds microdata to tag listing - itemprop="keywords"
function nu_gm_tag_microdata($thelist, $before = '', $separator = '', $after = '', $id = NULL) {
  if(is_admin() || !$separator) return $thelist;

  $listitems = explode($separator, $thelist);

  $new_listitems = array();
  foreach($listitems as $item) {
    if ($new_item = preg_replace_callback('!(<\s*a\s*)([^>]*)(>)(.*?)(<\s*/a[^>]*>)!im', function($regex_parts){
      if( !$regex_parts || count((array)$regex_parts) < 4)
        return $regex_parts;

      // Add microdata: itemprop="keywords"
      if ( strpos($regex_parts[4], 'itemprop') == false ) {
        $regex_parts[4] = '<span itemprop="keywords">'.$regex_parts[4].'</span>';
        array_shift($regex_parts);
        $updated_cat = implode('', $regex_parts);
        array_unshift($regex_parts, $updated_cat);
      }

      return $regex_parts[0];
    }, $item)) {
      $new_listitems[] = $new_item;
    }
  }


  $thelist = implode($separator, $new_listitems);
  return $thelist;
}

// Add filter to clean up Archive title for directory_item content type
function nu_gm_custom_archive_titles ($title, $post_type_override = false) {
  if (is_author()) {
    global $wp_query;
    $directory_items = get_posts(array(
      'post_type'  => 'nu_gm_directory_item',
      'meta_key'   => 'nu_gm_wp_user',
      'meta_value' => $wp_query->query_vars['author'],
    ));
    $replace = empty($directory_items) ? "Profile <h4 class='author-title'>$1</h4>" : "Profile";
    $title = preg_replace('/^[A-Za-z0-9]*:\s(.*)/', $replace, $title);
  }
  return $title;
}
add_filter( 'post_type_archive_title', 'nu_gm_custom_archive_titles', 10, 2);
add_filter( 'get_the_archive_title', 'nu_gm_custom_archive_titles', 10, 2);


/*************************
PERMANENTLY DISMISSIBLE ADMIN NOTICES
*************************/


add_action( 'admin_enqueue_scripts', 'nu_gm_dismissible_admin_notice_script', 999 );
function nu_gm_dismissible_admin_notice_script () {
  wp_enqueue_script( 'nu-gm-dissmissible-admin-notices', get_template_directory_uri().'/library/js/nu-gm-dissmissible-admin-notices.js', array( 'jquery' ), '', true  );
  wp_localize_script( 'nu-gm-dissmissible-admin-notices', 'nu_gm_dismiss_admin_notice', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

// Helper function to print a permanently dismissible admin notice
function nu_gm_dismissible_admin_notice ( $notice_id, $notice_content, $notice_class = '') {
  if( nu_gm_admin_notice_active_for_user( $notice_id ) ) {
    $default_class   = 'notice is-dismissible nu-gm-is-dismissible';
    $class = implode( ' ', array( $default_class, $notice_class ) );

    printf( '<div class="%1$s" data-nu-gm-admin-notice-id="%2$s">%3$s</div>', $class, $notice_id, $notice_content );
  }
}

// Ajax callback from library/js/nu-gm-dissmissible-admin-notices.js for updating the dismissal status of an admin notice
add_action( 'wp_ajax_nu_gm_dismiss_admin_notice', 'nu_gm_dismiss_admin_notice' );
function nu_gm_dismiss_admin_notice () {
  $user_id              = get_current_user_id();
  $notice_id            = $_POST['notice_id'];
  $admin_notices_status = get_user_meta( $user_id, 'nu_gm_admin_notices_status', true );

  // Initialize admin notices status if empty
  if( empty( $admin_notices_status ) )
    $admin_notices_status = array();

  // Mark this notice as dismissed with the current timestamp
  $admin_notices_status[$notice_id] = time();
  if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
    add_option($notice_id,$admin_notices_status);
    $updated = update_user_meta( $user_id, 'nu_gm_admin_notices_status', $admin_notices_status );
  }

  die();
}

// Helper funtion to determined if a notice has been dismissed by a user
function nu_gm_admin_notice_dismissed_by_user ( $notice_id, $user_id = false ) {
  $user_id = $user_id ?: get_current_user_id();
  if( !$user_id )
    return false;

  // If a timestamp exists for this notice in the users nu_gm_admin_notices_status meta, return true, else return false
  $user_admin_notices_status = get_user_meta( $user_id, 'nu_gm_admin_notices_status', true );
  $dismissed = !empty( $user_admin_notices_status[$notice_id] );

  return $dismissed;
}

// Helper funtion to determined if a notice is still active for a user
function nu_gm_admin_notice_active_for_user ( $notice_id, $user_id = false ) {
  $return = !nu_gm_admin_notice_dismissed_by_user( $notice_id, $user_id );
  return $return;
}


/*************************
VARIOUS HELPER FUNCTIONS
*************************/

// Helper function to return child themes framework directory URI within parent theme
function nu_gm_get_child_themes_directory_uri() {
  return get_template_directory_uri() . '/library/child-themes/';
}

// Helper function to return child themes framework directory path within parent theme
function nu_gm_get_child_themes_directory_path() {
  return get_template_directory() . '/library/child-themes/';
}

// Helper function to return the id of the current page
function nu_gm_get_current_page_id() {
  $request_uri_parts = parse_url($_SERVER['REQUEST_URI']);
  $site_url_parts = parse_url(get_site_url());

  // If the site url is a subdirectory, trim the request path variable appropriately
  if(!empty($site_url_parts['path'])) {
    $request_uri_parts['path'] = substr($request_uri_parts['path'], strlen($site_url_parts['path'])-1);
  }

  $page_slug = substr($request_uri_parts['path'], 1, -1);
  $page = get_page_by_path($page_slug);
  if ($page)
    return (string)$page->ID;
  return false;
}

// Helper funtion to determine if the current page should appear full width
function is_fullwidth() {
  global $wp_query;
  if(
    (is_home() && get_option('show_on_front', 'posts') == 'page' && get_theme_mod('archive_display_sidebar_setting', true) && !is_front_page()) ||
    (is_post_type_archive( 'nu_gm_news' ) && get_theme_mod('nu_gm_news_list_format_setting', 'news-listing') == 'news-listing') ||
    (is_post_type_archive( 'nu_gm_directory_item' ) && in_array(get_theme_mod('nu_gm_directory_item_list_format_setting', 'people-medium'), array('people-big', 'people-medium', 'people-small')))
  ) {
    $return = apply_filters( 'nu_gm_is_fullwidth', false );
    return $return;
  }
  if(
      (!is_active_sidebar( 'sidebar1' ) && !is_single()) ||
      (is_home()) ||
      (is_front_page() && get_page_template_slug(get_option('page_on_front', 0)) == 'page-home.php') ||
      (is_archive() && !get_theme_mod('archive_display_sidebar_setting', true)) ||
      (get_option('show_on_front', 'posts') == 'page' && get_option('page_for_posts', '-') == $wp_query->post->ID)
    ) {
    $return = apply_filters( 'nu_gm_is_fullwidth', true );
    return $return;
  } else {
    $return = apply_filters( 'nu_gm_is_fullwidth', false );
    return $return;
  }
  return $return;
}

// Function to return the URL that should be linked to by the Northwestern logo in the top bar
function nu_gm_top_bar_northwestern_logo_url() {
  $url = apply_filters( 'nu_gm_top_bar_northwestern_logo_url', 'http://www.northwestern.edu/' );
  return esc_url($url);
}

// Function to return image that should be used to represent Northwestern in the top bar
function nu_gm_top_bar_northwestern_logo_img() {
  $img = apply_filters( 'nu_gm_top_bar_northwestern_logo_img', get_template_directory_uri() . '/library/images/northwestern.svg' );
  return $img;
}

// Function to return image that should be used to represent Northwestern in the top bar
function nu_gm_top_bar_northwestern_logo_img_class() {
  return 'northwestern-' . get_theme_mod( 'top_bar_northwestern_logo_img', 'wordmark' );
}

// Apply Secondary Color Palette as Header Bar Background Color
add_action( 'wp_enqueue_scripts', 'nu_gm_header_bar_color_style', 1000 );
function nu_gm_header_bar_color_style() {
  $secondary_color = get_theme_mod( 'secondary_palette_color', 'default' );
  if( $secondary_color != 'default' ) {
    $css   = sprintf(
      'body header, body #top-nav { background: %1$s; }
      @media screen and (max-width: 768px) {
        body .mobile-link.mobile-search-link.open,
        body .search-form      { background-color: %1$s; }
        body .search-form form { background-image: none; position: relative; }
        body .search-form form:after {
          content: " ";
          display: block;
          position: absolute;
          left: 0;
          right: 0;
          bottom: 14px;
          height: 1px;
          background: white;
          background: rgba( 255, 255, 255, .75 );
        }
      }',
      $secondary_color
    );

    $css  .= 'body #search .search-form input::-webkit-input-placeholder        { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #search .search-form input::-moz-placeholder                 { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #search .search-form input:-moz-placeholder                  { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #search .search-form input:-ms-input-placeholder             { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #mobile-search .search-form input::-webkit-input-placeholder { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #mobile-search .search-form input::-moz-placeholder          { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #mobile-search .search-form input:-moz-placeholder           { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #mobile-search .search-form input:-ms-input-placeholder      { color: white; color: rgba( 255, 255, 255, .75 ) }
              body #mobile-search .search-form input, body #mobile-search .search-form button, body #search .search-form input, body #search .search-form button { color: white; border-color: white; border-color: rgba( 255, 255, 255, .75 ); }';

    wp_add_inline_style( 'nu_gm-stylesheet', $css );
  }
}

/* Function to return classes that should be applied to the top nav
function nu_gm_top_nav_classes() {
  $classes     = apply_filters( 'nu_gm_top_nav_classes', array() );
  $classes_str = implode( ' ', $classes );
  return $classes_str;
} */

// Function to return classes that should be applied to the top nav
function nu_gm_top_nav_classes() {
  $classes = apply_filters( 'nu_gm_top_nav_classes', array() );

  if ( is_array( $classes ) ) {
    return implode( ' ', $classes );
  }

  if ( is_string( $classes ) ) {
    return $classes;
  }

  return '';
}

// Function to return image that should be used to represent Northwestern in the footer
function nu_gm_footer_northwestern_logo_img() {
  $img = apply_filters( 'nu_gm_footer_northwestern_logo_img', get_template_directory_uri() . '/library/images/northwestern-university.svg' );
  return $img;
}

// Function to return URL that should be used to link Northwestern logo in the footer
function nu_gm_footer_northwestern_logo_link() {
  $url = apply_filters( 'nu_gm_footer_northwestern_logo_link', 'http://www.northwestern.edu/' );
  return $url;
}

// Function to return default links that should be output in the first column of the footer
function nu_gm_footer_publisher_links_default() {
  $links  = apply_filters(
    'nu_gm_footer_publisher_links_default',
    array(
      array(
        'url'  => 'http://www.northwestern.edu/contact.html',
        'text' => 'Contact Northwestern University',
      ),
      array(
        'url'  => 'http://www.northwestern.edu/hr/careers/',
        'text' => 'Careers',
      ),
      array(
        'url'  => 'http://www.northwestern.edu/emergency/index.html',
        'text' => 'Campus Emergency Information',
      ),
      array(
        'url'  => 'http://policies.northwestern.edu/',
        'text' => 'University Policies',
      ),
    )
  );
  return $links;
}

// Function to return links that should be output in the first column of the footer
function nu_gm_footer_publisher_links() {
  $links  = apply_filters(
    'nu_gm_footer_publisher_links',
    nu_gm_footer_publisher_links_default()
  );
  return $links;
}

// Function to return links that should be output in the first column of the footer
function nu_gm_footer_publisher_links_markup() {
  $output = '';
  $links = nu_gm_footer_publisher_links();
  if( !empty( $links ) ) {
    $output = implode(
      '',
      array_map(
        function( $link ) {
          if( empty( $link['url'] ) || empty( $link['text'] ) )
            return '';
          $output = sprintf(
            '<li><a href="%s">%s</a></li>',
            $link['url'],
            $link['text']
          );
          return $output;
        },
        $links
      )
    );
  }

  return $output;
}

// Function to retrieve content via filter to be pasted in bottom of footer
function nu_gm_footer_bottom() {
  $content = apply_filters( 'nu_gm_footer_bottom', '' );
  $content = $content ? '<div id="footer-bottom" class="contain-970">' . $content . '</div>' : '';
  return $content;
}

// Helper function to determine whether the homepage is dynamic
function is_homepage_dynamic() {
  return (get_option('show_on_front', 'posts') == 'posts' || (get_option('show_on_front', 'posts') == 'page' && get_page_template_slug(get_option('page_on_front', 0)) == 'page-home.php'));
}

function nu_gm_get_current_url() {
  $url = esc_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
  return $url;
}

function nu_gm_is_homepage_static() {
  return (get_option('show_on_front', 'posts') == 'page');
}

// Helper function to determine if posts are shown on homepage
function nugm_show_posts_on_front() {
  return get_option('show_on_front', 'posts') == 'posts';
}

// Helper function to determine if homepage hero banner should be visible
function homepage_hero_banner_visible() {
  return get_theme_mod('homepage_hero_banner_visible_setting', true);
}

// Helper function to return an array of supported social media links for footer
function get_supported_social_media() {
  return array(
    'Facebook',
    'Twitter',
    'LinkedIn',
    'YouTube',
    'GitHub',
    'WordPress',
    'Instagram',
    'Flickr',
    'Tumblr',
    'Futurity',
    'Vimeo',
    'Google Plus',
    'Google Groups',
    'Blog',
    'Storify',
    'Pinterest',
  );
}

// Helper function to provide post format wrappers to templates
function nu_gm_post_format_wrapper($key, $type = 'post', $attr = "") {
  $format_info = array(
    'feature-box' => array(
      'start' => '<div class="feature-'.(is_fullwidth() ? 'three' : 'two').'-col" '.$attr.'>',
      'end' => '</div>',
    ),
    'fractal-box' => array(
      'start' => '<div class="feature-'.(is_fullwidth() ? 'three' : 'two').'-col" '.$attr.'>',
      'end' => '</div>',
    ),
    'photo-feature' => array(
      'start' => '<div class="photo-feature-'.(is_fullwidth() ? '3' : '2').'-across" '.$attr.'>',
      'end' => '</div>',
    ),
    'standard' => array(
      'start' => '',
      'end' => '',
    ),
    'news-listing' => array(
      'start' => '',
      'end' => '',
    ),
    'people-big' => array(
      'start' => '',
      'end' => '',
    ),
    'people-medium' => array(
      'start' => '',
      'end' => '',
    ),
    'people-small' => array(
      'start' => '',
      'end' => '',
    ),
  );
  return $format_info[get_theme_mod($type.'_list_format_setting', 'standard')][$key];
}

// Helper function to provide schema.org mappings for various post types
function nu_gm_schema() {
  global $post;
  switch ($post->post_type) {
    case 'post':
      $return = 'http://schema.org/BlogPosting';
      break;
    case 'nu_gm_directory_item':
      $return = 'http://schema.org/Person';
      break;
    case 'nu_gm_news':
      $return = 'http://schema.org/NewsArticle';
      break;
    case 'nu_gm_event':
      $return = 'http://schema.org/Event';
      break;
    default:
      $return = 'http://schema.org/CreativeWork';
      break;
  }

  $return = apply_filters( 'nu_gm_schema_type', $return );
  return $return;
}

// Helper function to return the current index position of an item within the loop
function nu_gm_get_the_loop_index() {
  if ( empty($wp_query) ) {
    global $wp_query;
  }
  if (  isset( $wp_query->current_post ) && 
        ( 
          !empty( $wp_query->current_post ) || 
          $wp_query->current_post === 0
        )
  ) {
    $position = $wp_query->current_post + 1;
    return '<meta itemprop="position" content="'.$position.'" hidden />';
  }
  return '';
}

// Helper function to trim excerpts
function gm_custom_excerpt($limit = 20) {
  $excerpt = preg_replace('/<!--(.|\s)*?-->/', '', get_the_excerpt());
  $excerpt = explode(' ', $excerpt, $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  }
  $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  $excerpt = force_balance_tags($excerpt);
  return $excerpt;
}

// Add filter hook to enable child themes to insert content into singular post headers
function nu_gm_singular_header() {
  $post_type = get_post_type();
  $output = '';
  $output = apply_filters( 'nu_gm_singular_header', $output, $post_type );
  if(!empty($output)) {
    echo $output;
  }
}

// Add filter hook to enable child themes to insert content into singular post footers
function nu_gm_singular_footer() {
  $post_type = get_post_type();
  $output = '';
  $output = apply_filters( 'nu_gm_singular_footer', $output, $post_type );
  if(!empty($output)) {
    echo $output;
  }
}

// Add filter hook to enable child themes to insert content into singular post footers
function nu_gm_after_main_content() {
  $post_type = get_post_type();
  do_action( 'nu_gm_after_main_content', $post_type );
}

// Add filter hook to enable child themes to insert content into archive headers
function nu_gm_archive_header() {
  $output = '';
  $output = apply_filters( 'nu_gm_archive_header', $output );
  if(!empty($output)) {
    echo $output;
  }
}

// Add filter hook to enable child themes to insert content into archive footers
function nu_gm_archive_footer() {
  $output = '';
  $output = apply_filters( 'nu_gm_archive_footer', $output );
  if(!empty($output)) {
    echo $output;
  }
}

// Helper function to return attachment ID from source URL
// See https://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
function nu_gm_get_attachment_id_from_src($attachment_url) {
  global $wpdb;

  $attachment_id = false;
  $upload_dir_paths = wp_upload_dir();

  $attachment_url_no_proto = preg_replace('/^https?:\/\/(.*)$/i', "$1", $attachment_url);
  $upload_dir_url_no_proto = preg_replace('/^https?:\/\/(.*)$/i', "$1", $upload_dir_paths['baseurl']);

  // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
  if ( false !== strpos( $attachment_url_no_proto, $upload_dir_url_no_proto ) ) {

    // Remove the upload path base directory from the attachment URL
    $attachment_path_raw = str_replace( $upload_dir_url_no_proto . '/', '', $attachment_url_no_proto );

    // If this is the URL of an auto-generated thumbnail, get the URL of the original image
    $attachment_path_filtered = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_path_raw );

    // Build meta query for finding attachments matching naming convention
    $meta_query = array(
      array(
        'key'   => '_wp_attached_file',
        'value' => $attachment_path_filtered,
      )
    );

    // In case the original uploaded file was named with a *-[width]x[height].[extension] naming convention, make sure it is still found
    if( $attachment_path_filtered != $attachment_path_raw ) {
      $meta_query = array(
        'relation' => 'OR',
        array(
          'key'   => '_wp_attached_file',
          'value' => $attachment_path_raw,
        )
      ) + $meta_query;
    }

    // Get the first attachment to match
    $attachments = get_posts(array(
      'post_type'   => 'attachment',
      'meta_query'  => $meta_query,
      'numberposts' => 1
    ));

    if(!empty($attachments))
      $attachment_id = $attachments[0]->ID;

    if( !is_numeric( $attachment_id ) || empty($attachment_id) )
      $attachment_id = false;
  }

  return $attachment_id;
}

// Helper function to insert $data before a specific key in an associative array
function nu_gm_array_insert_before_key($array, $key, $data = null) {
  if (($offset = array_search($key, array_keys($array))) === false) {
    $offset = 0; // should we prepend $array with $data?
    $offset = count($array); // or should we append $array with $data? lets pick this one...
  }

  return array_merge(array_slice($array, 0, $offset), (array) $data, array_slice($array, $offset));
}

// Add hook to enable child themes & customizer to define archive banners
/**
TODO: Add customizer fields for setting titles and subtitles of various post type archives
*/
function nu_gm_get_dynamic_banner() {
  $queried_object  = get_queried_object();
  $return          = false;
  $banner_image_id = false;

  if(is_archive()) {
    if(!empty($queried_object->query_var)) {
      $banner_image_src = get_theme_mod($queried_object->query_var.'_list_hero_img_setting', '');
      if(!empty($banner_image_src)) {
        $banner_image_id   = nu_gm_get_attachment_id_from_src($banner_image_src);
        if($banner_image_id) {
          $image_format    = is_fullwidth() ? 'hero-landing' : 'hero-standard';
          $return          = array();
          $return['image'] = wp_get_attachment_image_src( $banner_image_id, $image_format)[0];
          $return['title'] = get_the_archive_title();
        }
      }
    }
  } elseif (is_home()) {
    $banner_image_src = get_theme_mod('post_list_hero_img_setting', '');
    if(!empty($banner_image_src)) {
      $banner_image_id   = nu_gm_get_attachment_id_from_src($banner_image_src);
      if($banner_image_id) {
        $image_format    = is_fullwidth() ? 'hero-landing' : 'hero-standard';
        $return          = array();
        $return['image'] = wp_get_attachment_image_src( $banner_image_id, $image_format)[0];
        $return['title'] = 'Blog';
      }
    }
  }

  $return = apply_filters( 'nu_gm_dynamic_banner', $return, $banner_image_id );
  return $return;
}

// Prevent rwmb meta from returning undefined
if ( ! function_exists( 'rwmb_meta' ) ) {
  function rwmb_meta( $key, $args = '', $post_id = null ) {
    return false;
  }
}

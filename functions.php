<?php

// LOAD NU_GM CORE (if you remove this, the theme will break)
require_once( dirname( __FILE__ ) . '/library/nu_gm.php' );

/*********************
LAUNCH NU_GM
Let's get everything up and running.
*********************/

function nu_gm_setup() {

  // Add editor style.
  add_editor_style( get_template_directory_uri() . '/library/css/editor-style.css' );

  // launching operation cleanup
  add_action( 'init', 'nu_gm_cleanup' );

  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );

  // remove WP version from RSS
  add_filter( 'the_generator', 'nu_gm_rss_version' );

  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'nu_gm_remove_wp_widget_recent_comments_style', 1 );

  // clean up comment styles in the head
  add_action( 'wp_head', 'nu_gm_remove_recent_comments_style', 1 );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'nu_gm_scripts_and_styles', 999 );

  // enqueue admin scripts and styles
  add_action( 'admin_enqueue_scripts', 'nu_gm_admin_scripts_and_styles', 999 );

  // launching this stuff after theme setup
  nu_gm_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'nu_gm_register_sidebars' );

  // clean up gallery output in wp
  add_filter( 'gallery_style', 'nu_gm_gallery_style' );

  // cleaning up random code around images
  add_filter( 'the_content', 'nu_gm_filter_ptags_on_images' );

  // cleaning up excerpt
  add_filter( 'excerpt_more', 'nu_gm_excerpt_more' );

  // update display of category links to include rich data
  add_filter('the_category', 'nu_gm_category_microdata', 10, 3);

  // update display of tag links to include rich data
  add_filter('the_tags', 'nu_gm_tag_microdata', 10, 5);

}

// let's get this party started
add_action( 'after_setup_theme', 'nu_gm_setup' );



/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
  $content_width = 680;
}


/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetized Areas
function nu_gm_register_sidebars() {
  register_sidebar(array(
    'id' => 'sidebar1',
    'name' => __( 'Sidebar', 'nu_gm' ),
    'description' => __( 'The sidebar.', 'nu_gm' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h5 class="widgettitle">',
    'after_title' => '</h5>',
  ));
  register_sidebar(array(
    'id' => 'homepage',
    'name' => __( 'Homepage Widgets', 'nu_gm' ),
    'description' => __( 'The homepage widgets.', 'nu_gm' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="widgettitle">',
    'after_title' => '</h2>',
  ));
}


/************* COMMENT LAYOUT *********************/

// Comment Layout
function nu_gm_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?> itemprop="comment" itemscope itemtype="http://schema.org/Comment">
    <article  class="cf text">
      <div class="comment-inner">
        <div class="comment-author vcard">
          <?php printf(__( '<cite class="fn"><span>Posted by</span> <em>%1$s</em></cite>', 'nu_gm' ), get_comment_author_link()) ?> on <time datetime="<?php echo comment_time('Y-m-j'); ?>"><?php comment_time(__( 'F jS, Y', 'nu_gm' )); ?></time>
        </div>
        <div>
          <?php if ($comment->comment_approved == '0') : ?>
            <div class="alert alert-info">
              <p><?php _e( 'Your comment is awaiting moderation.', 'nu_gm' ) ?></p>
            </div>
          <?php endif; ?>
          <div class="comment_content cf" itemprop="text">
            <?php comment_text() ?>
          </div>
        </div>
      </div>
      <div class="comment-actions">
        <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
        <?php edit_comment_link(__( 'Edit', 'nu_gm' ),'  ','') ?>
      </div>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
}


/************* BEADCRUMB SUPPORT ******************/

function nu_gm_breadcrumbs() {
  if(get_theme_mod('show_breadcrumbs_setting', false)) {
    // Settings
    $separator = '&gt;';
    $breadcrums_id = 'breadcrumbs';
    $breadcrums_class = 'breadcrumbs';
    $home_title = 'Home';
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy = 'nu_gm_project_category';
    // Get the query & post information
    global $post,$wp_query;
    // Do not display on the homepage
    if ( !is_front_page() ) {
      $position = 1;
      // Build the breadcrums
      echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '" itemscope itemtype="http://schema.org/BreadcrumbList">';
      // Home page
      echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
      if ( is_archive() && !is_tax() && !is_category() && !is_tag() && !is_author() ) {
        echo '<li class="item-current item-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
      } else if ( is_archive() && is_tax() && !is_category() && !is_tag() && !is_author() ) {
        // If post is a custom post type
        $post_type = get_post_type();
        // If it is a custom post type display name and link
        if($post_type != 'post') {
          $post_type_object = get_post_type_object($post_type);
          $post_type_archive = get_post_type_archive_link($post_type);
          echo '<li class="item-cat item-custom-post-type-' . $post_type . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a itemprop="item" class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
        }
        $custom_tax_name = get_queried_object()->name;
        echo '<li class="item-current item-archive" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
      } else if ( is_single() ) {
        // If post is a custom post type
        $post_type = get_post_type();
        // If it is a custom post type display name and link
        if($post_type != 'post') {
          $post_type_object = get_post_type_object($post_type);
          $post_type_archive = get_post_type_archive_link($post_type);
          echo '<li class="item-cat item-custom-post-type-' . $post_type . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a itemprop="item" class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
        }
        // Get post category info
        $category = get_the_category();
        if(!empty($category)) {
          // Get last category post is in
          $last_category = end(array_values($category));
          // Get parent any categories and create array
          $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
          $cat_parents = explode(',',$get_cat_parents);
          // Loop through parent categories and store in variable $cat_display
          $cat_display = '';
          foreach($cat_parents as $index => $parents) {
            $cat_display .= '<li class="item-cat" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><span itemprop="item">'.$parents.'</span></li>';
          }
        }
        // If it's a custom post type within a custom taxonomy
        $taxonomy_exists = taxonomy_exists($custom_taxonomy);
        if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
          $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
          $cat_id = $taxonomy_terms[0]->term_id;
          $cat_nicename = $taxonomy_terms[0]->slug;
          $cat_link = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
          $cat_name = $taxonomy_terms[0]->name;  
        }
        // Check if the post is in a category
        if(!empty($last_category)) {
          echo $cat_display;
          echo '<li class="item-current item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
        // Else if post is in a custom taxonomy
        } else if(!empty($cat_id)) {
          echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a itemprop="item" class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
          echo '<li class="item-current item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
        } else {
          echo '<li class="item-current item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';   
        }
      } else if ( is_category() ) {
        // Category page
        echo '<li class="item-current item-cat" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';
      } else if ( is_page() ) {
        // Standard page
        if( $post->post_parent ){
          // If child page, get parents 
          $anc = get_post_ancestors( $post->ID );
          // Get parents in the right order
          $anc = array_reverse($anc);  
          // Parent page loop
          foreach ( $anc as $ancestor ) {
            $parents .= '<li class="item-parent item-parent-' . $ancestor . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a itemprop="item" class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
          }
          // Display parent pages
          echo $parents;
          // Current page
          echo '<li class="item-current item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';
        } else {
          // Just display current page if not parents
          echo '<li class="item-current item-' . $post->ID . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';    
        }
      } else if ( is_tag() ) {
        // Tag page
        // Get tag information
        $term_id = get_query_var('tag_id');
        $taxonomy = 'post_tag';
        $args = 'include=' . $term_id;
        $terms = get_terms( $taxonomy, $args );
        $get_term_id = $terms[0]->term_id;
        $get_term_slug = $terms[0]->slug;
        $get_term_name = $terms[0]->name;
        // Display the tag name
        echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
      }  else if ( is_home() ) {
        // Display the home name
        echo '<li class="item-current item-home" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-home">Blog</strong></li>';
      } elseif ( is_day() ) {
        // Day archive
        // Year link
        echo '<li class="item-year item-year-' . get_the_time('Y') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a itemprop="item" class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
        // Month link
        echo '<li class="item-month item-month-' . get_the_time('m') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a itemprop="item" class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
        // Day display
        echo '<li class="item-current item-' . get_the_time('j') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
      } else if ( is_month() ) {
        // Month Archive
        // Year link
        echo '<li class="item-year item-year-' . get_the_time('Y') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a itemprop="item" class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
        // Month display
        echo '<li class="item-month item-month-' . get_the_time('m') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';
      } else if ( is_year() ) {
        // Display year archive
        echo '<li class="item-current item-current-' . get_the_time('Y') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';
      } else if ( is_author() ) {
        // Auhor archive
        // Get the author information
        global $author;
        $userdata = get_userdata( $author );
        $directory_query = new WP_Query( array(
          'post_type'  => 'nu_gm_directory_item',
          'meta_key'   => 'nu_gm_wp_user',
          'meta_value' => $author,
        ));
        if($directory_query->have_posts()) {
          $directory_object = get_post_type_object('nu_gm_directory_item');
          $directory_title = apply_filters( 'post_type_archive_title', $directory_object->labels->name, 'nu_gm_directory_item' );
          $directory_url = home_url('/'.$directory_object->rewrite['slug']);
          echo '<li class="item-parent item-parent-directory" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><a href="' . $directory_url . '" itemprop="item" class="bread-parent bread-parent-directory">' . $directory_title . '</a></li>';
        }
        // Display author name
        echo '<li class="item-current item-current-' . $userdata->user_nicename . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . $userdata->display_name . '</strong></li>';
      } else if ( get_query_var('paged') ) {
        // Paginated archives
        echo '<li class="item-current item-current-' . get_query_var('paged') . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page', 'nu_gm') . ' ' . get_query_var('paged') . '</strong></li>';
      } else if ( is_search() ) {
        // Search results page
        echo '<li class="item-current item-current-' . get_search_query() . '" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><meta itemprop="position" content="'.($position++).'" hidden /><strong itemprop="item" class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';
      } elseif ( is_404() ) {
        // 404 page
        echo '<li>' . 'Error 404' . '</li>';
      }
      echo '</ul>';
    }
  }
}

/* DON'T DELETE THIS CLOSING TAG */ ?>

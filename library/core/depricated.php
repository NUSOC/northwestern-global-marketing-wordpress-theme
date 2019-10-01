<?php

/************* DEPRICATED FUNCTIONS & HOOKS ******************/

// Previously returned PlanItPurple for Shortcode, now returns empty
// Abandoned in favor of PlanItPurple widget
function planitpurple_shortcode( $atts ) {
  return '';
}
add_shortcode( 'planitpurple', 'planitpurple_shortcode' );

// Check if using deprecated "landing" homepage style and update to "posts" on theme switch
function nu_gm_setup_options () {
  if(get_option('show_on_front') == 'landing')
    set_option('show_on_front', 'posts');
}
add_action('switch_theme', 'nu_gm_setup_options');

// Check if using deprecated "landing" homepage style and update to new static style if needed
function nu_gm_deprecate_homepage_landing_option(){
  // Check if using deprecated "landing" homepage style
  if(get_option('show_on_front', 'posts') == 'landing') {
    // Create default homepage to be used in statiic page style
    $post_args = array(
      'post_type'     => 'page',
      'post_title'    => 'Home',
      'post_status'   => 'publish',
      'post_author'   => 1,
      'meta_input'    => array(
        '_wp_page_template' => 'page-home.php',
      ),
    );
    $homepage_id = wp_insert_post( $post_args );

    // If homepage was successfully created, update theme options accordingly
    if($homepage_id) {
      // Set homepage to use static front page option
      update_option( 'show_on_front', 'page' );

      // Set static front page to our newly created homepage
      update_option( 'page_on_front', $homepage_id );

      // Force reload of page with new settings in place
      global $wp;
      $current_url = home_url(add_query_arg(array(),$wp->request));
      if(wp_redirect($current_url))
        exit;
    }
  }
}
add_action('init', 'nu_gm_deprecate_homepage_landing_option');
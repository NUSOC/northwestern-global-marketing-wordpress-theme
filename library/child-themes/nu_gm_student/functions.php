<?php

// Enqueue stylesheets
add_action( 'wp_enqueue_scripts', 'nu_gm_student_scripts_and_styles' );
function nu_gm_student_scripts_and_styles() {
  wp_enqueue_style( 'nu_gm_student-style', nu_gm_get_child_themes_directory_uri() . '/' .basename(__DIR__) . '/css/style.css' );
}

// Filter top bar logo theme mod to always return 'n' and display the Northwestern N
add_filter( 'theme_mod_top_bar_northwestern_logo_img', 'nu_gm_student_mod_top_bar_northwestern_logo_img', 30);
function nu_gm_student_mod_top_bar_northwestern_logo_img( $setting ) {
  return 'n';
}

// Replace Northwestern wordmark with the Student Affairs lockup in the footer
add_filter( 'nu_gm_footer_northwestern_logo_img', 'nu_gm_student_footer_northwestern_logo_img', 30 );
function nu_gm_student_footer_northwestern_logo_img( $img ) {
  $img = nu_gm_get_child_themes_directory_uri() . '/' .basename(__DIR__) . '/images/northwestern-student-affairs-lockup-white.svg';
  return $img;
}

// Replace Northwestern wordmark link with the Student Affairs lockup link in the footer
add_filter( 'nu_gm_footer_northwestern_logo_link', 'nu_gm_student_footer_northwestern_logo_link', 30 );
function nu_gm_student_footer_northwestern_logo_link( $url ) {
  $url = 'http://www.northwestern.edu/studentaffairs/';
  return $url;
}

// Add disclaimer text to footer
add_filter( 'nu_gm_footer_bottom', 'nu_gm_student_footer_bottom' );
function nu_gm_student_footer_bottom( $content ) {
  $disclaimer_text = __( 'Northwestern University is not responsible for the content of this site. If you have questions about any Northwestern information on this site, please contact the originating department directly.', 'nu_gm_light' );
  $content .= sprintf(
    '<div id="nu-gm-student-disclaimer"><hr class="footer-disclaimer-divider"><p><em>%s</em></p></div>',
    $disclaimer_text
  );
  return $content;
}

// Replace footer links under the Student Affairs lockup with custom links
add_filter( 'nu_gm_footer_publisher_links_default', 'nu_gm_student_footer_publisher_links_default', 30 );
function nu_gm_student_footer_publisher_links_default( $links ) {
  $links = array(
    array(
      'url'  => 'http://www.northwestern.edu/studentaffairs/',
      'text' => 'Northwestern Student Affairs',
    ),
  );
  return $links;
}

// Add customizer modifications
add_action( 'customize_register', 'nu_gm_student_theme_customizer', 9000030 );
function nu_gm_student_theme_customizer ($wp_customize) {
  // Remove Selection of Global Header Logo (N vs Wordmark)
  $wp_customize->remove_control('top_bar_northwestern_logo_img');
}

?>

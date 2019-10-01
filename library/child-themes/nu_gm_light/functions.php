<?php

// Enqueue stylesheets
add_action( 'wp_enqueue_scripts', 'nu_gm_light_scripts_and_styles' );
function nu_gm_light_scripts_and_styles() {
  wp_enqueue_style( 'nu_gm_light-style', nu_gm_get_child_themes_directory_uri() . '/' . basename(__DIR__) . '/css/style.css' );
}

// Filter top nav classes to add narrow-dropdown class for narrow menus
add_filter( 'nu_gm_top_nav_classes', 'nu_gm_light_top_nav_classes' );
function nu_gm_light_top_nav_classes( $classes ) {
  $classes[] = 'narrow-dropdown';
  return $classes;
}

// Filter top nav logo
add_filter( 'nu_gm_top_bar_northwestern_logo_img', 'nu_gm_light_top_bar_northwestern_logo_img', 20 );
function nu_gm_light_top_bar_northwestern_logo_img( $img ) {
  $img_theme_path = get_theme_mod( 'top_bar_northwestern_logo_img', 'wordmark' ) == 'wordmark' ? '/library/images/northwestern.svg' : '/library/images/northwestern-n-white.svg';
  $img = get_template_directory_uri() . $img_theme_path;
  return $img;
}

// Enable customized footer links
add_filter( 'nu_gm_footer_publisher_links', 'nu_gm_light_footer_publisher_links', 20 );
function nu_gm_light_footer_publisher_links( $links ) {
  $custom_links = array();
  foreach ($links as $link_key => $link) {
    $custom_link_text = get_theme_mod( 'nu_gm_footer_publisher_links_'.$link_key.'_text', false );
    if( !empty( $custom_link_text ) ) {
      $custom_links[$link_key]['text'] = $custom_link_text;
    } elseif( $custom_link_text === false ) {
      $custom_links[$link_key]['text'] = $links[$link_key]['text'];
    }

    $custom_link_url = get_theme_mod( 'nu_gm_footer_publisher_links_'.$link_key.'_url', false );
    if( !empty( $custom_link_url ) ) {
      $custom_links[$link_key]['url'] = $custom_link_url;
    } elseif( $custom_link_url === false ) {
      $custom_links[$link_key]['url'] = $links[$link_key]['url'];
    }
  }

  if( !empty( $custom_links ) )
    $links = $custom_links;

  return $links;
}

// Add customizer modifications
add_action( 'customize_register', 'nu_gm_light_theme_customizer', 9000020 );
function nu_gm_light_theme_customizer ($wp_customize) {
  // Selection of Global Header Logo (N vs Wordmark)
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'top_bar_northwestern_logo_img',
      array(
        'label'      => 'Top Bar Logo',
        'section'    => 'title_tagline',
        'settings'   => 'top_bar_northwestern_logo_img',
        'type'       => 'radio',
        'choices'    => array(
          'wordmark'   => __( 'Northwestern Wordmark', 'nu_gm' ),
          'n'          => __( 'Northwestern N', 'nu_gm' ),
        ),
      )
    )
  );

  // Selection of Secondary Palette Color
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'secondary_palette_color',
      array(
        'label'       => 'Secondary Palette Color',
        'description' => 'This color will be used as the background color for the bottom header bar.',
        'section'     => 'title_tagline',
        'type'        => 'radio',
        'choices'     => secondary_palette_color_choices(),
        'settings'    => 'secondary_palette_color',
      )
    )
  );

  // Footer First Column Links
  $wp_customize->add_section('footer_publisher_links', array(
    'title'    => 'Column #1 Links',
    'panel'    => 'footer_management',
    'priority' => 10,
  ));
  $footer_publisher_links_empty = true;
  foreach( nu_gm_footer_publisher_links_default() as $link_key => $link ) {
    if( empty( $link ) || empty( $link['url'] ) || empty( $link['text'] ) )
      continue;

    // Add visual divider
    if( !$footer_publisher_links_empty ) {
      $wp_customize->add_control(
        new NUGM_Customize_Misc_Control(
          $wp_customize,
          'footer_publisher_links_divider_'.$link_key,
          array(
            'section' => 'footer_publisher_links',
            'type' => 'markup',
            'description' => '<hr>',
            'priority' => $link_key * 3
          )
        )
      );
    }

    // Link Text
    $wp_customize->add_setting( 'nu_gm_footer_publisher_links_'.$link_key.'_text' , array(
      'default' => $link['text'],
      'sanitize_callback' => 'nu_gm_sanitize_text_field',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'nu_gm_footer_publisher_links_'.$link_key.'_text',
        array(
          'label'    => 'Footer Link #'.($link_key + 1).' Text',
          'section'  => 'footer_publisher_links',
          'type'     => 'text',
          'settings' => 'nu_gm_footer_publisher_links_'.$link_key.'_text',
          'priority' => ($link_key * 3) + 1,
        )
      )
    );

    // Link URL
    $wp_customize->add_setting( 'nu_gm_footer_publisher_links_'.$link_key.'_url' , array(
      'default' => $link['url'],
      'validate_callback' => 'nu_gm_validate_url',
      'sanitize_callback' => 'nu_gm_sanitize_url',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'nu_gm_footer_publisher_links_'.$link_key.'_url',
        array(
          'label'    => 'Footer Link #'.($link_key + 1).' URL',
          'section'  => 'footer_publisher_links',
          'type'     => 'url',
          'settings' => 'nu_gm_footer_publisher_links_'.$link_key.'_url',
          'priority' => ($link_key * 3) + 2,
        )
      )
    );

    $footer_publisher_links_empty = false;
  }
}

?>

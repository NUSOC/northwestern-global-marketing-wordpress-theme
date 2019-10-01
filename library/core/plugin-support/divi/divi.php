<?php

/*******************************
  Module Registration
 *******************************/

// Import custom GM Divi components
add_action('et_builder_ready', 'nu_gm_divi_register_builder_components');
function nu_gm_divi_register_builder_components() {
  // Import custom modules
  if ( class_exists('ET_Builder_Module') ) {
    $nu_gm_divi_module_classes = nu_gm_divi_module_classes();
    $nu_gm_divi_module_slugs   = array();

    foreach ( $nu_gm_divi_module_classes as $module_class ) {
      if( !class_exists( $module_class ) ) {
        require_once( dirname( __FILE__ ) . '/classes/'.$module_class.'.class.php' );
        $nu_gm_divi_module_slugs[] = $module_class::get_module_slug();
      }
    }

    // Make sure Divi role settings are in place initially
    if( empty( get_option( 'et_pb_role_settings' ) ) )
      update_option( 'et_pb_role_settings', nu_gm_get_divi_initial_role_settings( $nu_gm_divi_module_slugs ), 'yes' );
  }
}

/*******************************
  Front-End Style Functionality
 *******************************/

// Add body class landing-page for all divi-powered pages
add_filter( 'body_class', 'nu_gm_et_builder_body_class', 1000, 1 );
function nu_gm_et_builder_body_class ( $classes ) {
  $post = get_post( get_queried_object_id() );
  if( !empty( $post->post_content ) && preg_match( '/\[et_pb_section\s/', $post->post_content ) ) {
    $classes[] = 'landing-page';
    $classes[] = 'nu-gm-divi-page';
    if( $standard_page_key = array_search( 'standard-page', $classes ) )
      unset( $classes[$standard_page_key] );
  }
  return $classes;
}

// Add landing-page class to Divi wrapper
add_filter( 'et_builder_inner_content_class', 'nu_gm_et_builder_inner_content_class' );
function nu_gm_et_builder_inner_content_class ( $class ) {
  $class[] = 'landing-page';
  return $class;
}

// Replace Divi outer content html ID to prevent inheriting Divi styles
add_filter( 'et_builder_outer_content_id', 'nu_gm_et_builder_outer_content_id' );
function nu_gm_et_builder_outer_content_id ( $id = 'et_builder_outer_content' ) {
  return 'nu_gm_'.$id;
}

// Add page-divi.php to top of template hierarchy
add_filter( 'page_template_hierarchy', 'nu_gm_divi_singular_template_hierarchy', 1000, 1 );
function nu_gm_divi_singular_template_hierarchy ( $templates ) {
  $post = get_post( get_queried_object_id() );
  if( nu_gm_divi_is_divi_page( $post ) ) {
    array_unshift( $templates, 'page-divi.php' );
  }
  return $templates;
}

// Force fullwidth for all Divi pages
add_filter( 'nu_gm_is_fullwidth', 'nu_gm_divi_is_fullwidth', 1000, 1 );
function nu_gm_divi_is_fullwidth( $is_fullwidth ) {
  if( nu_gm_divi_is_divi_page() ) {
    $is_fullwidth = true;
  }
  return $is_fullwidth;
}

/*******************************
  Administrative Functionality
 *******************************/

// Disable Divi Builder for posts
add_filter( 'et_builder_post_types', 'nu_gm_et_builder_post_types', 10, 1);
function nu_gm_et_builder_post_types ( $post_types ) {
  if( $post_key = array_search( 'post', $post_types ) )
    unset( $post_types[$post_key] );
  return $post_types;
}

// Rename "fullwidth section" to "section"
add_filter( 'et_builder_add_fullwidth_section_button', function($et_builder_fullwidth_section_button) {
  return str_replace('Fullwidth', '', $et_builder_fullwidth_section_button);
});

// Remove standard & specialty section options
add_filter( 'et_builder_add_specialty_section_button', function($add_specialty_section_button) { return ''; });
add_filter( 'et_builder_add_main_section_button', function($et_builder_add_main_section_button) { return ''; });

// Enqueue custom admin scripts and styles for Divi components
add_action( 'admin_enqueue_scripts', 'nu_gm_divi_admin_scripts_and_styles', 1000 );
function nu_gm_divi_admin_scripts_and_styles() {
  global $typenow;

  $et_builder_post_types = function_exists( 'et_builder_get_builder_post_types' ) ? et_builder_get_builder_post_types() : array( 'page', 'project', 'et_pb_layout' );

  // Load Divi JS customizations
  if ( isset( $typenow ) && in_array( $typenow, $et_builder_post_types ) ) {
    wp_enqueue_script( 'gm-divi-backend', get_template_directory_uri() . '/library/core/plugin-support/divi/js/divi-backend.js', array( 'jquery', 'jquery-ui-core', 'underscore', 'backbone', 'et_pb_admin_js' ), '', true  );
  }

  // Load Divi admin style sheet
  wp_enqueue_style( 'nu_gm_divi_admin', get_template_directory_uri() . '/library/core/plugin-support/divi/css/divi-admin.css' );
}

// Forcibly remove all Divi page-level settings
add_filter( 'et_pb_get_builder_settings_configurations', 'nu_gm_et_pb_get_builder_settings_configurations' );
function nu_gm_et_pb_get_builder_settings_configurations ( $settings ) {
  return array();
}

// Forcibly hide all Divi section-level settings
add_filter( 'et_builder_module_fields_et_pb_section', 'nu_gm_et_builder_module_fields_et_pb_section' );
function nu_gm_et_builder_module_fields_et_pb_section ( $fields ) {
  foreach ($fields as $key => $field) {
    $fields[$key]['type'] = 'hidden';
  }
  return  $fields;
}

// Remove Divi options and role editor admin menu items
add_action( 'admin_menu', 'nu_gm_divi_remove_menu_items', 1000, 0 );
function nu_gm_divi_remove_menu_items(){
  if ( is_multisite() && !is_super_admin() ) {
    remove_menu_page( 'et_divi_options' );
    remove_submenu_page( 'et_divi_options', 'et_divi_role_editor' );
    remove_submenu_page( 'et_divi_options', 'et_divi_options' );
  }
}

// Override Divi roles & capabilities option to only allow GM modules
add_filter( 'option_et_pb_role_settings', 'nu_gm_divi_role_settings', 1000, 1 );
function nu_gm_divi_role_settings ( $et_pb_role_settings ) {
  foreach ( $et_pb_role_settings as $role => $settings ) {
    foreach( $settings as $setting_key => $setting_value ) {
      if( preg_match( '/^et_pb_nu_gm_/', $setting_key ) ) {
        $et_pb_role_settings[$role][$setting_key] = "on";
      } else if ( preg_match( '/^et_pb_/', $setting_key ) ) {
        $et_pb_role_settings[$role][$setting_key] = "off";
      } else if ( preg_match( '/^(?:add|edit_global|save|edit|load)_(?:library|layout)$/', $setting_key ) ) {
        $et_pb_role_settings[$role][$setting_key] = "off";
      }
    }
  }
  return $et_pb_role_settings;
}

/*******************************
  Helper Functions
 *******************************/

// Helper function to return list of GM module classes
function nu_gm_divi_module_classes() {
  $nu_gm_divi_module_classes = array(
    'ET_Builder_Module_NU_GM_Recent_Posts',
    'ET_Builder_Module_NU_GM_Photo_Feature',
    'ET_Builder_Module_NU_GM_Photo_Feature_Item',
    'ET_Builder_Module_NU_GM_Feature_Box',
    'ET_Builder_Module_NU_GM_Feature_Box_Item',
    'ET_Builder_Module_NU_GM_Photo_Grid',
    'ET_Builder_Module_NU_GM_Photo_Grid_Item',
    'ET_Builder_Module_NU_GM_Alternate_Float',
    'ET_Builder_Module_NU_GM_Alternate_Float_Item',
    'ET_Builder_Module_NU_GM_Tabs',
    'ET_Builder_Module_NU_GM_Tabs_Item',
    'ET_Builder_Module_NU_GM_Accordion',
    'ET_Builder_Module_NU_GM_Accordion_Item',
    'ET_Builder_Module_NU_GM_Text',
    'ET_Builder_Module_NU_GM_Statistics_Callout',
    'ET_Builder_Module_NU_GM_Events_PlanItPurple',
    'ET_Builder_Module_NU_GM_Hero_Banner',
    'ET_Builder_Module_NU_GM_Curated_Posts',
    'ET_Builder_Module_NU_GM_Curated_Posts_Item',
    'ET_Builder_Module_NU_GM_TimelineJS',
    'ET_Builder_Module_NU_GM_TimelineJS_Item',
  );

  if(post_type_exists('nu_gm_event')) {
    $nu_gm_divi_module_classes[] = 'ET_Builder_Module_NU_GM_Events';
  }

  return $nu_gm_divi_module_classes;
}

// Helper function to determine if current page is a divi page
function nu_gm_divi_is_divi_page( $post = false ) {
  if( !$post ) {
    global $post;
  }
  if($post instanceof WP_Post && ( get_page_template_slug( $post->ID ) == 'page-divi.php' || nu_gm_divi_text_contains_divi_shortcode( $post->post_content ) ) ) {
    return true;
  }
  return false;
}

// Helper function to determine if string $content contains Divi shortcodes
function nu_gm_divi_text_contains_divi_shortcode( $content ) {
  $return = ( !empty( $content ) && preg_match( '/\[et_pb_section\s/', $content ) );
  return $return;
}

// Helper function to return default Divi role settings for use in this theme
function nu_gm_get_divi_initial_role_settings( $gm_modules ) {
  require_once( ABSPATH . '/wp-admin/includes/user.php' );

  $roles = get_editable_roles();

  $capabilities = array(
    'theme_options'                  => 'off',
    'divi_library'                   => 'off',
    'ab_testing'                     => 'off',
    'portability'                    => 'off',
    'add_module'                     => 'on',
    'edit_module'                    => 'on',
    'move_module'                    => 'on',
    'disable_module'                 => 'on',
    'lock_module'                    => 'on',
    'divi_builder_control'           => 'on',
    'load_layout'                    => 'off',
    'save_library'                   => 'off',
    'add_library'                    => 'off',
    'edit_global_library'            => 'off',
    'general_settings'               => 'on',
    'advanced_settings'              => 'off',
    'custom_css_settings'            => 'off',
    'edit_colors'                    => 'on',
    'edit_content'                   => 'on',
    'edit_fonts'                     => 'on',
    'edit_buttons'                   => 'on',
    'edit_layout'                    => 'on',
    'edit_configuration'             => 'on',
    'et_pb_accordion'                => 'off',
    'et_pb_audio'                    => 'off',
    'et_pb_counters'                 => 'off',
    'et_pb_blog'                     => 'off',
    'et_pb_blurb'                    => 'off',
    'et_pb_button'                   => 'off',
    'et_pb_cta'                      => 'off',
    'et_pb_circle_counter'           => 'off',
    'et_pb_code'                     => 'off',
    'et_pb_comments'                 => 'off',
    'et_pb_contact_form'             => 'off',
    'et_pb_countdown_timer'          => 'off',
    'et_pb_divider'                  => 'off',
    'et_pb_signup'                   => 'off',
    'et_pb_filterable_portfolio'     => 'off',
    'et_pb_fullwidth_code'           => 'off',
    'et_pb_fullwidth_header'         => 'off',
    'et_pb_fullwidth_image'          => 'off',
    'et_pb_fullwidth_map'            => 'off',
    'et_pb_fullwidth_menu'           => 'off',
    'et_pb_fullwidth_portfolio'      => 'off',
    'et_pb_fullwidth_post_slider'    => 'off',
    'et_pb_fullwidth_post_title'     => 'off',
    'et_pb_fullwidth_slider'         => 'off',
    'et_pb_gallery'                  => 'off',
    'et_pb_image'                    => 'off',
    'et_pb_login'                    => 'off',
    'et_pb_map'                      => 'off',
    'et_pb_number_counter'           => 'off',
    'et_pb_team_member'              => 'off',
    'et_pb_portfolio'                => 'off',
    'et_pb_post_nav'                 => 'off',
    'et_pb_post_slider'              => 'off',
    'et_pb_post_title'               => 'off',
    'et_pb_pricing_tables'           => 'off',
    'et_pb_search'                   => 'off',
    'et_pb_shop'                     => 'off',
    'et_pb_sidebar'                  => 'off',
    'et_pb_slider'                   => 'off',
    'et_pb_social_media_follow'      => 'off',
    'et_pb_tabs'                     => 'off',
    'et_pb_testimonial'              => 'off',
    'et_pb_text'                     => 'off',
    'et_pb_toggle'                   => 'off',
    'et_pb_video'                    => 'off',
    'et_pb_video_slider'             => 'off',
    'et_pb_roles_portability'        => 'off',
    'et_builder_portability'         => 'off',
    'et_builder_layouts_portability' => 'off',
  );

  // Automatically enable all GM modules
  foreach ( $gm_modules as $gm_module ) {
    $capabilities[$gm_module] = 'on';
  }

  $settings = array_map(
    function ($role) use ($capabilities) {
      return $capabilities;
    },
    $roles
  );
  return $settings;
}

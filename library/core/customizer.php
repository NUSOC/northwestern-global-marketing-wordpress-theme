<?php

// Add nu_gm theme configuration options to the WP Customizer
function nu_gm_theme_customizer ($wp_customize) {
  // Remove unwanted WP customize sections
  $wp_customize->remove_section('colors');
  $wp_customize->remove_section('background_image');
  $wp_customize->remove_control('site_icon');

  // Remove blog description unless a child theme indicates otherwise
  if($remove_customizer_blogdescription = apply_filters('nu_gm_customizer_remove_blogdescription', true))
    $wp_customize->remove_control('blogdescription');

  // Add validation error styles for url fields
  $wp_customize->add_control(
    new NUGM_Customize_Misc_Control(
      $wp_customize,
      'nu_gm_url_error_style',
      array(
        'section' => 'header_lockup_section',
        'type' => 'style',
        'description' => '.customize-control-url.has-error input {outline: #dc3232 solid 2px;}',
      )
    )
  );

  // Add color coded styles for secondary color palette choices
  $wp_customize->add_control(
    new NUGM_Customize_Misc_Control(
      $wp_customize,
      'nu_gm_secondary_palette_color_style',
      array(
        'section'     => 'title_tagline',
        'type'        => 'style',
        'description' => secondary_palette_color_customizer_styles(),
      )
    )
  );

  // Add setting to select top bar logo image
  // Control is used in child themes only
  $wp_customize->add_setting( 'top_bar_northwestern_logo_img' , array(
    'default' => 'wordmark',
    'sanitize_callback' => 'sanitize_key',
  ));

  // Add setting to select secondary palette color
  // Control is used in child themes only
  $wp_customize->add_setting( 'secondary_palette_color' , array(
    'default' => 'default',
    'sanitize_callback' => 'nu_gm_sanitize_secondary_color',
  ));

  // Section for Header Lockups
  $wp_customize->add_section('header_lockup_section', array(
    'title' => 'Header Lockup',
    'priority' => 30,
  ));

  // Selection of Header Lockup Formats
  $wp_customize->add_setting( 'header_lockup_format_setting' , array(
    'default' => 'opt_1',
    'sanitize_callback' => 'sanitize_key',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'header_lockup_format',
      array(
        'label'      => 'Header Lockup Format',
        'section'    => 'header_lockup_section',
        'settings'   => 'header_lockup_format_setting',
        'type' => 'radio',
        'choices'        => array(
          'opt_0' => __( 'Provide an Image', 'nu_gm' ),
          'opt_1' => __( 'Standard (single line)', 'nu_gm' ),
          'opt_2' => __( 'Large Text Above & Small Text Below', 'nu_gm' ),
          'opt_3' => __( 'Small Text Above & Large Text Below', 'nu_gm' ),
          'opt_4' => __( 'Small Text Above & Medium Text Below', 'nu_gm' ),
        ),
      )
    )
  );

  // Header Lockup Image [opt_0]
  $wp_customize->add_setting( 'header_lockup_img_setting' , array(
    'default' => '',
    'sanitize_callback' => 'nu_gm_sanitize_image_path',
  ));
  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'header_lockup_img',
      array(
        'label'           => 'Header Lockup Image',
        'section'         => 'header_lockup_section',
        'settings'        => 'header_lockup_img_setting',
        'active_callback' => 'nu_gm_header_lockup_format_callback',
      )
    )
  );

  // Header Lockup Line 1 Text
  $wp_customize->add_setting( 'header_lockup_line_1_text_setting', array(
    'default' => get_bloginfo('name'),
    'validate_callback' => 'nu_gm_validate_require_text',
    'sanitize_callback' => 'nu_gm_sanitize_lockup_text',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'header_lockup_line_1_text',
      array(
        'label' => 'Lockup Line 1',
        'section' => 'header_lockup_section',
        'type' => 'text',
        'settings' => 'header_lockup_line_1_text_setting',
        'active_callback' => 'nu_gm_header_lockup_format_callback',
      )
    )
  );

  // Header Lockup Line 1 Link
  $wp_customize->add_setting( 'header_lockup_line_1_link_setting', array(
    'default' => '',
    'validate_callback' => 'nu_gm_validate_url',
    'sanitize_callback' => 'nu_gm_sanitize_url',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'header_lockup_line_1_link',
      array(
        'label' => 'Lockup Line 1 Link',
        'section' => 'header_lockup_section',
        'type' => 'url',
        'settings' => 'header_lockup_line_1_link_setting',
        'active_callback' => 'nu_gm_header_lockup_format_callback',
        'description' => 'Leave blank to have this line link to the site homepage.',
      )
    )
  );

  // Header Lockup Line 2 Text
  $wp_customize->add_setting( 'header_lockup_line_2_text_setting', array(
    'default' => '',
    'validate_callback' => 'nu_gm_validate_require_text',
    'sanitize_callback' => 'nu_gm_sanitize_lockup_text',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'header_lockup_line_2_text',
      array(
        'label' => 'Lockup Line 2',
        'section' => 'header_lockup_section',
        'type' => 'text',
        'settings' => 'header_lockup_line_2_text_setting',
        'active_callback' => 'nu_gm_header_lockup_format_callback',
      )
    )
  );

  // Header Lockup Line 2 Link
  $wp_customize->add_setting( 'header_lockup_line_2_link_setting', array(
    'default' => '',
    'validate_callback' => 'nu_gm_validate_url',
    'sanitize_callback' => 'nu_gm_sanitize_url',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'header_lockup_line_2_link',
      array(
        'label' => 'Lockup Line 2 Link',
        'section' => 'header_lockup_section',
        'type' => 'url',
        'settings' => 'header_lockup_line_2_link_setting',
        'active_callback' => 'nu_gm_header_lockup_format_callback',
        'description' => 'Leave blank to have this line link to the site homepage.',
      )
    )
  );

  // Footer Management Panel
  $wp_customize->add_panel('footer_management', array(
    'title' => 'Footer',
    'priority'       => 900,
    'capability'     => 'edit_theme_options',
  ));

  // Footer Contact Info
  $wp_customize->add_section('footer_contact_info', array(
    'title'    => 'Contact Info',
    'panel'    => 'footer_management',
    'priority' => 20,
  ));

  // Footer Contact Info: Address Line 1
  $wp_customize->add_setting( 'footer_contact_info_address_line_1_setting', array(
    'default' => '633 Clark Street',
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_address_line_1',
      array(
        'label' => 'Address Line 1',
        'section' => 'footer_contact_info',
        'type' => 'text',
        'settings' => 'footer_contact_info_address_line_1_setting',
      )
    )
  );

  // Footer Contact Info: Address Line 2
  $wp_customize->add_setting( 'footer_contact_info_address_line_2_setting', array(
    'default' => 'Evanston, IL 60208',
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_address_line_2',
      array(
        'label' => 'Address Line 2',
        'section' => 'footer_contact_info',
        'type' => 'text',
        'settings' => 'footer_contact_info_address_line_2_setting',
      )
    )
  );

  // Footer Contact Info: Phone 1 Label
  $wp_customize->add_setting( 'footer_contact_info_phone_1_label_setting', array(
    'default' => 'Evanston',
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_phone_1_label',
      array(
        'label' => 'Phone #1 Label',
        'section' => 'footer_contact_info',
        'type' => 'text',
        'settings' => 'footer_contact_info_phone_1_label_setting',
      )
    )
  );

  // Footer Contact Info: Phone 1 Phone Number
  $wp_customize->add_setting( 'footer_contact_info_phone_1_number_setting', array(
    'default' => '(847) 491-3741',
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_phone_1_number',
      array(
        'label' => 'Phone #1 Number',
        'section' => 'footer_contact_info',
        'type' => 'text',
        'settings' => 'footer_contact_info_phone_1_number_setting',
      )
    )
  );

  // Footer Contact Info: Phone 2 Label
  $wp_customize->add_setting( 'footer_contact_info_phone_2_label_setting', array(
    'default' => 'Chicago',
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_phone_2_label',
      array(
        'label' => 'Phone #2 Label',
        'section' => 'footer_contact_info',
        'type' => 'text',
        'settings' => 'footer_contact_info_phone_2_label_setting',
      )
    )
  );

  // Footer Contact Info: Phone 1 Phone Number
  $wp_customize->add_setting( 'footer_contact_info_phone_2_number_setting', array(
    'default' => '(312) 503-8649',
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_phone_2_number',
      array(
        'label' => 'Phone #2 Number',
        'section' => 'footer_contact_info',
        'type' => 'text',
        'settings' => 'footer_contact_info_phone_2_number_setting',
      )
    )
  );

  // Footer Contact Info: Email
  $wp_customize->add_setting( 'footer_contact_info_email_setting', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_email',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_email',
      array(
        'label' => 'Email',
        'section' => 'footer_contact_info',
        'type' => 'text',
        'settings' => 'footer_contact_info_email_setting',
      )
    )
  );

  // Footer Contact Info: Website
  $wp_customize->add_setting( 'footer_contact_info_website_setting', array(
    'default' => '',
    'sanitize_callback' => 'nu_gm_sanitize_url',
    'validate_callback' => 'nu_gm_validate_url',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'footer_contact_info_website',
      array(
        'label' => 'Website URL',
        'section' => 'footer_contact_info',
        'type' => 'url',
        'settings' => 'footer_contact_info_website_setting',
      )
    )
  );

  // Footer Social Media
  $wp_customize->add_section('footer_social_media_links', array(
    'title'    => 'Social Media Links',
    'panel'    => 'footer_management',
    'priority' => 30,
  ));

  // Hide RSS Feed
  $wp_customize->add_setting( 'nu_hide_rss' , array(
    'default' => false,
    'sanitize_callback' => 'nu_gm_sanitize_checkbox',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'nu_hide_rss',
      array(
        'label' => 'Hide RSS Feed',
        'section' => 'footer_social_media_links',
        'type' => 'checkbox',
        'settings' => 'nu_hide_rss',
      )
    )
  );

  // Footer Social Media Links
  $social_media_options = get_supported_social_media();
  foreach ($social_media_options as $social_media_option) {
    $key = str_replace(' ', '-', strtolower($social_media_option));
    $wp_customize->add_setting( 'footer_social_media_links_'.$key.'_setting', array(
      'default' => '',
      'sanitize_callback' => 'nu_gm_sanitize_url',
      'validate_callback' => 'nu_gm_validate_url',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'footer_social_media_links_'.$key,
        array(
          'label' => $social_media_option.' URL',
          'section' => 'footer_social_media_links',
          'type' => 'url',
          'settings' => 'footer_social_media_links_'.$key.'_setting',
        )
      )
    );
  }

  // Footer Links Button to Redirect to Menus
  $wp_customize->add_control(
      new NUGM_Customize_Misc_Control(
          $wp_customize,
          'footer_links_redirect',
          array(
              'section'  => 'footer_site_links',
              'description'    => 'Either select an existing menu from the above list, or add a new menu using the button below:</p><p><button type="button" class="button-secondary" aria-expanded="false" onclick="wp.customize.panel(\'nav_menus\').expanded(1)">Add or Edit Menus</button>',
              'type' => 'text',
              'priority' => 35,
          )
      )
  );
  $wp_customize->get_control('nav_menu_locations[footer-links]')->section = 'footer_site_links';

  // Post Formatting Section
  $wp_customize->add_section('post_format', array(
    'title' => 'Post Display Options',
    'priority' => 700,
  ));

  // Post Formatting Card Style
  $wp_customize->add_setting( 'post_list_format_setting' , array(
    'default' => 'standard',
    'sanitize_callback' => 'sanitize_key',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'post_list_format',
      array(
        'label' => 'Display Format of Posts in Lists',
        'section' => 'post_format',
        'type' => 'radio',
        'choices'        => array(
          'standard'   => __( 'Textual Preview', 'nu_gm' ),
          'feature-box'   => __( 'Feature Box', 'nu_gm' ),
          'photo-feature'  => __( 'Photo Feature', 'nu_gm' )
        ),
        'settings' => 'post_list_format_setting',
      )
    )
  );

  // Posts Page Header Lockup Image
  $wp_customize->add_setting( 'post_list_hero_img_setting' , array(
    'default' => '',
    'sanitize_callback' => 'nu_gm_sanitize_image_path',
  ));
  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'post_list_hero_img',
      array(
        'label'           => 'Hero Banner',
        'section'         => 'post_format',
        'settings'        => 'post_list_hero_img_setting',
        'active_callback' => 'nu_gm_is_homepage_static',
      )
    )
  );

  // News Formatting Card Style
  if(post_type_exists('nu_gm_news')) {

    // News Formatting Section
    $wp_customize->add_section('nu_gm_news_format', array(
      'title' => 'News Article Display Options',
      'priority' => 701,
    ));

    $wp_customize->add_setting( 'nu_gm_news_list_format_setting' , array(
      'default' => 'news-listing',
      'sanitize_callback' => 'sanitize_key',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'nu_gm_news_list_format',
        array(
          'label' => 'Display Format of News in Lists',
          'section' => 'nu_gm_news_format',
          'type' => 'radio',
          'choices'        => array(
            'news-listing'   => __( 'News Listing (always shows sidebar)', 'nu_gm' ),
            'standard'   => __( 'Textual Preview', 'nu_gm' ),
            'feature-box'   => __( 'Feature Box', 'nu_gm' ),
            'photo-feature'  => __( 'Photo Feature', 'nu_gm' )
          ),
          'settings' => 'nu_gm_news_list_format_setting',
        )
      )
    );
  }

  // Directory Entry Settings
  if(post_type_exists('nu_gm_directory_item')) {

    // Directory Entry Formatting Section
    $wp_customize->add_section('nu_gm_directory_item_format', array(
      'title' => 'Directory Display Options',
      'priority' => 702,
    ));

    // Directory Entry Formatting Card Style
    $wp_customize->add_setting( 'nu_gm_directory_item_list_format_setting' , array(
      'default' => 'people-small',
      'sanitize_callback' => 'sanitize_key',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'nu_gm_directory_item_list_format',
        array(
          'label' => 'Display Format of Directory Entries in Lists',
          'section' => 'nu_gm_directory_item_format',
          'type' => 'radio',
          'choices'        => array(
            'people-big'   => __( 'Person (Big)', 'nu_gm' ),
            'people-medium'   => __( 'Person (Medium)', 'nu_gm' ),
            'people-small'  => __( 'Person (Small)', 'nu_gm' ),
          ),
          'settings' => 'nu_gm_directory_item_list_format_setting',
        )
      )
    );

    // Directory Entry Show Image
    $wp_customize->add_setting( 'nu_gm_directory_item_list_show_img_setting' , array(
      'default' => true,
      'sanitize_callback' => 'nu_gm_sanitize_checkbox',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'nu_gm_directory_item_list_show_img',
        array(
          'label' => 'Display Profile Image in Directory Listing',
          'section' => 'nu_gm_directory_item_format',
          'type' => 'checkbox',
          'settings' => 'nu_gm_directory_item_list_show_img_setting',
        )
      )
    );

    // Directory Listing Group by First Letter
    $wp_customize->add_setting( 'nu_gm_directory_item_list_group_by_initial_setting' , array(
      'default' => true,
      'sanitize_callback' => 'nu_gm_sanitize_checkbox',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'nu_gm_directory_item_list_group_by_initial',
        array(
          'label' => 'Display Directory Listings Grouped by Last Initial',
          'section' => 'nu_gm_directory_item_format',
          'type' => 'checkbox',
          'settings' => 'nu_gm_directory_item_list_group_by_initial_setting',
        )
      )
    );

    // News Archive Hero Image
    $wp_customize->add_setting( 'nu_gm_directory_item_list_hero_img_setting' , array(
      'default' => '',
      'sanitize_callback' => 'nu_gm_sanitize_image_path',
    ));
    $wp_customize->add_control(
      new WP_Customize_Image_Control(
        $wp_customize,
        'nu_gm_directory_item_list_hero_img',
        array(
          'label'      => 'Hero Banner',
          'section'    => 'nu_gm_directory_item_format',
          'settings'   => 'nu_gm_directory_item_list_hero_img_setting',
        )
      )
    );
  }

  // Project Formatting Card Style
  if(post_type_exists('nu_gm_project')) {

    // Project Formatting Section
    $wp_customize->add_section('nu_gm_project_format', array(
      'title' => 'Project Display Options',
      'priority' => 703,
    ));

    $wp_customize->add_setting( 'nu_gm_project_list_format_setting' , array(
      'default' => 'feature-box',
      'sanitize_callback' => 'sanitize_key',
    ));
    $wp_customize->add_control(
      new WP_Customize_Control(
        $wp_customize,
        'nu_gm_project_list_format',
        array(
          'label' => 'Display Format of Projects in Lists',
          'section' => 'nu_gm_project_format',
          'type' => 'radio',
          'choices'        => array(
            'standard'   => __( 'Textual Preview', 'nu_gm' ),
            'feature-box'   => __( 'Feature Box', 'nu_gm' ),
            'photo-feature'  => __( 'Photo Feature', 'nu_gm' )
          ),
          'settings' => 'nu_gm_project_list_format_setting',
        )
      )
    );

    // Project Archive Hero Image
    $wp_customize->add_setting( 'nu_gm_project_list_hero_img_setting' , array(
      'default' => '',
      'sanitize_callback' => 'nu_gm_sanitize_image_path',
    ));
    $wp_customize->add_control(
      new WP_Customize_Image_Control(
        $wp_customize,
        'nu_gm_project_list_hero_img',
        array(
          'label'      => 'Hero Banner',
          'section'    => 'nu_gm_project_format',
          'settings'   => 'nu_gm_project_list_hero_img_setting',
        )
      )
    );
  }

  // Archive Display Sidebar
  $wp_customize->add_setting( 'archive_display_sidebar_setting' , array(
    'default' => true,
    'sanitize_callback' => 'nu_gm_sanitize_checkbox',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'archive_display_sidebar',
      array(
        'label' => 'Display Sidebar in Category, Tag, Author and Archive Lists',
        'section' => 'post_format',
        'type' => 'checkbox',
        'settings' => 'archive_display_sidebar_setting',
      )
    )
  );

  // Show Breadcrumbs
  $wp_customize->add_setting( 'show_breadcrumbs_setting' , array(
    'default' => false,
    'sanitize_callback' => 'nu_gm_sanitize_checkbox',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'show_breadcrumbs',
      array(
        'label' => 'Show Breadcrumb Trail',
        'section' => 'post_format',
        'type' => 'checkbox',
        'settings' => 'show_breadcrumbs_setting',
      )
    )
  );

  // Homepage Management Panel
  $wp_customize->add_panel('homepage_management', array(
    'title' => 'Homepage',
    'priority'       => 800,
    'capability'     => 'edit_theme_options',
  ));

  // Homepage Hero Banner Section
  $wp_customize->add_section('homepage_hero_banner', array(
    'title' => 'Hero Banner',
    'panel' => 'homepage_management',
    'priority' => 20,
    'active_callback' => 'is_homepage_dynamic',
  ));

  // Homepage Hero Banner Toggle
  $wp_customize->add_setting( 'homepage_hero_banner_visible_setting' , array(
    'default' => true,
    'sanitize_callback' => 'nu_gm_sanitize_checkbox',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'homepage_hero_banner_visible',
      array(
        'label' => 'Show Hero Banner',
        'section' => 'homepage_hero_banner',
        'type' => 'checkbox',
        'settings' => 'homepage_hero_banner_visible_setting',
      )
    )
  );

  // Homepage Hero Banner Title
  $wp_customize->add_setting( 'homepage_hero_banner_title_setting' , array(
    'default' => get_bloginfo('name'),
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'homepage_hero_banner_title',
      array(
        'label' => 'Hero Banner Title',
        'section' => 'homepage_hero_banner',
        'type' => 'text',
        'settings' => 'homepage_hero_banner_title_setting',
        'active_callback' => 'homepage_hero_banner_visible',
      )
    )
  );

  // Homepage Hero Banner Subhead
  $wp_customize->add_setting( 'homepage_hero_banner_subhead_setting' , array(
    'default' => get_bloginfo('description'),
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'homepage_hero_banner_subhead',
      array(
        'label' => 'Hero Banner Subhead',
        'section' => 'homepage_hero_banner',
        'type' => 'text',
        'settings' => 'homepage_hero_banner_subhead_setting',
        'active_callback' => 'homepage_hero_banner_visible',
      )
    )
  );

  // Homepage Hero Banner Link Button Label
  $wp_customize->add_setting( 'homepage_hero_banner_link_btn_label_setting' , array(
    'default' => 'More',
    'sanitize_callback' => 'nu_gm_sanitize_strip_script_and_style',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'homepage_hero_banner_link_btn_label',
      array(
        'label' => 'Hero Banner Link Button Label',
        'section' => 'homepage_hero_banner',
        'type' => 'text',
        'settings' => 'homepage_hero_banner_link_btn_label_setting',
        'active_callback' => 'homepage_hero_banner_visible',
      )
    )
  );

  // Homepage Hero Banner Link Button URL
  $wp_customize->add_setting( 'homepage_hero_banner_link_btn_url_setting' , array(
    'default' => home_url(),
    'validate_callback' => 'nu_gm_validate_url',
    'sanitize_callback' => 'nu_gm_sanitize_url',
  ));
  $wp_customize->add_control(
    new WP_Customize_Control(
      $wp_customize,
      'homepage_hero_banner_link_btn_url',
      array(
        'label' => 'Hero Banner Link Button URL',
        'section' => 'homepage_hero_banner',
        'type' => 'url',
        'settings' => 'homepage_hero_banner_link_btn_url_setting',
        'active_callback' => 'homepage_hero_banner_visible',
      )
    )
  );

  // Homepage Hero Banner Image
  $wp_customize->add_setting( 'homepage_hero_banner_img_setting' , array(
    'default' => get_template_directory_uri().'/library/images/default-hero.jpg',
    'sanitize_callback' => 'nu_gm_sanitize_image_path',
  ));
  $wp_customize->add_control(
    new WP_Customize_Image_Control(
      $wp_customize,
      'homepage_hero_banner_img',
      array(
        'label'      => 'Hero Banner Image',
        'section'    => 'homepage_hero_banner',
        'settings'   => 'homepage_hero_banner_img_setting',
        'active_callback' => 'homepage_hero_banner_visible',
      )
    )
  );

  // Homepage Widgets Section
  if(empty($wp_customize->get_section('sidebar-widgets-homepage'))) $wp_customize->add_section('sidebar-widgets-homepage');
  $wp_customize->get_section('sidebar-widgets-homepage')->panel = 'homepage_management';
  $wp_customize->get_section('sidebar-widgets-homepage')->title = 'Homepage Widgets';
  $wp_customize->get_section('sidebar-widgets-homepage')->priority = 30;
  $wp_customize->get_section('sidebar-widgets-homepage')->active_callback = 'is_homepage_dynamic';

  // Homepage Content Options
  $wp_customize->get_section('static_front_page')->panel = 'homepage_management';
  $wp_customize->get_section('static_front_page')->title = 'Display Options';
  $wp_customize->get_section('static_front_page')->priority = 10;

  // Footer Links Section
  $wp_customize->add_section('footer_site_links', array(
    'title'    => 'Site Links',
    'panel'    => 'footer_management',
    'priority' => 40,
  ));
}
add_action( 'customize_register', 'nu_gm_theme_customizer', 9000000 );

// active_callback to determine which header lockup fields should be present based on the format selected
function nu_gm_header_lockup_format_callback( $control ) {
  $nu_gm_header_lockup_format = $control->manager->get_setting('header_lockup_format_setting')->value();
  if(empty($nu_gm_header_lockup_format)) $nu_gm_header_lockup_format = $control->manager->get_setting('header_lockup_format_setting')->default();
  switch($nu_gm_header_lockup_format) {
    case 'opt_1':
      return ( $control->id == 'header_lockup_line_1_text' );
      break;
    case 'opt_2':
    case 'opt_3':
    case 'opt_4':
      return  (     $control->id == 'header_lockup_line_1_text'
                ||  $control->id == 'header_lockup_line_1_link'
                ||  $control->id == 'header_lockup_line_2_text'
                ||  $control->id == 'header_lockup_line_2_link'
              );
      break;
    case 'opt_0':
      return ( $control->id == 'header_lockup_img' );
      break;
    default:
      return false;
  }
  return false;
}

// URL Validation helper function - adapted from Drupal 7 "valid_url" function
function nu_gm_valid_url ( $url, $absolute = false ) {
  if ($absolute) {
    return (bool) preg_match("
      /^                                                      # Start at the beginning of the text
      (?:(?:ftp|https?|feed):)?\/\/                           # Look for ftp, http, https or feed schemes
      (?:                                                     # Userinfo (optional) which is typically
        (?:(?:[\w\.\-\+!$&'\(\)*\+,;=]|%[0-9a-f]{2})+:)*      # a username or a username and password
        (?:[\w\.\-\+%!$&'\(\)*\+,;=]|%[0-9a-f]{2})+@          # combination
      )?
      (?:
        (?:[a-z0-9\-\.]|%[0-9a-f]{2})+                        # A domain name or a IPv4 address
        |(?:\[(?:[0-9a-f]{0,4}:)*(?:[0-9a-f]{0,4})\])         # or a well formed IPv6 address
      )
      (?::[0-9]+)?                                            # Server port number (optional)
      (?:[\/|\?]
        (?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})   # The path and query (optional)
      *)?
    $/xi", $url);
  }
  else {
    return (bool) preg_match("/^(?:[\w#!:\.\?\+=&@$'~*,;\/\(\)\[\]\-]|%[0-9a-f]{2})+$/i", $url);
  }
}

// validate_callback for urls
function nu_gm_validate_url ( $validity, $input ) {
  if( !empty($input) ) {
    if( nu_gm_valid_url($input) === false && nu_gm_valid_url($input, true) === false ) {
      $validity->add( 'required', __( 'This must be a valid URL or relative path.', 'nu_gm' ) );
    }
  }
  return $validity;
}

// validate_callback for requiring non-empty text
function nu_gm_validate_require_text ( $validity, $input ) {
  if( empty($input) ) {
    $validity->add( 'required', __( 'This value is required.', 'nu_gm' ) );
  }
  return $validity;
}

// validate_callback for requiring non-empty file field
function nu_gm_validate_require_file ( $validity, $input ) {
  if( empty($input) ) {
    $validity->add( 'required', __( 'This value is required.', 'nu_gm' ) );
  }
  return $validity;
}

// sanitize_callback for removing script & style tags as well as their contents
function nu_gm_sanitize_strip_script_and_style ( $input ) {
  $input = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $input );
  return $input;
}

// sanitize_callback for header lockup text
function nu_gm_sanitize_lockup_text ( $input ) {
  $input = nu_gm_sanitize_strip_script_and_style( $input, NULL );
  $input = strip_tags( $input, '<strong><small><span><p>' );
  $input = preg_replace( '/[\r\n\t ]+/', ' ', $input );
  return $input;
}

// sanitize_callback for header lockup text
function nu_gm_sanitize_secondary_color ( $input ) {
  $input = ( $input == 'default' || sanitize_hex_color( $input ) ) ? $input : 'default';
  return $input;
}

// sanitize_callback for header lockup text
function nu_gm_sanitize_text_field ( $input ) {
  $input = sanitize_text_field( $input );
  return $input;
}

// sanitize_callback for urls
function nu_gm_sanitize_url ( $input ) {
  $input = wp_strip_all_tags( $input, true );
  $input = wp_kses_data( $input );
  return $input;
}

// sanitize_callback for image paths
function nu_gm_sanitize_image_path ( $input ) {
  $input = nu_gm_sanitize_url( $input );
  return $input;
}

// sanitize_callback for checkboxes
function nu_gm_sanitize_checkbox( $input ) {
  if ( $input == true ) {
    return true;
  } else {
    return false;
  }
}

// helper function to return secondary palette color choices as an array
function secondary_palette_color_choices() {
  $palette = array(
    'default' => __( 'Purple (default)', 'nu_gm' ),
    '#0D2D6C' => __( 'Navy Blue', 'nu_gm' ),
    '#5091CD' => __( 'Bright Blue', 'nu_gm' ),
    '#007FA4' => __( 'Teal Blue', 'nu_gm' ),
    '#008656' => __( 'Green', 'nu_gm' ),
    '#58B947' => __( 'Bright Green', 'nu_gm' ),
    '#CA7C1B' => __( 'Burnt Orange', 'nu_gm' ),
    '#D85820' => __( 'Orange', 'nu_gm' ),
    '#EF553F' => __( 'Bright Red', 'nu_gm' ),
  );
  return $palette;
}

// helper function to return styles for secondary palette color choices
function secondary_palette_color_customizer_styles() {
  $colors = array_keys( secondary_palette_color_choices() );
  $styles = array_map(
    function( $color ) {
      $color_hex = preg_match( '|^#[A-F0-9]{3}[A-F0-9]{3}?$|i', $color ) ? $color : '#4e2a84';
      return sprintf( '#customize-control-secondary_palette_color label input[value="%1$s"] { border-color: %2$s; }
                       #customize-control-secondary_palette_color label input[value="%1$s"]:before { background-color: %2$s; }
                       #customize-control-secondary_palette_color label input[value="%1$s"]:after { background-color: %2$s; }', $color, $color_hex );
    },
    $colors
  );
  $styles[] = '#customize-control-secondary_palette_color label { position: relative; z-index: 2; text-indent: 5px; color: #fff; }';
  $styles[] = '#customize-control-secondary_palette_color label input[type="radio"]:after {
    content: " ";
    position: absolute;
    top: 1px;
    bottom: 1px;
    left: -24px;
    right: 0;
    display: block;
    z-index: -1;
  }';
  $styles[] = '@media only screen and (max-width: 782px) { #customize-control-secondary_palette_color label input[type="radio"]:after { left: -32px; top: 0; } }';

  // Remove empty elements
  $styles = array_filter( $styles, function( $value ) { return !empty($value); } );

  $output = implode( ' ', $styles );
  return $output;
}

// Trigger admin notice alerting users that they can use the customizer to customize their site
add_action( 'admin_notices', 'nu_gm_admin_notice__customize_theme__info' );
function nu_gm_admin_notice__customize_theme__info () {
  if( current_user_can( 'customize' ) ) {
    $notice_content = '<p><strong>Customize Your Site!</strong></p><p>This theme supports customization using the Customizer tool, which will enable you to control the look and feel of the site while previewing changes.</p><p><a href="'.admin_url('/customize.php').'" class="button-primary">Customize this Site</a></p>';
    nu_gm_dismissible_admin_notice( 'get_started_customizing_theme', $notice_content );
  }
}

// Add Custom Customizer Control to Enable Arbitrary HTML
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'NUGM_Customize_Misc_Control' ) ) :
class NUGM_Customize_Misc_Control extends WP_Customize_Control {
  public $settings = 'blogname';
  public $description = '';

  public function render_content() {
    switch ( $this->type ) {
      default:
      case 'text':
        ?>
        <p class="description"><?php echo $this->description; ?></p>
        <?php
        break;

      case 'heading':
        ?>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php
        break;

      case 'style':
        ?>
        <style type="text/css"><?php echo $this->description; ?></style>
        <?php
        break;

      case 'markup':
        echo $this->description;
        break;

      case 'line':
        ?>
        <hr />'
        <?php
        break;
    }
  }
}
endif;

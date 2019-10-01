<?php
/*
This file handles the admin area and functions.
You can use this file to make changes to the
dashboard. Updates to this page are coming soon.
It's turned off by default, but you can call it
via the functions file.
*/


// CUSTOM Metaboxes from https://metabox.io
require_once( dirname( __FILE__ ) . '/vendor/meta-box/meta-box.php' );
require_once( dirname( __FILE__ ) . '/vendor/meta-box-group/meta-box-group.php' );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/************* DASHBOARD WIDGETS *****************/

// disable default dashboard widgets
function disable_default_dashboard_widgets() {
	global $wp_meta_boxes;
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);    // Right Now Widget
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);        // Activity Widget
	// unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']); // Comments Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);  // Incoming Links Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);         // Plugins Widget

	// unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);    // Quick Press Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);     // Recent Drafts Widget
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);           //
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);         //

	// remove plugin dashboard boxes
	unset($wp_meta_boxes['dashboard']['normal']['core']['yoast_db_widget']);           // Yoast's SEO Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['rg_forms_dashboard']);        // Gravity Forms Plugin Widget
	unset($wp_meta_boxes['dashboard']['normal']['core']['bbp-dashboard-right-now']);   // bbPress Plugin Widget
}

// calling all custom dashboard widgets
function nu_gm_custom_dashboard_widgets() {
	/*
	Be sure to drop any other created Dashboard Widgets
	in this function and they will all load.
	*/
}


// removing the dashboard widgets
// add_action( 'wp_dashboard_setup', 'disable_default_dashboard_widgets' );
// adding any custom widgets
add_action( 'wp_dashboard_setup', 'nu_gm_custom_dashboard_widgets' );

/************* CUSTOMIZE ADMIN *******************/

/*
I don't really recommend editing the admin too much
as things may get funky if WordPress updates. Here
are a few funtions which you can choose to use if
you like.
*/

// Custom Backend Footer
function nu_gm_custom_admin_footer() {
	_e( '<span id="footer-thankyou">Developed by <a href="https://mediadesign.it.northwestern.edu/" target="_blank">Northwestern IT S&S Media and Design</a></span>.', 'nu_gm' );
}
add_filter( 'admin_footer_text', 'nu_gm_custom_admin_footer' );

// Rename Aside and Standard post formats in posts list table and edit screen
function rename_post_formats( $safe_text ) {
    if ( $safe_text == 'Aside' )
      return 'No Sidebar';
    else if ( $safe_text == 'Standard' )
      return 'Include Sidebar';

    return $safe_text;
}
add_filter( 'esc_html', 'rename_post_formats' );
function live_rename_formats() {
    global $current_screen;
    if ( $current_screen->base == 'post' ) {
        echo '<script type="text/javascript">
        jQuery("document").ready(function() {
            jQuery("#post-formats-select label").each(function() {
                if ( jQuery(this).text() == "Aside" )
                    jQuery(this).text("No Sidebar");
                if ( jQuery(this).text() == "Standard" )
                    jQuery(this).text("Include Sidebar");
            });

        });
        </script>';
    }
}
add_action('admin_head', 'live_rename_formats');

/************* CUSTOM WP TINYMCE EDITOR *****************/

// Add body classes and limit block formats
function nu_gm_tinymce_settings( $settings ) {
	// Limit the block formats available to users
	$settings['block_formats'] = "Paragraph=p;Header 2=h2;Header 3=h3;Header 4=h4;Header 5=h5;Header 6=h6";

	// Add correct class and ID to the body for content to get styled correctly
	$settings['body_class'] .= ' content';

  // Add format and button for transforming a tags into gm buttons (a.button)
  $settings['formats'] = preg_replace('|^(.*)(})$|', "$1, nubutton: { selector: \"a\", classes: \"button\", wrapper: false }$2", $settings['formats'] );
	$settings['setup'] = 'function(ed){
    ed.onInit.add(function(){
      tinymce.activeEditor.contentDocument.body.id = "main-content";
    });
    ed.addButton(
      "nubutton",
      {
        tooltip: "Button-Style Link",
        icon: "icon-gm-button",
        onclick: function() {
          ed.execCommand("mceToggleFormat", false, "nubutton");
        },

        onpostrender: function() {
          var btn = this;
          ed.on("init", function() {
            ed.formatter.formatChanged("nubutton", function(state) {
              btn.active(state);
            });
          });
        }
      }
    );
  }';

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'nu_gm_tinymce_settings' );

//Remove the unwanted TinyMCE buttons
function nu_gm_tinymce_buttons($buttons) {
	$remove = array('forecolor', 'alignjustify', 'blockquote', 'strikethrough');
	return array_diff($buttons,$remove);
}
add_filter('mce_buttons','nu_gm_tinymce_buttons');
add_filter('mce_buttons_2','nu_gm_tinymce_buttons');

// Add nubutton button to toolbar
function nu_gm_tinymce_button_link( $buttons ) {
  $buttons[] = 'nubutton';
  return $buttons;
}
add_filter('mce_buttons','nu_gm_tinymce_button_link');


// Trigger admin notice on menus screen warning users against more than 5 menu pages
add_action( 'admin_notices', 'nu_gm_admin_notice__menu_maximum_items_suggestion__info' );
function nu_gm_admin_notice__menu_maximum_items_suggestion__info () {
  $screen = get_current_screen();
  if( current_user_can( 'edit_theme_options' ) && !empty($screen) && $screen->id == 'nav-menus' ) {
    $notice_content = '<p><strong>Main Menu Limit</strong></p><p>Please limit the number of items appearing in this site\'s main menu to <strong style="color:#a00;">no more than 5 menu items in the main menu</strong> and  <strong style="color:#a00;">limit the titles to 1 or 2 words</strong>.</p><p>This will help keep this site looking clean and professional.</p>';
    nu_gm_dismissible_admin_notice( 'menu_maximum_items_suggestion', $notice_content, 'notice-warning' );
  }
}

// Trigger admin notice alerting users that they can use the customizer to customize their site
add_action( 'admin_notices', 'nu_gm_admin_notice__theme_switch_divi_warning' );
function nu_gm_admin_notice__theme_switch_divi_warning () {
  $screen = get_current_screen();
  if( !empty($screen) && 
      (
        $screen->id == 'themes' || 
        (
          $screen->parent_base == 'themes' && 
          !empty($_GET['page']) && 
          sanitize_key($_GET['page']) == 'multisite-theme-manager.php'
        )
      ) && 
      is_plugin_active( 'divi-builder/divi-builder.php' )
  ) {
    $divi_pages = get_pages(array(
      'meta_key' => '_et_pb_use_builder',
      'meta_value' => 'on',
      'number' => 1,
      'post_type' => 'page',
    ));
    if($divi_pages) {
      $class = 'notice notice-warning';
      $message = '<p><strong>Deactivate Divi Builder Before Switching Themes!</strong></p><p>This theme provides custom integration with the Divi Builder plugin, that other themes don\'t necessarily support.</p><p>Unfortunately, this means that <strong style="color:#a00;">you will lose some of the content that you\'ve built using Divi, if switching to a theme outside of the Northwestern Global theme family</strong>. You can review a list of the content on this site that was built with Divi on the <a href="'.admin_url('/edit.php?post_type=page&_et_pb_use_builder=on').'" >Pages > Divi Pages</a> section of the dashboard.</p><p><strong>Before you switch themes, make sure to disable the Divi Builder plugin from the <a href="'.admin_url('/plugins.php').'" >Plugins</a> section of the dashboard.</strong></p><p><a href="'.admin_url('/plugins.php').'" class="button-primary">Go to Plugins</a></p>';
      $styles = '<style>
      .theme-browser .theme[aria-describedby^=nu_gm] .theme-screenshot,
      .theme-browser .theme[data-slug^=nu_gm] .theme-screenshot {
          position: relative;
          z-index: 1;
        }
        .theme-browser .theme[aria-describedby^=nu_gm] .theme-screenshot:before,
        .theme-browser .theme[data-slug^=nu_gm] .theme-screenshot:before {
          content: "Northwestern Theme Family";
          position: absolute;
          bottom: 0;
          right: 0;
          z-index: 5;
          display: block;
          padding: 5px 10px;
          background: #4e2a84;
          color: #fff;
          border: 2px solid #fff;
          border-bottom-width: 0;
          border-right-width: 0;
          font-weight: bold;
          transition: opacity .2s ease-in-out;
        }
        .theme-browser .theme[aria-describedby^=nu_gm]:hover .theme-screenshot:before,
        .theme-browser .theme[data-slug^=nu_gm]:hover .theme-screenshot:before {
          opacity: .3;
        }
      </style>';
      printf( '<div class="%1$s"><p>%2$s</p></div>%3$s', $class, $message, $styles);
    }
  }
}

// Filter for Manage Pages screen based on whether authored natively or in Divi
add_action( 'restrict_manage_posts', 'nu_gm_admin_posts_filter_restrict_manage_posts_divi_filter' );
function nu_gm_admin_posts_filter_restrict_manage_posts_divi_filter(){
  if ( isset($_GET['post_type']) && $_GET['post_type'] == 'page' && is_plugin_active( 'divi-builder/divi-builder.php' ) ) {
    $values = array(
      'Divi' => 'on',
      'WordPress Editor' => 'off',
    );
    ?>
    <select name="_et_pb_use_builder">
    <option value=""><?php _e('All Authoring Tools ', 'nu_gm'); ?></option>
    <?php
      $current_v = isset($_GET['_et_pb_use_builder'])? sanitize_key($_GET['_et_pb_use_builder']):'';
      foreach ( $values as $label => $value ) {
        printf(
          '<option value="%s"%s>%s</option>',
          $value,
          $value == $current_v? ' selected="selected"':'',
          $label
        );
      }
    ?>
    </select>
    <?php
  }
}

// Filter Manage Pages screen based on whether authored natively or in Divi
add_filter( 'parse_query', 'nu_gm_manage_posts_divi_filter' );
function nu_gm_manage_posts_divi_filter( $query ){
  if( is_admin() ) {
    global $pagenow;
    if( $pagenow == 'edit.php' && 
        isset($_GET['post_type']) && 
        $_GET['post_type'] == 'page' && 
        is_plugin_active( 'divi-builder/divi-builder.php' ) && 
        !empty($_GET['_et_pb_use_builder'])
    ) {
      $divi_meta_query = array();
      $divi_meta_value = sanitize_key($_GET['_et_pb_use_builder']);
      if( $divi_meta_value == 'on' ) {
        $divi_meta_query['key'] = '_et_pb_use_builder';
        $divi_meta_query['value'] = $divi_meta_value;
      } elseif( $divi_meta_value == 'off' ) {
        $divi_meta_query = array(
          'relation' => 'OR',
          'divi_not_set' => array(
            'key' => '_et_pb_use_builder',
            'compare' => 'NOT EXISTS',
          ),
          'divi_not_on' => array(
            'key' => '_et_pb_use_builder',
            'compare' => '!=',
            'value' => 'on',
          ),
        );
      }
      $query->query_vars['meta_query'] = $query->query_vars['meta_query'] ?: array();
      $query->query_vars['meta_query'][] = $divi_meta_query;
    }
  }
  return $query;
}

// Add admin menu item for quick access to Divi-authored pages
add_action('admin_menu', 'nu_gm_add_divi_pages_admin_link');
function nu_gm_add_divi_pages_admin_link () {
  if( is_plugin_active( 'divi-builder/divi-builder.php' ) ) {
    add_submenu_page('edit.php?post_type=page', 'Pages Authored with Divi', 'Divi Pages', 'manage_pages', 'edit.php?post_type=page&_et_pb_use_builder=on');
  }
}

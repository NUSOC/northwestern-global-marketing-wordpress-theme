<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/*
*  Funtion to generate markup for theme listing
*/
function nu_theme_list() {
  if ( !function_exists( 'wp_get_themes' ) ) {
    require_once ABSPATH . 'wp-includes/theme.php';
  }
  $themes = wp_get_themes(array('errors' => null, 'allowed' => 'network'));
  $theme_list_html =
  '<style>
    .nu-theme-listing .photo-feature:nth-child(odd) {
      clear: left;
    }
    .nu-theme-listing .photo-feature {
      overflow: hidden;
      height: 0;
      padding-bottom: 28.4503632%;
    }
    .nu-theme-listing .photo-feature a,
    .nu-theme-listing .photo-feature a .front,
    .nu-theme-listing .photo-feature a .back {
      max-height: inherit;
    }
    .nu-theme-listing .photo-feature .front .image-wrapper {
      position: relative;
      z-index: 1;
      max-height: inherit;
    }
    .nu-theme-listing .photo-feature .front .image-wrapper:before {
      content: " ";
      width: 100%;
      position: absolute;
      z-index: 2;
      bottom: 0;
      left: 0;
      top: 0;
      right: 0;
      background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.45) 100%);
      background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, rgba(0,0,0,0)), color-stop(100%, rgba(0,0,0,0.45)));
      background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.45) 100%);
      background: -o-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.45) 100%);
      background: -ms-linear-gradient(top, rgba(0,0,0,0) 0%, rgba(0,0,0,0.45) 100%);
      background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,rgba(0,0,0,0.45) 100%);
    }
  </style>
  <div class="nu-theme-listing">
  <div class="nu-theme-listing-inner">
  <div class="photo-feature-'.(is_fullwidth() ? '3' : '2').'-across clearfix">';
  foreach($themes as $key => $theme) {
    // If theme is marked as private, do not display it
    if(strpos($theme->Name,'[Private]') !== false) {
      continue;
    }

    $theme_uri  = $theme->get('ThemeURI');
    $theme_desc = $theme->get('Description');
    $theme_desc = strlen($theme_desc) > 140 ? substr($theme_desc,0,140)."..." : $theme_desc;
    $theme_auth = $theme->get('Author');

    $theme_list_html .= '<article class="photo-feature">';
    if(!empty($theme_uri)) $theme_list_html .= '<a href="'.$theme_uri.'" target="_blank">';
    $theme_list_html .= '<div class="front">';
    $theme_list_html .= '<div class="image-wrapper"><img src="'.$theme->get_screenshot().'" alt="'.$theme->Name.'"></div>';
    $theme_list_html .= '<div class="text-over-image">';
    $theme_list_html .= '<h4>'.$theme->Name.'</h4>';
    if(!empty($theme_uri)) $theme_list_html .= '<p class="link">Learn More</p>';
    $theme_list_html .= '</div>'; // end .photo-feature div.front div.text-over-image
    $theme_list_html .= '</div>'; // end .photo-feature div.front
    $theme_list_html .= '<div class="back">';
    $theme_list_html .= '<div class="back-text">';
    $theme_list_html .= '<h4>'.$theme->Name.'</h4>';
    if(!empty($theme_desc)) $theme_list_html .= '<p class="decription">'.$theme_desc.'</p>';
    if(!empty($theme_uri)) $theme_list_html .= '<p class="link">Learn More</p>';
    $theme_list_html .= '</div>'; // end .photo-feature div.back div.back-text
    $theme_list_html .= '</div>'; // end .photo-feature div.back
    if(!empty($theme_uri)) $theme_list_html .= '</a>'; // end .photo-feature a
    $theme_list_html .= '</article>'; // end .photo-feature
  }
  $theme_list_html .=
  '</div>
  </div>
</div>';

return $theme_list_html;
}

/*
*  Filter to replace [nu:themes:list] shortcode with theme listing
*/
function nu_theme_list_shortcode($content) {
  if(is_single() || is_page()) {
    $content = preg_replace('/\[nu:themes:list\]/i', nu_theme_list(), $content);
  } else {
    $content = preg_replace('/\[nu:themes:list\]/i', '', $content);
  }

  return $content;
}
add_filter( 'the_content', 'nu_theme_list_shortcode' );

/*
*  Filter to replace [nu_theme_list] shortcode with theme listing using Shortcode API
*/
function nu_theme_list_handler( $atts ) {
  return nu_theme_list();
}
add_shortcode( 'nu_theme_list', 'nu_theme_list_handler' );

/*
*  Funtion to generate markup for plugin listing
*/
function nu_plugin_list() {
  if ( !function_exists( 'get_plugins' ) ) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
  }
  $plugins = apply_filters( 'all_plugins', get_plugins() );
  $plugin_list_html =
  '<div class="nu-plugin-listing">
    <div class="nu-plugin-listing-inner">
      <h2>Plugins</h2>
      <ul class="nu-plugin-listing-items clearfix">';
        foreach($plugins as $plugin) {
          $plugin_list_html .=
          '<li class="nu-plugin-listing-item">';
            if(isset($plugin['PluginURI']) && !empty($plugin['PluginURI'])) {
              $plugin_list_html .=
              '<div class="nu-plugin-listing-name-wrapper"
                <span class="nu-plugin-listing-name">
                  <a target="_blank" href="'.$plugin['PluginURI'].'">'.
                    $plugin['Name'].
                    '</a>
                </span>
              </div>';
            } else {
              $plugin_list_html .=
              '<div class="nu-plugin-listing-name-wrapper"
                <span class="nu-plugin-listing-name">'.
                  $plugin['Name'].
                '</span>
              </div>';
            }
          $plugin_list_html .= '</li>';
        }
      $plugin_list_html .=
      '</ul>
    </div>
    <style>
      .nu-plugin-listing-items {
        padding: 0 20px;
      }
    </style>
  </div>';

  return $plugin_list_html;
}

/*
*  Filter to replace [nu:plugins:list] shortcode with theme listing
*/
function nu_plugin_list_shortcode($content) {
  if(is_single() || is_page()) {
    $content = preg_replace('/\[nu:plugins:list\]/i', nu_plugin_list(), $content);
  } else {
    $content = preg_replace('/\[nu:plugins:list\]/i', '', $content);
  }

  return $content;
}
add_filter( 'the_content', 'nu_plugin_list_shortcode' );

/*
*  Filter to replace [nu_plugin_list] shortcode with plugin listing using Shortcode API
*/
function nu_plugin_list_handler( $atts ) {
  return nu_plugin_list();
}
add_shortcode( 'nu_plugin_list', 'nu_plugin_list_handler' );

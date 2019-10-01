<?php

class ET_Builder_Module_NU_GM_Alternate_Float extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_alternate_float';

  function init() {
    $this->name               = esc_html__( 'Alternate Float', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->child_slug         = 'et_pb_nu_gm_alternate_float_item';
    $this->child_item_text    = esc_html__( 'Alternate Float Item', 'nu_gm' );
    $this->whitelisted_fields = array(
      'title',
      'module_id',
      'enable_lightbox',
    );
    $this->fields_defaults    = array(
      'enable_lightbox' => 'off',
    );
    $this->advanced_options   = array();
  }

  static function get_module_slug() {
    return self::$module_slug;
  }

  function get_fields() {
    $fields = array(
      'title' => array(
        'label'           => esc_html__( 'Section Title', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the section title text.', 'nu_gm' ),
      ),
      'module_id' => array(
        'label'           => esc_html__( 'Section ID', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'configuration',
        'description'     => esc_html__( 'This is an administrative ID for this section. It should be unique, and should only contain lowercase letters, numbers, dashes and underscores.', 'nu_gm' ),
        'attributes'      => array( 'pattern' => '[a-z0-9\-_]*' ),
      ),
      'enable_lightbox' => array(
        'label'             => esc_html__( 'Enable Lightbox Image Preview', 'nu_gm' ),
        'type'              => 'yes_no_button',
        'option_category'   => 'basic_option',
        'options'           => array(
          'off' => esc_html__( "No", 'nu_gm' ),
          'on'  => esc_html__( 'Yes', 'nu_gm' ),
        ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    global $et_pb_nu_gm_alternating_float_side;
    $et_pb_nu_gm_alternating_float_side  = false;

    $title                   = $this->shortcode_atts['title'] ?: false;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $enable_lightbox         = $this->shortcode_atts['enable_lightbox'] == 'on' ? true : false;
    $lightbox_group_id       = uniqid('nu_gm_photo_grid_');
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    // Rewrite link structure based on whether lightbox / fancybox should be enabled
    $inner_content           = preg_replace_callback(
                                '|(<a class="alternate\-photo\-img\-link\salternate\-photo\-fancybox\-img\-link\sfancybox"[^>]+>)(.*)(</a>)|siU',
                                function( $matches ) use ( $enable_lightbox, $lightbox_group_id ) {
                                  $start_a = $matches[1];
                                  $inner   = $matches[2];
                                  $end_a   = $matches[3];
                                  if ( $enable_lightbox ) {
                                    $start_a = str_replace( 'rel="temporary"', 'rel="'.$lightbox_group_id.'"', $start_a );
                                    $inner   = preg_replace( '|<a class="alternate\-photo\-img\-link"[^>]+>|', '', $inner );
                                  } else {
                                    $start_a = '';
                                    if ( !preg_match( '|<a class="alternate\-photo\-img\-link|', $inner ) )
                                      $end_a = '';
                                  }
                                  return $start_a.$inner.$end_a;
                                },
                                $this->shortcode_content
                              );

    // Enqueue JS for fancybox / lightbox
    if ( $enable_lightbox ) {
      wp_enqueue_style( 'nu_gm-fancybox-css' );
      wp_enqueue_script( 'nu_gm-fancybox-js' );
      wp_add_inline_script( 'nu_gm-fancybox-js',
                            'jQuery(document).ready( function() {
                              jQuery(".fancybox[rel=\''.$lightbox_group_id.'\']").fancybox({
                                "padding" : 10,
                                "preload" : 4,
                                "nextEffect" : "fade",
                                "prevEffect" : "fade",
                                "beforeShow" : function() {
                                  // Add alt attribute
                                  var alt = this.element.find(\'img\').attr(\'alt\');
                                  this.inner.find(\'img\').attr(\'alt\', alt);

                                  // Add caption and title markup
                                  var caption = this.element.attr(\'caption\');
                                  this.title = "";
                                  if(caption) {
                                    if(this.title) this.title = "<h6 class=\'title-text\'>"+this.title+"</h6>";
                                    this.title += "<p class=\'caption-text\'>"+caption+"</p>";
                                  }
                                }
                              });
                            });');
    }

    $output  = '<section'.$module_id.$aria_label.' class="clearfix contain-1120 '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="alternate-photo-float-wrapper">'.$inner_content.'</div>';
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Alternate_Float;

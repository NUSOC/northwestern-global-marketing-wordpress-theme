<?php

class ET_Builder_Module_NU_GM_Hero_Banner extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_hero_banner';

  function init() {
    $this->name               = esc_html__( 'Hero Banner', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->whitelisted_fields = array(
      'title',
      'module_id',
      'tagline',
      'src',
      'alt',
    );
    $this->fields_defaults    = array();
    $this->advanced_options   = array();
  }

  static function get_module_slug() {
    return self::$module_slug;
  }

  function get_fields() {
    $fields = array(
      'title' => array(
        'label'           => esc_html__( 'Title', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the banner title text.', 'nu_gm' ),
      ),
      'module_id' => array(
        'label'           => esc_html__( 'Section ID', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'configuration',
        'description'     => esc_html__( 'This is an administrative ID for this section. It should be unique, and should only contain lowercase letters, numbers, dashes and underscores.', 'nu_gm' ),
        'attributes'      => array( 'pattern' => '[a-z0-9\-_]*' ),
      ),
      'tagline' => array(
        'label'           => esc_html__( 'Tagline', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the banner tagline text.', 'nu_gm' ),
      ),
      'src' => array(
        'label'              => esc_html__( 'Image', 'nu_gm' ),
        'type'               => 'upload',
        'required'           => true,
        'option_category'    => 'basic_option',
        'upload_button_text' => esc_attr__( 'Upload an image', 'nu_gm' ),
        'choose_text'        => esc_attr__( 'Choose an Image', 'nu_gm' ),
        'update_text'        => esc_attr__( 'Set As Image', 'nu_gm' ),
        'description'        => esc_html__( 'Upload the image you would like to display.', 'nu_gm' ),
      ),
      'alt' => array(
        'label'           => esc_html__( 'Image Alternative Text', 'nu_gm' ),
        'type'            => 'text',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the image ALT text. A short description of your image can be placed here.', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $tagline                 = $this->shortcode_atts['tagline'] ?: false;
    $src_id                  = nu_gm_get_attachment_id_from_src( $this->shortcode_atts['src'] );
    $src                     = $src_id == false ? $this->shortcode_atts['src'] : wp_get_attachment_image_src( $src_id, 'hero-standard' )[0];
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    $output  = '<section'.$module_id.$aria_label.' class="clearfix hero contain-1440 '.preg_replace('|_|', '-', $this->slug).'">';
      $output  .= '<div class="hero-image" style="background: #4e2a84 url(\''.$src.'\') no-repeat center / cover; height: 420px;">';
        $output  .= '<div class="contain-1120">';
          if($title)   $output  .= '<h2>'.$title.'</h2>';
          if($tagline) $output  .= '<p>'.$tagline.'</p>';
        $output .= '</div>';
      $output .= '</div>';
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Hero_Banner;

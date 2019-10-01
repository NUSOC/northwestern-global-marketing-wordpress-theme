<?php

class ET_Builder_Module_NU_GM_Text extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_text';

  function init() {
    $this->name               = esc_html__( 'Text', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->whitelisted_fields = array(
      'title',
      'content',
      'container_class',
      'enable_inline_sidebar',
      'sidebar_content',
      'module_id',
    );
    $this->fields_defaults    = array(
      'enable_inline_sidebar' => 'off',
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
      'content' => array(
        'label'           => esc_html__( 'Content', 'nu_gm' ),
        'type'            => 'tiny_mce',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the descriptive text.', 'nu_gm' ),
      ),
      'container_class' => array(
        'label'           => esc_html__( 'Width', 'nu_gm' ),
        'type'            => 'select',
        'required'        => true,
        'option_category' => 'basic_option',
        'options'         => array(
          'contain-1120' => esc_html__( '1120px', 'nu_gm' ),
          'contain-970'  => esc_html__( '970px', 'nu_gm' ),
        ),
      ),
      'enable_inline_sidebar' => array(
        'label'             => esc_html__( 'Enable Inline Sidebar', 'nu_gm' ),
        'type'              => 'yes_no_button',
        'option_category'   => 'basic_option',
        'options'           => array(
          'off' => esc_html__( "No", 'nu_gm' ),
          'on'  => esc_html__( 'Yes', 'nu_gm' ),
        ),
        'description'     => esc_html__( 'This enables an inline sidebar, which can be used for adding small text blurbs to highlight content.', 'nu_gm' ),
        'affects'           => array(
          '#et_pb_sidebar_content',
        ),
      ),
      'sidebar_content' => array(
        'label'           => esc_html__( 'Sidebar Content', 'nu_gm' ),
        'type'            => 'textarea',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Please provide brief content for the inline sidebar (line breaks will be ommitted).', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $content                 = et_builder_replace_code_content_entities( $this->shortcode_content );
    $container_class         = $this->shortcode_atts['container_class'] ?: "contain-1120";
    $enable_inline_sidebar   = $this->shortcode_atts['enable_inline_sidebar'] == 'on' ? true : false;
    $sidebar_content         = $enable_inline_sidebar ? $this->shortcode_atts['sidebar_content'] : false;
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    $output  = '<section'.$module_id.$aria_label.' class="clearfix '.$container_class.' '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="gm-text-module">';
        if($sidebar_content) $output .= '<div class="standard-page"><div class="inline-sidebar"><div class="box">'.$sidebar_content.'</div></div></div>';
        $output .= $content;
      $output .= '</div>';
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Text;

<?php

class ET_Builder_Module_NU_GM_Accordion extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_accordion';
  
  function init() {
    $this->name               = esc_html__( 'Accordion', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->child_slug         = 'et_pb_nu_gm_accordion_item';
    $this->child_item_text    = esc_html__( 'Accordion Item', 'nu_gm' );
    $this->whitelisted_fields = array(
      'title',
      'format',
      'module_id',
    );
    $this->fields_defaults    = array(
      'format' => array( 1 ),
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
      'format' => array(
        'label'           => esc_html__( 'Accordian Style Format', 'nu_gm' ),
        'type'            => 'select',
        'required'        => true,
        'option_category' => 'basic_option',
        'options'         => array(
          1 => esc_html__( 'Light', 'nu_gm' ),
          2 => esc_html__( 'Light (Outlined)', 'nu_gm' ),
          3 => esc_html__( 'Dark', 'nu_gm' ),
        ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $format                  = $this->shortcode_atts['format'] ?: 1;
    $format_class            = 'expander' . $format;
    $inner_content           = $this->shortcode_content;
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    $output  = '<section'.$module_id.$aria_label.' class="clearfix contain-1120 '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="expander '.$format_class.'"  data-collapse="data-collapse">'.$inner_content.'</div>';
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Accordion;

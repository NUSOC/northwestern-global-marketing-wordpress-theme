<?php

// TODO: Implement custom build using Divi child elements
// TODO: Set dynamic default source
// TODO: Hide timeline URL field based on selected source type
class ET_Builder_Module_NU_GM_TimelineJS extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_timelinejs';

  function init() {
    $this->name               = esc_html__( 'TimelineJS', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    // $this->child_slug         = 'et_pb_nu_gm_timelinejs_item';
    // $this->child_item_text    = esc_html__( 'TimelineJS Item', 'nu_gm' );
    $this->whitelisted_fields = array(
      'title',
      'module_id',
      'source_type',
      'timeline_url',
    );
    $this->fields_defaults    = array(
      'source_type' => 'timeline_url',
    );
    if(isset($this->child_slug)) {
      $this->fields_defaults['source_type'] = 'divi';
    }
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
      'source_type' => array(
        'label'           => esc_html__( 'Width', 'nu_gm' ),
        'type'            => 'select',
        'required'        => true,
        'option_category' => 'basic_option',
        'options'         => array(
          'timeline_url'    => esc_html__( 'TimelineJS URL', 'nu_gm' ),
        ),
      ),
      'timeline_url' => array(
        'label'           => esc_html__( 'TimelineJS Embed URL', 'nu_gm' ),
        'type'            => 'url',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Please provide the TimelineJS Embed URL. You can generate this from a Google Sheet at <a href="'.esc_url('https://timeline.knightlab.com/#make').'">https://timeline.knightlab.com/#make</a>.', 'nu_gm' ),
      ),
    );
    if(isset($this->child_slug)) {
      $fields['source_type']['options']['divi'] = esc_html__( 'Build Custom Timeline', 'nu_gm' );
    }

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $source_type             = $this->shortcode_atts['source_type'] ?: 'timeline_url';
    if($source_type == 'timeline_url') {
      $timeline_url          = esc_url($this->shortcode_atts['timeline_url']) ?: false;
    } else {
      $timeline_url          = false;
    }
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    $output  = '<section'.$module_id.$aria_label.' class="clearfix contain-1120 '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="gm-timelinejs-module">';
        switch($source_type) {
          case 'timeline_url':
            $output .= '<iframe src="'.$timeline_url.'" width="100%" height="650" title="TimelineJS Iframe" frameborder="0"></iframe>';
          break;
          default:
          break;
        }
      $output .= '</div>';
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_TimelineJS;

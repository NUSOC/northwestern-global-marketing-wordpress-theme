<?php

class ET_Builder_Module_NU_GM_Statistics_Callout extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_statistics_callout';
  
  function init() {
    $this->name               = esc_html__( 'Statistics Callout', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->whitelisted_fields = array(
      'title',
      'stat_value_left',
      'stat_label_left',
      'stat_value_middle',
      'stat_label_middle',
      'stat_value_right',
      'stat_label_right',
      'module_id',
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
      'stat_value_left' => array(
        'label'           => esc_html__( 'Value (left)', 'nu_gm' ),
        'type'            => 'text',
        'required'        => false,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Value for the left stat.', 'nu_gm' ),
      ),
      'stat_label_left' => array(
        'label'           => esc_html__( 'Label (left)', 'nu_gm' ),
        'type'            => 'text',
        'required'        => false,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Label for the leftmost stat.', 'nu_gm' ),
      ),
      'stat_value_middle' => array(
        'label'           => esc_html__( 'Value (middle)', 'nu_gm' ),
        'type'            => 'text',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Value for the middle stat.', 'nu_gm' ),
      ),
      'stat_label_middle' => array(
        'label'           => esc_html__( 'Label (middle)', 'nu_gm' ),
        'type'            => 'text',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Label for the middle stat.', 'nu_gm' ),
      ),
      'stat_value_right' => array(
        'label'           => esc_html__( 'Value (right)', 'nu_gm' ),
        'type'            => 'text',
        'required'        => false,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Value for the right stat.', 'nu_gm' ),
      ),
      'stat_label_right' => array(
        'label'           => esc_html__( 'Label (right)', 'nu_gm' ),
        'type'            => 'text',
        'required'        => false,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Label for the rightmost stat.', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $stat_value_left         = $this->shortcode_atts['stat_value_left'] ?: false;
    $stat_label_left         = $this->shortcode_atts['stat_label_left'] ?: false;
    $stat_value_middle       = $this->shortcode_atts['stat_value_middle'];
    $stat_label_middle       = $this->shortcode_atts['stat_label_middle'];
    $stat_value_right        = $this->shortcode_atts['stat_value_right'] ?: false;
    $stat_label_right        = $this->shortcode_atts['stat_label_right'] ?: false;
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    $output  = '<section'.$module_id.$aria_label.' class="clearfix contain-1120 '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="stats-callout">'; // div.stats-callout start

        $output .= '<div><p>'; // div (left stat) start
          if($stat_value_left) $output .= '<span class="big">'.$stat_value_left.'</span>'; // stat value start / end
          if($stat_label_left) $output .= '<span class="small">'.$stat_label_left.'</span>'; // stat label start / end
        $output .= '</p></div>'; // div (left stat) end

        $output .= '<div><p>'; // div (middle stat) start
          $output .= '<span class="big">'.$stat_value_middle.'</span>'; // stat value start / end
          $output .= '<span class="small">'.$stat_label_middle.'</span>'; // stat label start / end
        $output .= '</p></div>'; // div (middle stat) end

        $output .= '<div><p>'; // div (right stat) start
          if($stat_value_right) $output .= '<span class="big">'.$stat_value_right.'</span>'; // stat value start / end
          if($stat_label_right) $output .= '<span class="small">'.$stat_label_right.'</span>'; // stat label start / end
        $output .= '</p></div>'; // div (right stat) end

      $output .= '</div>'; // div.stats-callout end
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Statistics_Callout;

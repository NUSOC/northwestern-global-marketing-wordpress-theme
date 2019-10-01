<?php

class ET_Builder_Module_NU_GM_Tabs extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_tabs';
  
  function init() {
    $this->name               = esc_html__( 'Tabs', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->child_slug         = 'et_pb_nu_gm_tabs_item';
    $this->child_item_text    = esc_html__( 'Tab', 'nu_gm' );
    $this->whitelisted_fields = array(
      'title',
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
    );

    return $fields;
  }

  function pre_shortcode_content() {
    // Reset / initialize tab content
    global $et_pb_nu_gm_tab_titles;
    $et_pb_nu_gm_tab_titles = array();

    global $et_pb_nu_gm_tab_group_id_prefix;
    $et_pb_nu_gm_tab_group_id_prefix = uniqid('nu_gm_tabs_').'-';
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    global $et_pb_nu_gm_tab_titles;
    global $et_pb_nu_gm_tab_group_id_prefix;

    $title            = $this->shortcode_atts['title'] ?: false;
    $module_id        = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $all_tabs_content = $this->shortcode_content;
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';
    
    $output  = '<section'.$module_id.$aria_label.' class="clearfix contain-1120 '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div id="tabs-container">'; // div#tabs-container start
        if(!empty($et_pb_nu_gm_tab_titles)) {
          $output .= '<ul id="tabs" role="tablist">'; // ul#tabs start
          foreach ($et_pb_nu_gm_tab_titles as $tab_key => $tab_title) {
            $tab_selected = $tab_key == 1 ? 'true' : 'false';
            $tab_class    = $tab_key == 1 ? 'active' : '';
            $output .= '<li role="presentation">';
              $output .= '<a aria-controls="'.$et_pb_nu_gm_tab_group_id_prefix.'tab-panel'.$tab_key.'" aria-selected="'.$tab_selected.'" class="'.$tab_class.'" href="#'.$et_pb_nu_gm_tab_group_id_prefix.'tab-panel'.$tab_key.'" id="'.$et_pb_nu_gm_tab_group_id_prefix.'tab'.$tab_key.'" role="tab">'.$tab_title.'</a>';
            $output .= '</li>';
          }
          $output .= '</ul>'; // ul#tabs end
        }
        $output .= '<div id="tab-content">'; // div#tab-content start
          $output .= $all_tabs_content;
        $output .= '</div>'; // div#tab-content end
      $output .= '</div>'; // div#tabs-container end
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Tabs;

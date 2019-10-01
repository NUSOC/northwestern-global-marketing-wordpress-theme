<?php

class ET_Builder_Module_NU_GM_Tabs_Item extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_tabs_item';
  
  function init() {
    $this->name               = esc_html__( 'Tab', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->type               = 'child';
    $this->custom_css_tab     = false;
    $this->child_title_var    = 'title';

    $this->whitelisted_fields = array(
      'title',
      'content',
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
        'label'           => esc_html__( 'Title Text', 'nu_gm' ),
        'type'            => 'text',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the tab title text.', 'nu_gm' ),
      ),
      'content' => array(
        'label'           => esc_html__( 'Content', 'nu_gm' ),
        'type'            => 'tiny_mce',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the tab content.', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    global $et_pb_nu_gm_tab_titles;
    global $et_pb_nu_gm_tab_group_id_prefix;
    $tab_key                          = count($et_pb_nu_gm_tab_titles) + 1;
    $tab_selected_display_style       = $tab_key == 1 ? 'block' : 'none';
    $et_pb_nu_gm_tab_titles[$tab_key] = $this->shortcode_atts['title'];
    $content                          = et_builder_replace_code_content_entities( $this->shortcode_content );
    
    // If $content has no HTML wrapper, generically wrap it in a <p>
    if($content == strip_tags($content))
      $content = '<p>'.$this->shortcode_atts['content'].'</p>';

    $output  = '<div aria-labelledby="'.$et_pb_nu_gm_tab_group_id_prefix.'tab'.$tab_key.'" id="'.$et_pb_nu_gm_tab_group_id_prefix.'tab-panel'.$tab_key.'" role="tabpanel" style="display:'.$tab_selected_display_style.';">'; // div#tabid start
      $output .= $content;
    $output .= '</div>'; // div#tabid end

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Tabs_Item;

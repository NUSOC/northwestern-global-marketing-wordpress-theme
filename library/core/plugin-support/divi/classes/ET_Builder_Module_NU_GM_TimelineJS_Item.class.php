<?php
// TODO: Enable creation of individual timeline items via children
// SEE:  https://timeline.knightlab.com/docs/json-format.html#json-slide
class ET_Builder_Module_NU_GM_TimelineJS_Item extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_timelinejs_item';

  function init() {
    $this->name               = esc_html__( 'TimelineJS Item', 'nu_gm' );
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
        'description'     => esc_html__( 'This defines the title text, which can be clicked on to open the accordion.', 'nu_gm' ),
      ),
      'content' => array(
        'label'           => esc_html__( 'Content', 'nu_gm' ),
        'type'            => 'tiny_mce',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the descriptive text, which is revealed when the accordion is opened.', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'];
    $content                 = $this->shortcode_atts['content'] ?: '';
    $this->shortcode_content = et_builder_replace_code_content_entities( $this->shortcode_content );
    $content                 = $this->shortcode_content;

    $output  = '<h4>'.$title.'</h4>'; // h4 start / end
    $output .= '<div>'.$content.'</div>'; // div (content) start / end

    return $output;
  }
}
new ET_Builder_Module_NU_GM_TimelineJS_Item;

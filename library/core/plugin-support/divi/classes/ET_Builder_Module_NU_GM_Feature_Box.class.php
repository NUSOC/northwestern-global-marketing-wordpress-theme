<?php

class ET_Builder_Module_NU_GM_Feature_Box extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_feature_box';

  function init() {
    $this->name               = esc_html__( 'Feature Box', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->child_slug         = 'et_pb_nu_gm_feature_box_item';
    $this->child_item_text    = esc_html__( 'Feature Box Item', 'nu_gm' );
    $this->whitelisted_fields = array(
      'columns',
      'title',
      'module_id',
    );
    $this->fields_defaults    = array(
      'columns' => array( 2 ),
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
      'columns' => array(
        'label'           => esc_html__( 'Number of Columns', 'nu_gm' ),
        'type'            => 'select',
        'required'        => true,
        'option_category' => 'basic_option',
        'options'         => array(
          2 => esc_html__( 'Two', 'nu_gm' ),
          3  => esc_html__( 'Three', 'nu_gm' ),
        ),
      ),
    );

    return $fields;
  }

  function get_column_class( $columns ) {
    switch($columns) {
      case 2:
        $class = 'feature-two-col';
        break;
      case 3:
        $class = 'feature-three-col';
        break;
      default:
        $class = 'feature-two-col';
        break;
    }

    return $class;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $columns                 = $this->shortcode_atts['columns'] ?: 2;
    $column_class            = $this->get_column_class($columns);
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';
    $inner_content           = preg_replace_callback(
                                '|data\-origin\-src="([^"]+)"|',
                                function( $matches ) use ( $columns ) {
                                  $src_id = nu_gm_get_attachment_id_from_src( $matches[1] );
                                  if( $src_id == false )
                                    return 'src="'.$matches[1].'"';
                                  $src    = wp_get_attachment_image_src( $src_id, 'feature-box-'.$columns )[0];
                                  return 'src="'.$src.'"';
                                },
                                $this->shortcode_content
                              );

    $output  = '<section'.$module_id.$aria_label.' class="clearfix contain-1120 '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="'.$column_class.'">'.$inner_content.'</div>';
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Feature_Box;

<?php

class ET_Builder_Module_NU_GM_Feature_Box_Item extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_feature_box_item';

  function init() {
    $this->name               = esc_html__( 'Feature Box Item', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->type               = 'child';
    $this->custom_css_tab     = false;
    $this->child_title_var    = 'title';

    $this->whitelisted_fields = array(
      'title',
      'src',
      'alt',
      'content',
      'link_url',
      'link_text',
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
        'description'     => esc_html__( 'This defines the title text.', 'nu_gm' ),
      ),
      'src' => array(
        'label'              => esc_html__( 'Image URL', 'nu_gm' ),
        'type'               => 'upload',
        'required'           => true,
        'option_category'    => 'basic_option',
        'upload_button_text' => esc_attr__( 'Upload an image', 'nu_gm' ),
        'choose_text'        => esc_attr__( 'Choose an Image', 'nu_gm' ),
        'update_text'        => esc_attr__( 'Set As Image', 'nu_gm' ),
        'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display.', 'nu_gm' ),
      ),
      'alt' => array(
        'label'           => esc_html__( 'Image Alternative Text', 'nu_gm' ),
        'type'            => 'text',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the image ALT text. A short description of your image can be placed here.', 'nu_gm' ),
      ),
      'content' => array(
        'label'           => esc_html__( 'Content', 'nu_gm' ),
        'type'            => 'textarea',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the descriptive text.', 'nu_gm' ),
      ),
      'link_url' => array(
        'label'           => esc_html__( 'Link URL', 'nu_gm' ),
        'type'            => 'url',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the URL this feature box will link to.', 'nu_gm' ),
      ),
      'link_text' => array(
        'label'           => esc_html__( 'Link Text', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the text in the optional link button.', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'];
    $src                     = $this->shortcode_atts['src'];
    $alt                     = $this->shortcode_atts['alt'] ?: $this->shortcode_atts['title'];
    $link_url                = $this->shortcode_atts['link_url'] ?: false;
    $link_text               = $this->shortcode_atts['link_text'] ?: "Read More";
    $this->shortcode_content = et_builder_replace_code_content_entities( $this->shortcode_content );
    $content                 = $this->shortcode_content;
    
    // If $content has no HTML wrapper, generically wrap it in a <p>
    if($content == strip_tags($content)) {
      $content = '<p>'.$this->shortcode_atts['content'].'</p>';
    }

    $output  = '<article class="feature-box '.preg_replace('|_|', '-', $this->slug).'" aria-label="'.$title.'">'; // article.feature-box start
      if($link_url) $output .= '<a href="'.$link_url.'" class="feature-box-image-link" tabindex="-1" title="'.$alt.'">'; // a.feature-box-image-link start
        $output .= '<img data-origin-src="'.$src.'" alt="'.$alt.'">'; // img start / end
      if($link_url) $output .= '</a>'; // a.feature-box-image-link end
      $output .= '<div class="feature-copy">'; // div.feature-copy start
        $output .= '<h4>'.$title.'</h4>'; // title start / end
        $output .= $content; // content start / end
      $output .= '</div>'; // div.feature-copy end
      if($link_url) $output .= '<a href="'.$link_url.'" class="button" aria-label="'.str_replace('"', '', $title).'">'.$link_text.'</a>'; // a.button start / end
    $output .= '</article>'; // article.feature-box end

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Feature_Box_Item;

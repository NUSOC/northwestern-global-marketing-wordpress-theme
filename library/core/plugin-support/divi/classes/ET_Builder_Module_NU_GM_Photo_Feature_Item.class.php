<?php

class ET_Builder_Module_NU_GM_Photo_Feature_Item extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_photo_feature_item';

  function init() {
    $this->name               = esc_html__( 'Photo Feature Item', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->type               = 'child';
    $this->custom_css_tab     = false;
    $this->child_title_var    = 'title';

    $this->whitelisted_fields = apply_filters(
      'nu_gm_divi_module_whitelisted_fields',
      array(
        'title',
        'src',
        'alt',
        'link_url',
        'call_to_action',
        'description_front',
        'description_back',
      ),
      self::$module_slug
    );

    $this->fields_defaults    = apply_filters( 'nu_gm_divi_module_fields_defaults', array(), self::$module_slug );
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
        'description'     => esc_html__( 'This defines the title text, which appears on both the front & back of the photo feature.', 'nu_gm' ),
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
      'link_url' => array(
        'label'           => esc_html__( 'URL', 'nu_gm' ),
        'type'            => 'url',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the URL this photo feature will link to.', 'nu_gm' ),
      ),
      'call_to_action' => array(
        'label'           => esc_html__( 'Call to Action Text', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the front call to action text.', 'nu_gm' ),
      ),
      'description_front' => array(
        'label'           => esc_html__( 'Description (Front)', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the front descriptive text.', 'nu_gm' ),
      ),
      'description_back' => array(
        'label'           => esc_html__( 'Description (Back)', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the back descriptive text.', 'nu_gm' ),
      ),
    );

    // Enable additional fields to be added via filter
    $fields = apply_filters( 'nu_gm_divi_module_fields', $fields, self::$module_slug );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'];
    $src                     = $this->shortcode_atts['src'];
    $alt                     = $this->shortcode_atts['alt'] ?: $this->shortcode_atts['title'];
    $link_url                = $this->shortcode_atts['link_url'] ?: false;
    $call_to_action          = $this->shortcode_atts['call_to_action'] ?: false;
    $description_front       = $this->shortcode_atts['description_front'] ?: false;
    $description_back        = $this->shortcode_atts['description_back'] ?: false;

    $output  = '<article class="photo-feature '.preg_replace('|_|', '-', $this->slug).'" aria-label="'.$title.'">'; // article.photo-feature start
      if($link_url) $output .= '<a href="'.$link_url.'" title="'.$title.'">'; // a start
        $output .= '<div class="front">'; // div.front start
          $output .= '<img data-origin-src="'.$src.'" alt="'.$alt.'">'; // img start / end
          $output .= '<div class="text-over-image">'; // div.text-over-image start
            $output .= '<h4>'.$title.'</h4>'; // title start / end
            if($description_front) $output .= '<p>'.$description_front.'</p>'; // front description start / end
            if($call_to_action && $link_url) $output .= '<p class="link">'.$call_to_action.'</p>'; // call to action start / end
          $output .= '</div>'; // div.text-over-image end
        $output .= '</div>'; // div.front end
        $output .= '<div class="back">'; // div.back start
          $output .= '<div class="back-text">'; // div.back-text start
            $output .= '<h4>'.$title.'</h4>'; // title start / end
            if($description_back) $output .= '<p>'.$description_back.'</p>'; // back description start / end
            if($call_to_action && $link_url) $output .= '<p class="link">'.$call_to_action.'</p>'; // p.link start / end
          $output .= '</div>'; // div.back-text end
        $output .= '</div>'; // div.back end
      if($link_url) $output .= '</a>'; // a end
    $output .= '</article>'; // article.photo-feature end

    // Apply filters to output
    $output = apply_filters( 'nu_gm_divi_module_shortcode_output', $output, self::$module_slug, $this->shortcode_atts );

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Photo_Feature_Item;

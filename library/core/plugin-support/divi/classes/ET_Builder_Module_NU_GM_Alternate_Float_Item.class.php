<?php

class ET_Builder_Module_NU_GM_Alternate_Float_Item extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_alternate_float_item';

  function init() {
    $this->name               = esc_html__( 'Alternate Float Item', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->type               = 'child';
    $this->custom_css_tab     = false;
    $this->child_title_var    = 'title';

    $this->whitelisted_fields = array(
      'title',
      'src',
      'alt',
      'link_url',
      'content',
      'caption',
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
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the optional title text.', 'nu_gm' ),
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
        'required'           => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the image ALT text. A short description of your image can be placed here.', 'nu_gm' ),
      ),
      'caption' => array(
        'label'           => esc_html__( 'Image Lightbox Caption', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the image caption text when displayed in a lightbox. A short description of your image can be placed here.', 'nu_gm' ),
      ),
      'link_url' => array(
        'label'           => esc_html__( 'Image Link URL', 'nu_gm' ),
        'type'            => 'url',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the URL this image will link to.', 'nu_gm' ),
      ),
      'content' => array(
        'label'           => esc_html__( 'Content', 'nu_gm' ),
        'type'            => 'tiny_mce',
        'required'        => true,
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the descriptive text.', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    global $et_pb_nu_gm_alternating_float_side;
    $et_pb_nu_gm_alternating_float_side  = (empty( $et_pb_nu_gm_alternating_float_side ) || $et_pb_nu_gm_alternating_float_side == 'right') ? 'left' : 'right';
    $alternating_float_class = 'image-'.$et_pb_nu_gm_alternating_float_side;

    $title                   = $this->shortcode_atts['title'];
    $src_id                  = nu_gm_get_attachment_id_from_src( $this->shortcode_atts['src'] );
    $src                     = $src_id == false ? $this->shortcode_atts['src'] : wp_get_attachment_image_src( $src_id, 'feature-box-2' )[0];
    $large_src               = $src_id == false ? $this->shortcode_atts['src'] : wp_get_attachment_image_src( $src_id, 'large' )[0];
    $alt                     = $this->shortcode_atts['alt'] ?: $this->shortcode_atts['title'];
    $link_url                = $this->shortcode_atts['link_url'] ?: false;
    $content                 = et_builder_replace_code_content_entities( $this->shortcode_content );
    $caption                 = $this->shortcode_atts['caption'] ?: '';

    // If $content has no HTML wrapper, generically wrap it in a <p>
    if($content == strip_tags($content))
      $content = '<p>'.$this->shortcode_atts['content'].'</p>';

    // Create Image Side Output
    $img_output   = '<div class="'.$alternating_float_class.' '.preg_replace('|_|', '-', $this->slug).'">'; // div.image-{left/right} start
      $img_output .= '<a class="alternate-photo-img-link alternate-photo-fancybox-img-link fancybox" alt="'.$alt.'" title="'.$title.'" caption="'.$caption.'" href="'.$large_src.'" rel="temporary">'; // a.alternate-photo-fancybox-img-link start
      if($link_url) $img_output  .= '<a class="alternate-photo-img-link" title="'.$alt.'" href="'.$link_url.'">'; // a.alternate-photo-float-image-link start
        $img_output  .= '<img src="'.$src.'" alt="'.$alt.'">'; // img start / end
      $img_output  .= '</a>'; // a.alternate-photo-float-img-link end
    $img_output  .= '</div>'; // div.image-{left/right} end

    // Create Text Side Output
    $text_output  = '<div class="text">'; // div.text start
      if(isset($title) && strlen($title)) { $text_output .= '<h4>'.$title.'</h4>'; } // title start / end
      $text_output .= $content; // content start / end
    $text_output .= '</div>'; // div.text end

    $output  = '<div class="alternate-photo-float">'; // div.alternate-photo-float start
      $output .= $et_pb_nu_gm_alternating_float_side == 'left' ? $img_output.$text_output : $text_output.$img_output; // Insert text and image in correct order based on current alternating side
    $output .= '</div>'; // div.alternate-photo-float end

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Alternate_Float_Item;

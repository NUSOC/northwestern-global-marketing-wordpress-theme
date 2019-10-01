<?php

class ET_Builder_Module_NU_GM_Photo_Grid_Item extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_photo_grid_item';

  function init() {
    $this->name               = esc_html__( 'Photo Grid Item', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->type               = 'child';
    $this->custom_css_tab     = false;
    $this->child_title_var    = 'alt';

    $this->whitelisted_fields = array(
      'src',
      'alt',
      'caption',
      'title',
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
      'caption' => array(
        'label'           => esc_html__( 'Image Lightbox Caption', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the image caption text when displayed in a lightbox. A short description of your image can be placed here.', 'nu_gm' ),
      ),
      'title' => array(
        'label'           => esc_html__( 'Title Text', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the title text.', 'nu_gm' ),
      ),
      'content' => array(
        'label'           => esc_html__( 'Description', 'nu_gm' ),
        'type'            => 'textarea',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the descriptive text.', 'nu_gm' ),
      ),
      'link_url' => array(
        'label'           => esc_html__( 'URL', 'nu_gm' ),
        'type'            => 'url',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the URL this photo feature will link to.', 'nu_gm' ),
      ),
      'link_text' => array(
        'label'           => esc_html__( 'Link Text', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'This defines the text in the optional link.', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $src_id                  = nu_gm_get_attachment_id_from_src( $this->shortcode_atts['src'] );
    $src                     = $src_id == false ? $this->shortcode_atts['src'] : wp_get_attachment_image_src( $src_id, 'people-medium' )[0];
    $large_src               = $src_id == false ? $this->shortcode_atts['src'] : wp_get_attachment_image_src( $src_id, 'large' )[0];
    $alt                     = $this->shortcode_atts['alt'] ?: '';
    $caption                 = $this->shortcode_atts['caption'] ?: '';
    $title                   = $this->shortcode_atts['title'] ?: '';
    $link_url                = $this->shortcode_atts['link_url'] ?: false;
    $link_text               = $this->shortcode_atts['link_text'] ?: false;
    $content                 = et_builder_replace_code_content_entities( $this->shortcode_atts['content'] );

    $output  = '<article class="photo-box '.preg_replace('|_|', '-', $this->slug).'" aria-label="'.$alt.'">'; // article.photo-box start
                    $output .= '<a class="photo-box-img-link photo-box-fancybox-img-link fancybox" alt="'.$alt.'" title="'.$title.'" caption="'.$caption.'" href="'.$large_src.'" rel="temporary">'; // a.photo-box-fancybox-img-link start
      if($link_url) $output .= '<a class="photo-box-img-link" alt="'.$alt.'" title="'.$title.'" href="'.$link_url.'">'; // a.photo-box-link start
                    $output .= '<img src="'.$src.'" alt="'.$alt.'">'; // img start / end
                    $output .= '</a>'; // a end
      if($title)    $output .= '<h4>'.$title.'</h4>'; // title start / end
      if($content)  $output .= '<p>'.$content.'</p>'; // content start / end
      if($link_url && $link_text) $output .= '<p class="link"><a href="'.$link_url.'">'.$link_text.'</a></p>'; // p.link start / end
    $output .= '</article>'; // article.photo-box end

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Photo_Grid_Item;

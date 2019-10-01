<?php

class ET_Builder_Module_NU_GM_Curated_Posts_Item extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_curated_posts_item';

  function init() {
    $this->name               = esc_html__( 'Post Item', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->type               = 'child';
    $this->custom_css_tab     = false;
    $this->child_title_var    = 'post_title';

    $this->whitelisted_fields = array(
      'post_id',
      'post_title',
    );

    $this->fields_defaults    = array(
      'post_id'    => 0,
      'post_title' => 'Post',
    );
    $this->advanced_options   = array();
  }

  static function get_module_slug() {
    return self::$module_slug;
  }

  function get_fields() {
    $post_options = array(
      0 => '- none -',
    );
    $posts = query_posts(array(
      'post_type' => 'post',
    ));
    wp_reset_postdata();
    foreach ($posts as $post) {
      $post_options[$post->ID] = esc_html__($post->post_title, 'nu_gm');
    }
    $fields = array(
      'post_id' => array(
        'label'           => esc_html__( 'Post', 'nu_gm' ),
        'type'            => 'select',
        'required'        => true,
        'option_category' => 'basic_option',
        'options'         => $post_options,
        'description'     => esc_html__( 'Please select a post.', 'nu_gm' ),
        'class'           => array( 'et-pb-nu-gm-curated-posts-item-id' ),
        'attributes'      => array(
          'onchange'        => 'jQuery(".et-pb-nu-gm-curated-posts-item-title").val(jQuery(".et-pb-nu-gm-curated-posts-item-id").find("option[value="+jQuery(".et-pb-nu-gm-curated-posts-item-id").val()+"]").text());',
        ),
      ),
      'post_title' => array(
        'type'            => 'hidden',
        'class'           => array( 'et-pb-nu-gm-curated-posts-item-title' ),
      ),
    );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    if($post_id = $this->shortcode_atts['post_id']) {
      global $et_pb_nu_gm_curated_posts;
      $et_pb_nu_gm_curated_posts[] = $post_id;
    }
    $output = '';
    return $output;
  }
}
new ET_Builder_Module_NU_GM_Curated_Posts_Item;

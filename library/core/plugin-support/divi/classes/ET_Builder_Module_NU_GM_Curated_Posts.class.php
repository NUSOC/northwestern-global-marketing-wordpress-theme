<?php

class ET_Builder_Module_NU_GM_Curated_Posts extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_curated_posts';

  function init() {
    $this->name               = esc_html__( 'Curated Posts', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->child_slug         = 'et_pb_nu_gm_curated_posts_item';
    $this->child_item_text    = esc_html__( 'Post Item', 'nu_gm' );
    $this->whitelisted_fields = array(
      'title',
      'module_id',
      'display_format',
    );
    $this->fields_defaults    = array(
      'display_format' => 'feature-box',
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
      'display_format' => array(
        'label'           => esc_html__( 'Display Format', 'nu_gm' ),
        'type'            => 'select',
        'required'        => true,
        'option_category' => 'basic_option',
        'options'         => array(
          'feature-box'   => esc_html__( 'Feature Box', 'nu_gm' ),
          'photo-feature' => esc_html__( 'Photo Feature', 'nu_gm' ),
        ),
      ),
    );

    return $fields;
  }

  function get_column_class( $columns, $display_format ) {
    switch($display_format) {
      case 'feature-box':
        $columns_text = ($columns == 2) ? 'two' : 'three';
        $class = 'feature-'.$columns_text.'-col';
        break;
      case 'photo-feature':
        $class = 'photo-feature-'.$columns.'-across';
        break;
    }

    return $class;
  }

  function pre_shortcode_content() {
    global $et_pb_nu_gm_curated_posts;
    $et_pb_nu_gm_curated_posts = array();
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    global $et_pb_nu_gm_curated_posts;

    $title                   = $this->shortcode_atts['title'] ?: false;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $display_format          = $this->shortcode_atts['display_format'];
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    // If no posts have been selected, don't output anything
    if(empty($et_pb_nu_gm_curated_posts)) {
      return '';
    }

    // Query posts
    $posts_query = new WP_Query(array(
      'post_type' => 'post',
      'post__in' => $et_pb_nu_gm_curated_posts,
    ));

    // Generate output
    $posts_output = array();
    foreach ($et_pb_nu_gm_curated_posts as $et_pb_nu_gm_curated_post_id) {
      $posts_output[$et_pb_nu_gm_curated_post_id] = '';
    }
    ?>
      <?php if ($posts_query->have_posts()) : while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
        <?php ob_start(); ?>
        <?php get_template_part( 'nu-gm-formats/format', $display_format ); ?>
        <?php $posts_output[get_the_ID()] = ob_get_contents(); ?>
        <?php ob_end_clean(); ?>
      <?php endwhile; endif; ?>
    <?php
    wp_reset_postdata();
    $inner_content = implode('', $posts_output);

    $num_posts               = count($posts_output);
    $columns                 = $num_posts % 3 == 0 ? 3 : 2;
    $column_class            = $this->get_column_class($columns, $display_format);
    $section_class           = ($display_format == 'photo-feature') ? 'contain-1440' : 'contain-1120';

    $output = '<section'.$module_id.$aria_label.' class="clearfix '.$section_class.' '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="'.$column_class.'" itemscope itemtype="http://schema.org/ItemList">'.$inner_content.'</div>';
    $output .= '</section>';

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Curated_Posts;

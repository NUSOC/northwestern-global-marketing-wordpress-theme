<?php

class ET_Builder_Module_NU_GM_Recent_Posts extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_recent_posts';

  function init() {
    $this->name               = esc_html__( 'Recent Posts', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->whitelisted_fields = apply_filters(
      'nu_gm_divi_module_whitelisted_fields',
      array(
        'title',
        'max_items',
        'columns',
        'display_format',
        'module_id',
      ),
      self::$module_slug
    );
    $this->fields_defaults    = apply_filters(
      'nu_gm_divi_module_fields_defaults',
      array(
        'title'          => __( 'Recent Posts', 'nu_gm' ),
        'max_items'      => 6,
        'display_format' => 'feature-box',
      ),
      self::$module_slug
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
      'max_items' => array(
        'label'             => esc_html__( 'Number of Posts to Display', 'nu_gm' ),
        'type'              => 'range',
        'required'          => true,
        'default'           => 6,
        'number_validation' => true,
        'range_settings'    => array(
          'min'  => 3,
          'max'  => 12,
          'step' => 3,
        ),
        'option_category'   => 'basic_option',
        'description'       => esc_html__( 'This defines the maximum number of posts that will be displayed.', 'nu_gm' ),
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

    // Enable additional fields to be added via filter
    $fields = apply_filters( 'nu_gm_divi_module_fields', $fields, self::$module_slug );

    return $fields;
  }

  function get_column_class( $columns, $display_format ) {
    $class = '';
    switch($display_format) {
      case 'feature-box':
        $columns_text = ($columns == 2) ? 'two' : 'three';
        $class = 'feature-'.$columns_text.'-col';
        break;
      case 'photo-feature':
        $class = 'photo-feature-'.$columns.'-across';
        break;
    }

    $class = apply_filters( 'nu_gm_divi_module_column_class', $class, $columns, $display_format, self::$module_slug );

    return $class;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $max_items               = $this->shortcode_atts['max_items'] ?: 6;
    $display_format          = $this->shortcode_atts['display_format'] ?: 'feature-box';
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    // Query posts
    $posts_query = new WP_Query(array(
      'post_type' => 'post',
      'posts_per_page' => $max_items,
    ));

    // Generate output
    $num_posts = 0;
    ob_start();
    ?>
      <?php if ($posts_query->have_posts()) : while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
        <?php get_template_part( 'nu-gm-formats/format', $display_format ); ?>
        <?php $num_posts++; ?>
      <?php endwhile; endif; ?>
    <?php
    $inner_content .= ob_get_contents();
    ob_end_clean();

    $columns                 = $num_posts % 3 == 0 ? 3 : 2;
    $column_class            = $this->get_column_class($columns, $display_format);
    $section_class           = ($display_format == 'photo-feature') ? 'contain-1440' : 'contain-1120';

    $output  = '<section'.$module_id.$aria_label.' class="clearfix '.$section_class.' '.preg_replace('|_|', '-', $this->slug).'">';
      if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
      $output .= '<div class="'.$column_class.'" itemscope itemtype="http://schema.org/ItemList">'.$inner_content.'</div>';
    $output .= '</section>';

    wp_reset_postdata();

    // Apply filters to output
    $output = apply_filters( 'nu_gm_divi_module_shortcode_output', $output, self::$module_slug, $this->shortcode_atts );

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Recent_Posts;

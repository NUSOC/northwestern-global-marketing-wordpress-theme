<?php

class ET_Builder_Module_NU_GM_Events extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_events';

  function init() {
    $this->name               = esc_html__( 'Upcoming Events', 'nu_gm' );
    $this->slug               = $this->get_module_slug();
    $this->fullwidth          = true;
    $this->custom_css_tab     = false;
    $this->whitelisted_fields = apply_filters(
      'nu_gm_divi_module_whitelisted_fields',
      array(
        'title',
        'max_items',
        'module_id',
      ),
      self::$module_slug
    );
    $this->fields_defaults    = apply_filters(
      'nu_gm_divi_module_fields_defaults',
      array(
        'title'          => __( 'Upcoming Events', 'nu_gm' ),
        'max_items'      => 6,      ),
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
        'label'             => esc_html__( 'Number of Events to Display', 'nu_gm' ),
        'type'              => 'range',
        'required'          => true,
        'default'           => 6,
        'number_validation' => true,
        'range_settings'    => array(
          'min'  => 2,
          'max'  => 12,
          'step' => 2,
        ),
        'option_category'   => 'basic_option',
        'description'       => esc_html__( 'This defines the maximum number of events that will be displayed.', 'nu_gm' ),
      ),
    );

    // Enable additional fields to be added via filter
    $fields = apply_filters( 'nu_gm_divi_module_fields', $fields, self::$module_slug );

    return $fields;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title                   = $this->shortcode_atts['title'] ?: false;
    $max_items               = $this->shortcode_atts['max_items'] ?: 6;
    $module_id               = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $aria_label              = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    // Query posts
    $posts_query = new WP_Query(array(
      'post_type' => 'nu_gm_event',
      'posts_per_page' => $max_items,
      'nu_gm_event_enable_upcoming_filters' => true,
    ));
    $posts_query = nu_gm_event_update_query( $posts_query, true );

    // Generate output
    $num_posts = 0;
    ob_start();
    ?>
      <?php if ($posts_query->have_posts()) : while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
        <?php get_template_part( 'nu-gm-formats/format', 'event' ); ?>
        <?php $num_posts++; ?>
      <?php endwhile; ?>
      <?php else: ?>
        <?php ob_end_clean(); return ''; ?>
      <?php endif; ?>
    <?php
    $inner_content .= ob_get_contents();
    ob_end_clean();

    $section_class           = 'contain-1120';
    $output                  = '';

    if(!empty($inner_content)) {
      $output .= '<section'.$module_id.$aria_label.' class="clearfix '.$section_class.' '.preg_replace('|_|', '-', $this->slug).'">';
        if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
        $output .= '<div class="event-list-wrapper standard-page" itemscope itemtype="http://schema.org/ItemList"><div class="event-list">'.$inner_content.'</div></div>';
      $output .= '</section>';
    }

    wp_reset_postdata();

    // Apply filters to output
    $output = apply_filters( 'nu_gm_divi_module_shortcode_output', $output, self::$module_slug, $this->shortcode_atts );

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Events;

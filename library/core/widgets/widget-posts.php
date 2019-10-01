<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');
  
add_action( 'widgets_init', function(){
  register_widget( 'GM_Posts_Widget' );
});

/**
 * Adds GM Posts widget.
 */
class GM_Posts_Widget extends WP_Widget {
  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'GM_Posts_Widget', // Base ID
      __('Blog Posts GM Widget (homepage only)', 'nu_gm'), // Name
      array( 'classname' => 'widget_gm_posts', 'description' => __( 'Display a Blog Feed', 'nu_gm' ), ) // Args
    );
    $this->alt_option_name = 'widget_gm_posts';

    // Enable caching
    add_action( 'save_post', array($this, 'flush_widget_cache') );
    add_action( 'deleted_post', array($this, 'flush_widget_cache') );
    add_action( 'switch_theme', array($this, 'flush_widget_cache') );
  }
  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  function widget( $args, $instance ) {
    // Retrieve cached widget content
    $cache = get_transient($this->id);

    // if ( !empty( $cache ) ) {
    //   echo $cache;
    //   return;
    // }

    // Add widget-fullwidth if needed
    $args['before_widget'] = preg_replace('/\A\s*<div/miA', '<section', $args['before_widget']);
    $args['before_widget'] = preg_replace('/class="widget/mi', 'class="widget widget-fullwidth', $args['before_widget']);
    $args['after_widget'] = preg_replace('/<\/div>\s*\z/miD', '</section>', $args['after_widget']);

    $output = '';
    $output .= $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      $output .= $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }

    // Construct custom query
    $max_items = $instance['max_items'] ?: 6;
    $posts_query = new WP_Query(array(
      'post_type' => 'post',
      'posts_per_page' => $max_items,
    ));

    // Begin output
    ob_start();
    ?>
    <?php echo nu_gm_post_format_wrapper('start', 'post', ' itemscope itemtype="http://schema.org/ItemList"'); ?>
        <?php if ($posts_query->have_posts()) : while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
          <?php get_template_part( 'nu-gm-formats/format', get_theme_mod('post_list_format_setting', 'photo-feature') ); ?>
        <?php endwhile; ?>
        <p class="more-posts-page-link-wrapper content link" style="text-align:center;"><a class="more-posts-page-link button" href="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>" title="View More Posts">More Posts</a></p>
        <?php endif; ?>
    <?php echo nu_gm_post_format_wrapper('end', 'post'); ?>

    <?php
    $output .= ob_get_contents();
    ob_end_clean();

    $output .= $args['after_widget'];

    echo $output;

    // Cache content
    $cache = $output;
    set_transient($this->id, $cache, $instance[ 'cache_lifetime' ] * 60);
  }
  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'Recent Posts', 'nu_gm' );
    }
    if ( isset( $instance[ 'max_items' ] ) ) {
      $max_items = $instance[ 'max_items' ];
    }
    else {
      $max_items = 6;
    }
    if ( isset( $instance[ 'cache_lifetime' ] ) ) {
      $cache_lifetime = $instance[ 'cache_lifetime' ];
    }
    else {
      $cache_lifetime = 60;
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'max_items' ); ?>"><?php _e( 'Maximum Number of Items to Show:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="number" value="<?php echo esc_attr( $max_items ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'cache_lifetime' ); ?>"><?php _e( 'Minutes to Cache Output:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'cache_lifetime' ); ?>" name="<?php echo $this->get_field_name( 'cache_lifetime' ); ?>" type="number" value="<?php echo esc_attr( $cache_lifetime ); ?>">
    </p>
    <?php 
  }
  /**
   * Flush widget cache
   */
  function flush_widget_cache() {
    delete_transient($this->id);
  }
  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  function update( $new_instance, $old_instance ) {
    // Update instance with new values
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['max_items'] = ( ! empty( $new_instance['max_items'] ) ) ? strip_tags( $new_instance['max_items'] ) : 6;
    $instance['cache_lifetime'] = ( ! empty( $new_instance['cache_lifetime'] ) ) ? strip_tags( $new_instance['cache_lifetime'] ) : 60;
    
    // Flush widget cache
    $this->flush_widget_cache();
    
    return $instance;
  }
} // class GM_Posts_Widget
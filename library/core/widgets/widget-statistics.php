<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');
  
add_action( 'widgets_init', function(){
  register_widget( 'GM_Statistics_Widget' );
});  

/**
 * Adds GM Statistics Callout widget.
 */
class GM_Statistics_Widget extends WP_Widget {
  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'GM_Statistics_Widget', // Base ID
      __('Stats Callout Widget (homepage only)', 'nu_gm'), // Name
      array( 'classname' => 'widget_gm_statistics', 'description' => __( 'Display a GM styled statistics callout', 'nu_gm' ), ) // Args
    );
    $this->alt_option_name = 'widget_gm_statistics';
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
    // Add widget-fullwidth
    $args['before_widget'] = preg_replace('/\A\s*<div/miA', '<section', $args['before_widget']);
    $args['before_widget'] = preg_replace('/class="widget/mi', 'class="widget widget-fullwidth', $args['before_widget']);
    $args['after_widget'] = preg_replace('/<\/div>\s*\z/miD', '</section>', $args['after_widget']);

    $output = '';
    $output .= $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      $output .= $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }

    // Begin output
    ob_start();
    ?>

    <div class="landing-page">
      <div class="stats-callout">
        <div>
          <p><span class="big"><?php echo $instance[ 'stat_1_value' ]; ?></span><span class="small"><?php echo $instance[ 'stat_1_label' ]; ?></span></p>
        </div>
        <div>
          <p><span class="big"><?php echo $instance[ 'stat_2_value' ]; ?></span><span class="small"><?php echo $instance[ 'stat_2_label' ]; ?></span></p>
        </div>
        <div>
          <p><span class="big"><?php echo $instance[ 'stat_3_value' ]; ?></span><span class="small"><?php echo $instance[ 'stat_3_label' ]; ?></span></p>
        </div>
      </div>
      <?php if(!empty($instance[ 'optional_link_url' ]) && !empty($instance[ 'optional_link_label' ])): ?>
        <p class="content" style="text-align:center;">
          <a class="button" href="<?php echo $instance[ 'optional_link_url' ]; ?>" title="<?php echo $instance[ 'optional_link_label' ]; ?>"><?php echo $instance[ 'optional_link_label' ]; ?></a>
        </p>
      <?php endif; ?>
    </div>

    <?php
    $output .= ob_get_contents();
    ob_end_clean();

    $output .= $args['after_widget'];

    echo $output;
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
      $title = '';
    }
    
    $stat_1_value = (isset($instance[ 'stat_1_value' ]) ? $instance[ 'stat_1_value' ] : '');
    $stat_1_label = (isset($instance[ 'stat_1_label' ]) ? $instance[ 'stat_1_label' ] : '');
    $stat_2_value = (isset($instance[ 'stat_2_value' ]) ? $instance[ 'stat_2_value' ] : '');
    $stat_2_label = (isset($instance[ 'stat_2_label' ]) ? $instance[ 'stat_2_label' ] : '');
    $stat_3_value = (isset($instance[ 'stat_3_value' ]) ? $instance[ 'stat_3_value' ] : '');
    $stat_3_label = (isset($instance[ 'stat_3_label' ]) ? $instance[ 'stat_3_label' ] : '');
    $optional_link_url = (isset($instance[ 'optional_link_url' ]) ? $instance[ 'optional_link_url' ] : '');
    $optional_link_label = (isset($instance[ 'optional_link_label' ]) ? $instance[ 'optional_link_label' ] : '');

    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <hr>
    <p>
      <label for="<?php echo $this->get_field_id( 'stat_1_value' ); ?>"><?php _e( 'Stat #1 Value:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'stat_1_value' ); ?>" name="<?php echo $this->get_field_name( 'stat_1_value' ); ?>" type="text" value="<?php echo esc_attr( $stat_1_value ); ?>">
      <label for="<?php echo $this->get_field_id( 'stat_1_label' ); ?>"><?php _e( 'Stat #1 Label:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'stat_1_label' ); ?>" name="<?php echo $this->get_field_name( 'stat_1_label' ); ?>" type="text" value="<?php echo esc_attr( $stat_1_label ); ?>">
    </p>
    <hr>
    <p>
      <label for="<?php echo $this->get_field_id( 'stat_2_value' ); ?>"><?php _e( 'Stat #2 Value:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'stat_2_value' ); ?>" name="<?php echo $this->get_field_name( 'stat_2_value' ); ?>" type="text" value="<?php echo esc_attr( $stat_2_value ); ?>">
      <label for="<?php echo $this->get_field_id( 'stat_2_label' ); ?>"><?php _e( 'Stat #2 Label:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'stat_2_label' ); ?>" name="<?php echo $this->get_field_name( 'stat_2_label' ); ?>" type="text" value="<?php echo esc_attr( $stat_2_label ); ?>">
    </p>
    <hr>
    <p>
      <label for="<?php echo $this->get_field_id( 'stat_3_value' ); ?>"><?php _e( 'Stat #3 Value:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'stat_3_value' ); ?>" name="<?php echo $this->get_field_name( 'stat_3_value' ); ?>" type="text" value="<?php echo esc_attr( $stat_3_value ); ?>">
      <label for="<?php echo $this->get_field_id( 'stat_3_label' ); ?>"><?php _e( 'Stat #3 Label:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'stat_3_label' ); ?>" name="<?php echo $this->get_field_name( 'stat_3_label' ); ?>" type="text" value="<?php echo esc_attr( $stat_3_label ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'optional_link_url' ); ?>"><?php _e( 'Optional Link URL:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'optional_link_url' ); ?>" name="<?php echo $this->get_field_name( 'optional_link_url' ); ?>" type="url" value="<?php echo esc_attr( $optional_link_url ); ?>">
      <label for="<?php echo $this->get_field_id( 'optional_link_label' ); ?>"><?php _e( 'Optional Link Text:', 'nu_gm' ); ?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'optional_link_label' ); ?>" name="<?php echo $this->get_field_name( 'optional_link_label' ); ?>" type="text" value="<?php echo esc_attr( $optional_link_label ); ?>">
    </p>
    <?php 
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
    $instance['stat_1_value'] = ( ! empty( $new_instance['stat_1_value'] ) ) ? strip_tags( $new_instance['stat_1_value'] ) : '';
    $instance['stat_1_label'] = ( ! empty( $new_instance['stat_1_label'] ) ) ? strip_tags( $new_instance['stat_1_label'] ) : '';
    $instance['stat_2_value'] = ( ! empty( $new_instance['stat_2_value'] ) ) ? strip_tags( $new_instance['stat_2_value'] ) : '';
    $instance['stat_2_label'] = ( ! empty( $new_instance['stat_2_label'] ) ) ? strip_tags( $new_instance['stat_2_label'] ) : '';
    $instance['stat_3_value'] = ( ! empty( $new_instance['stat_3_value'] ) ) ? strip_tags( $new_instance['stat_3_value'] ) : '';
    $instance['stat_3_label'] = ( ! empty( $new_instance['stat_3_label'] ) ) ? strip_tags( $new_instance['stat_3_label'] ) : '';
    $instance['optional_link_url'] = ( ! empty( $new_instance['optional_link_url'] ) ) ? strip_tags( $new_instance['optional_link_url'] ) : '';
    $instance['optional_link_label'] = ( ! empty( $new_instance['optional_link_label'] ) ) ? strip_tags( $new_instance['optional_link_label'] ) : '';
    
    return $instance;
  }
} // class GM_Statistics_Widget
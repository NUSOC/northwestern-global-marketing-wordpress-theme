<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');
  
  
add_action( 'widgets_init', function(){
  register_widget( 'PlanItPurple_Widget' );
}); 
/**
 * Adds PlanItPurple widget.
 */
class PlanItPurple_Widget extends WP_Widget {
  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'PlanItPurple_Widget', // Base ID
      __('PlanItPurple Widget', 'nu_gm'), // Name
      array( 'classname' => 'widget_planit_purple', 'description' => __( 'Display a PlanItPurple Feed', 'nu_gm' ), ) // Args
    );
    $this->alt_option_name = 'widget_planit_purple';

    // Enable caching
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

    if ( !empty( $cache ) ) {
      echo $cache;
      return;
    }

    $output = '';
    $output .= $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      $output .= $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }

    if(!empty($instance['pip_id'])) {
      // Construct feed URL
      if(empty($instance['days']))
        $instance['days'] = 0;
      $planitpurple_json_url = 'http://planitpurple.northwestern.edu/feed/json/'.$instance['pip_id'].'?days='.$instance['days'];
      if(!empty($instance['max_items']))
        $planitpurple_json_url .= '&max='.$instance['max_items'];

      // Fetch feed data
      $planitpurple_response = wp_remote_get($planitpurple_json_url, array('timeout' => 2));

      // Set PiP Feed Page URL
      $planitpurple_url = 'http://planitpurple.northwestern.edu/feed/'.$instance['pip_id'];

      // Begin output
      ob_start();
      ?>

      <?php if( !empty($planitpurple_response['body']) ): ?>
        <?php $planitpurple_events = json_decode($planitpurple_response['body']); ?>
        <div class="standard-page">
          <ul class="event-list" itemscope itemtype="http://schema.org/ItemList">
            <?php foreach ( $planitpurple_events as $ind => $event ): ?>
              <?php if(!empty($instance['max_items']) && $ind >= $instance['max_items']) break; ?>
              <?php $event_date = explode('-', $event->eventdate); ?>
              <li class="event-list-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/Event">
                <div class="event">
                  <div class="event-date">
                    <div class="month"><?php echo date('M', strtotime($event->eventdate)); ?></div>
                    <div class="day"><?php echo $event_date[2]; ?></div>
                    <div class="year"><?php echo $event_date[0]; ?></div>
                  </div>
                  <div class="event-description">
                    <h4 itemprop="name"><a href="<?php echo $event->url; ?>" itemprop="url mainEntityOfPage sameAs" target="_blank" title="View <?php echo $event->title; ?> on PlanItPurple"><?php echo $event->title; ?></a></h4>
                    <p class="event-time-location"><?php echo $event->start_time_display_format; ?></p>
                    <?php
                      // Calculate ISO 8601 Dates for microdata
                      $start_date = date_create($event->eventdate_ical_format.' CST');
                      $start_date_iso_8601 = $start_date->format('c');
                      $end_date = (empty($event->eventend_ical_format)) ? $start_date : date_create($event->eventend_ical_format.' CST');
                      $end_date_iso_8601 = $end_date->format('c');
                      $duration = $end_date->diff($start_date);
                      $duration_iso_8601 = $start_date->format('Y-m-d').'/'.$duration->format('P%dD%HH%II');
                    ?>
                    <span class="microdata-hidden" hidden style="display:none;visibility:hidden;">
                      <time datetime="<?php echo $start_date_iso_8601; ?>" itemprop="startDate" hidden></time>
                      <time datetime="<?php echo $end_date_iso_8601; ?>" itemprop="endDate" hidden></time>
                      <time datetime="<?php echo $duration_iso_8601; ?>" itemprop="duration" hidden></time>
                      <?php if(!empty($event->address_1) && !empty($event->city) && !empty($event->state) && !empty($event->zip)): ?>
                        <span itemprop="location" itemscope itemtype="http://schema.org/Place" hidden>
                          <?php
                            $address =  '<span itemprop="streetAddress">'.$event->address_1.'</span>, '.
                                        '<span itemprop="addressLocality">'.$event->city.'</span>, '.
                                        '<span itemprop="addressRegion">'.$event->state.'</span> '.
                                        '<span itemprop="postalCode">'.$event->zip.'</span>';
                          ?>
                          <meta itemprop="name" content="<?php echo (empty($event->building_name)) ? strip_tags($address) : $event->building_name; ?>" />
                          <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $address; ?></span>
                        </span>
                      <?php endif; ?>
                      <?php if(!empty($event->image_med)): ?><meta itemprop="image" content="<?php echo $event->image_med; ?>" /><?php endif; ?>
                      <?php if(!$instance['show_description']): ?><span itemprop="description" hidden><?php echo $event->description_html; ?></span><?php endif; ?>
                    </span>
                    <?php if($instance['show_description']): ?><p itemprop="description"><?php echo $event->description; ?></p><?php endif; ?>
                  </div>
                </div>
              </li>
            <?php endforeach;?>
          </ul>
          <p class="more-events-page-link-wrapper content" style="text-align:center;"><a class="more-events-page-link button" href="<?php echo esc_url( $planitpurple_url ); ?>" title="View More Events" target="_blank">More Events</a></p>
        </div>
      <?php else: ?>
        <p>No events scheduled, please check back later.</p>
      <?php endif; ?>

      <?php
      $output .= ob_get_contents();
      ob_end_clean();
    }

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
      $title = __( 'Upcoming Events', 'nu_gm' );
    }
    if ( isset( $instance[ 'pip_id' ] ) ) {
      $pip_id = $instance[ 'pip_id' ];
    }
    else {
      $pip_id = '';
    }
    if ( isset( $instance[ 'max_items' ] ) ) {
      $max_items = $instance[ 'max_items' ];
    }
    else {
      $max_items = 3;
    }
    if ( isset( $instance[ 'cache_lifetime' ] ) ) {
      $cache_lifetime = $instance[ 'cache_lifetime' ];
    }
    else {
      $cache_lifetime = 60;
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'nu_gm' )?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'pip_id' ); ?>"><?php _e( 'PlanItPurple Feed ID #:', 'nu_gm' )?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'pip_id' ); ?>" name="<?php echo $this->get_field_name( 'pip_id' ); ?>" type="number" value="<?php echo esc_attr( $pip_id ); ?>">
      <em><span class="required">*</span> This is different than the calendar ID, and you may need to create a feed if you have not already done so. For help with this, please see the <a href="http://www.northwestern.edu/univ-relations/webcomm/user-support-and-training/planit-purple-help/planit-purple-guides/planit_purple_feed.html" target="_blank" title="Plan It Purple Feed Documentation">PlanItPurple Feed Documentation</a></em>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'max_items' ); ?>"><?php _e( 'Maximum Number of Items to Show:', 'nu_gm' )?></label> 
      <input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="number" value="<?php echo esc_attr( $max_items ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'cache_lifetime' ); ?>"><?php _e( 'Minutes to Cache Feed Output:', 'nu_gm' )?></label> 
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
    $instance['pip_id'] = ( ! empty( $new_instance['pip_id'] ) ) ? strip_tags( $new_instance['pip_id'] ) : '';
    $instance['max_items'] = ( ! empty( $new_instance['max_items'] ) ) ? strip_tags( $new_instance['max_items'] ) : '';
    $instance['cache_lifetime'] = ( ! empty( $new_instance['cache_lifetime'] ) ) ? strip_tags( $new_instance['cache_lifetime'] ) : 60;
    
    // Flush widget cache
    $this->flush_widget_cache();
    
    return $instance;
  }
} // class PlanItPurple_Widget

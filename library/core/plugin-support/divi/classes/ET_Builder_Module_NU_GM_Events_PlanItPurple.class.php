<?php

class ET_Builder_Module_NU_GM_Events_PlanItPurple extends ET_Builder_Module {
  public static $module_slug = 'et_pb_nu_gm_events_planitpurple';

  function init() {
    $this->name = esc_html__( 'Events Feed (PlanItPurple)', 'nu_gm' );
    $this->slug = $this->get_module_slug();
    $this->fullwidth       = true;
    $this->custom_css_tab  = false;
    $this->whitelisted_fields = array(
      'title',
      'pip_id',
      'max_items',
      'cache_lifetime',
      'module_id',
    );
    $this->fields_defaults = array(
      'max_items'      => 4,
      'cache_lifetime' => 86400,
    );
    $this->advanced_options = array();
  }

  static function get_module_slug() {
    return self::$module_slug;
  }

  function get_fields() {
    $fields = array(
      'title' => array(
        'label'             => esc_html__( 'Section Title', 'nu_gm' ),
        'type'              => 'text',
        'option_category'   => 'basic_option',
        'description'       => esc_html__( 'This defines the section title text.', 'nu_gm' ),
      ),
      'module_id' => array(
        'label'           => esc_html__( 'Section ID', 'nu_gm' ),
        'type'            => 'text',
        'option_category' => 'configuration',
        'description'     => esc_html__( 'This is an administrative ID for this section. It should be unique, and should only contain lowercase letters, numbers, dashes and underscores.', 'nu_gm' ),
        'attributes'      => array( 'pattern' => '[a-z0-9\-_]*' ),
      ),
      'pip_id' => array(
        'label'             => esc_html__( 'PlanItPurple Feed ID', 'nu_gm' ),
        'type'              => 'text',
        'required'          => true,
        'digits'            => true,
        'attributes'        => array(
          'maxlength' => 8,
        ),
        'option_category'   => 'basic_option',
        'description'       => esc_html__( 'This must be a number. It is different than the calendar ID, and you may need to create a feed if you have not already done so. For help with this, please see the <a href="http://www.northwestern.edu/univ-relations/webcomm/user-support-and-training/planit-purple-help/planit-purple-guides/planit_purple_feed.html" target="_blank" title="Plan It Purple Feed Documentation">PlanItPurple Feed Documentation</a>.', 'nu_gm' ),
      ),
      'max_items' => array(
        'label'             => esc_html__( 'Number of Events to Display', 'nu_gm' ),
        'type'              => 'range',
        'required'          => true,
        'default'           => 4,
        'number_validation' => true,
        'range_settings'    => array(
          'min'  => 2,
          'max'  => 6,
          'step' => 2,
        ),
        'option_category'   => 'basic_option',
        'description'       => esc_html__( 'This defines the maximum number of events that will be displayed.', 'nu_gm' ),
      ),
      'cache_lifetime' => array(
        'label'             => esc_html__( 'Cache Lifetime', 'nu_gm' ),
        'type'              => 'select',
        'required'          => true,
        'default'           => 86400,
        'option_category'   => 'basic_option',
        'options'           => array(
          3600   => esc_html__( '1 Hour', 'nu_gm' ),
          10800  => esc_html__( '3 Hours', 'nu_gm' ),
          21600  => esc_html__( '6 Hours', 'nu_gm' ),
          43200  => esc_html__( '12 Hours', 'nu_gm' ),
          86400  => esc_html__( '1 Day (Recommended)', 'nu_gm' ),
          172800 => esc_html__( '2 Days', 'nu_gm' ),
          259200 => esc_html__( '3 Days', 'nu_gm' ),
          604800 => esc_html__( '1 Week', 'nu_gm' ),
        ),
        'description'       => esc_html__( 'This defines how frequently PlanItPurple should be checked for new events (set longer cache lifetime for better performance).', 'nu_gm' ),
      ),
    );

    return $fields;
  }

  function get_transient_id() {
    $pip_id            = trim($this->shortcode_atts['pip_id']);
    $transient_id      = 'nu_gm:pip:'.$pip_id;
    return $transient_id;
  }

  function get_pip_events() {
    $pip_events       = array();
    $pip_endpoint_url = 'http://planitpurple.northwestern.edu/feed/json/'.trim($this->shortcode_atts['pip_id']);
    $transient_id     = $this->get_transient_id();

    // Attempt to retrieve events data from cache before requesting updated feed from pip
    $cache = get_transient( $transient_id );
    if ( !empty( $cache ) ) {
      return $cache;
    }
    
    // Fetch fresh data from pip
    $pip_response = wp_remote_get($pip_endpoint_url, array('timeout' => 3));
    if( !empty( $pip_response ) && !empty( $pip_response['body'] ) ) {
      $pip_events_full = json_decode( $pip_response['body'] );
      foreach ( $pip_events_full as $index => $pip_event ) {
        $pip_events[] = $this->parse_pip_event( $pip_event );
      }
    }

    // Cache parsed events array
    if( !empty( $pip_events ) ) {
      set_transient( $transient_id, $pip_events, $this->shortcode_atts[ 'cache_lifetime' ] );
    }

    return $pip_events;
  }

  function parse_pip_event( $event_full ) {
    // Calculate ISO 8601 Dates for microdata
    $start_date          = date_create($event_full->eventdate_ical_format.' CST');
    $start_date_iso_8601 = $start_date->format('c');
    $end_date            = (empty($event_full->eventend_ical_format)) ? $start_date : date_create($event_full->eventend_ical_format.' CST');
    $end_date_iso_8601   = $end_date->format('c');
    $duration            = $end_date->diff($start_date);
    $duration_iso_8601   = $start_date->format('Y-m-d').'/'.$duration->format('P%dD%HH%II');
    $event_date_parts    = explode('-', $event_full->eventdate);

    // Build Event
    $event = array(
      'display' => array(
        'url'        => $event_full->url,
        'title'      => $event_full->title,
        'year'       => $event_date_parts[0],
        'month'      => $event_date_parts[1],
        'month_text' => date('M', strtotime($event_full->eventdate)),
        'day'        => $event_date_parts[2],
        'time'       => $event_full->start_time_display_format,
      ),
      'meta'    => (object) array(
        '@context'  => 'http://schema.org',
        '@type'     => 'Event',
        'name'      => $event_full->title,
        'url'       => $event_full->url,
        'startDate' => $start_date_iso_8601,
        'endDate'   => $end_date_iso_8601,
        'duration'  => $duration_iso_8601,
      ),
    );
    if( !empty( $event_full->building_name ) ) {
      $event['display']['location_name'] = $event_full->building_name;
    }
    if( !empty( $event_full->building_name ) || !empty( $event_full->address_1 ) || !empty( $event_full->city ) || !empty( $event_full->state ) || !empty( $event_full->zip ) ) {
      $event['meta']->location = (object) array(
        '@type'   => 'Place',
        'address' => (object) array(
          '@type'           => 'PostalAddress',
        ),
      );
      if( !empty( $event_full->address_1 ) ) {
        $event['meta']->location->address->streetAddress = $event_full->address_1;
      }
      if( !empty( $event_full->city ) ) {
        $event['meta']->location->address->addressLocality = $event_full->city;
      }
      if( !empty( $event_full->state ) ) {
        $event['meta']->location->address->addressRegion = $event_full->state;
      }
      if( !empty( $event_full->zip ) ) {
        $event['meta']->location->address->postalCode = $event_full->zip;
      }
      if( !empty( $event_full->building_name ) ) {
        $event['meta']->location->name = $event_full->building_name;
      }
    }
    if( !empty( $event_full->image_med ) ) {
      $event['meta']->image = $event_full->image_med;
    }
    if( !empty( $event_full->description ) ) {
      $event['meta']->description = $event_full->description;
    }

    return $event;
  }

  function shortcode_callback( $atts, $content = null, $function_name ) {
    $title      = $this->shortcode_atts['title'] ?: false;
    $max_items  = $this->shortcode_atts['max_items'] ?: 4;
    $module_id  = $this->shortcode_atts['module_id'] ? ' id="'.$this->shortcode_atts['module_id'].'"' : '';
    $pip_events = $this->get_pip_events();
    $output     = '';
    $metadata   = array();
    $aria_label = empty($title) ?  ' aria-label="'.$this->name.'"' : ' aria-label="'.$title.'"';

    if( !empty( $pip_events ) ) {
      $output .= '<section'.$module_id.$aria_label.' class="clearfix contain-1120 '.preg_replace('|_|', '-', $this->slug).'">';
        if($title) $output  .= '<div class="nu-gm-section-title content"><h3>'.$title.'</h3></div>';
        $output .= '<div class="event-list-wrapper standard-page">'; // div.event-list-wrapper start
          $output .= '<div class="event-list">'; // ul.event-list start
            foreach ( $pip_events as $index => $event ) {
              // If we've already reached the maximum, stop parsing
              if( $index >= $max_items )
                break;

              // Add JSON-LD metadata to $metadata array
              $metadata[] = $event['meta'];

              // Generate individual event output
              $output .= '<div class="event">'; // div.event-list-item start
                $output .= '<div class="event-date">'; // div.event-date start
                  $output .= '<div class="month">'.$event['display']['month_text'].'</div>';
                  $output .= '<div class="day">'.$event['display']['day'].'</div>';
                  $output .= '<div class="year">'.$event['display']['year'].'</div>';
                $output .= '</div>'; // div.event-date end
                $output .= '<div class="event-description">'; // div.event-description start
                  $output .= '<h4><a href="'.$event['display']['url'].'" target="_blank" title="View '.$event['display']['title'].' on PlanItPurple">'.$event['display']['title'].'</a></h4>';
                  $output .= '<p class="event-time-location">';
                    $output .= $event['display']['time'];
                    if(!empty($event['display']['location_name'])) $output .= '<br>'.$event['display']['location_name'];
                  $output .= '</p>';
                $output .= '</div>'; // div.event-description end
              $output .= '</div>'; // div.event-list-item end
            }
          $output .= '</div>'; // ul.event-list end
          $output .= '<script type="application/ld+json">'; // JSON-LD metadata start
            $output .= json_encode($metadata, JSON_UNESCAPED_SLASHES);
          $output .= '</script>'; // JSON-LD metadata end
        $output .= '</div>'; // div.event-list-wrapper end
      $output .= '</section>';
    }

    return $output;
  }
}
new ET_Builder_Module_NU_GM_Events_PlanItPurple;

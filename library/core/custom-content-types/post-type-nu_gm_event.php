<?php
/*
 *	Custom Content Type Definition for: nu_gm_event
 *
 *	- Dependant on Advanced Custom Fields plugin
**/

add_action( 'init', 'register_custom_type_nu_gm_event');
function register_custom_type_nu_gm_event() {
	register_post_type( 'nu_gm_event',
		array( 'labels' => array(
				'name' 								=> __( 'Events', 'nu_gm' ), /* This is the Title of the Group */
				'singular_name' 			=> __( 'Event', 'nu_gm' ), /* This is the individual type */
				'all_items' 					=> __( 'All Events', 'nu_gm' ), /* the all items menu item */
				'add_new' 						=> __( 'Add New', 'nu_gm' ), /* The add new menu item */
				'add_new_item' 				=> __( 'Add New Event', 'nu_gm' ), /* Add New Display Title */
				'edit' 								=> __( 'Edit', 'nu_gm' ), /* Edit Dialog */
				'edit_item' 					=> __( 'Edit Event', 'nu_gm' ), /* Edit Display Title */
				'new_item' 						=> __( 'New Event', 'nu_gm' ), /* New Display Title */
				'view_item' 					=> __( 'View Event', 'nu_gm' ), /* View Display Title */
				'search_items' 				=> __( 'Search Events', 'nu_gm' ), /* Search Custom Type Title */
				'not_found' 					=>  __( 'Nothing found in the Database.', 'nu_gm' ), /* This displays if there are no entries yet */
				'not_found_in_trash' 	=> __( 'Nothing found in Trash', 'nu_gm' ), /* This displays if there is nothing in the trash */
				'parent_item_colon'  	=> ''
			),
			'description' => '',
			'public' 					=> true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' 				=> true,
			'query_var' 			=> true,
			'menu_position' 	=> 8, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon' 			=> 'dashicons-format-aside', /* the icon for the custom type menu */
			'menu_icon' 			=> 'dashicons-calendar-alt', /* the icon for the project type menu */
			'rewrite'					=> array( 'slug' => 'events', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' 		=> 'events', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' 		=> false,
			'supports' 				=> array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes')
		) /* end of options */
	); /* end of register post type */

	/* this adds your post categories to your custom type */
	register_taxonomy_for_object_type( 'category', 'nu_gm_event' );

	/* this adds your post tags to your custom type */
	register_taxonomy_for_object_type( 'post_tag', 'nu_gm_event' );

	// Add filter to clean up Archive title for cutom content type
	add_filter( 'get_the_archive_title', 'nu_gm_event_update_archive_title');

	// Update archive listings with date-based sorting
	add_action( 'pre_get_posts', 'nu_gm_event_update_query', 10, 1 );
}

// Set events to sort by start date, then start time
function nu_gm_event_update_query( $query, $return = false ) {
  if( ( is_post_type_archive( 'nu_gm_event' ) && $query->is_main_query() ) ||
      ( $query->get('post_type') == 'nu_gm_event' && !empty($query->get('nu_gm_event_enable_upcoming_filters')) )
    ) {
    // Date range condition
    $query->set('meta_query', array(
      'relation' => 'AND',
      'dates' => array(
      	'relation' => 'AND',
	      array(
	        'key'     => 'nu_gm_event_start_date',
	        'compare' => 'EXISTS',
	      ),
	      array(
	      	'relation' => 'OR',
		      'start_date_value' => array(
		        'key'     	=> 'nu_gm_event_start_date',
		        'compare' 	=> '>=',
		        'value'			=> strtotime('today'),
		        'type'			=> 'UNSIGNED'
		      ),
		      'end_date_value' => array(
		        'key'     	=> 'nu_gm_event_end_date',
		        'compare' 	=> '>=',
		        'value'			=> strtotime('today'),
		        'type'			=> 'UNSIGNED'
		      )
		    )
	    ),
	    'times' => array(
	    	'relation' => 'AND',
	    	'start_time' => array(
	    		'relation' => 'OR',
	      	'start_time_value' => array(
		        'key'     	=> 'nu_gm_event_start_time',
		        'compare' 	=> 'EXISTS',
		        'type' 			=> 'TIME'
		      ),
	      	array(
		        'key'     	=> 'nu_gm_event_start_time',
		        'compare' 	=> 'NOT EXISTS',
		      ),
		    ),
	    	'end_time' => array(
	    		'relation' => 'OR',
	      	'end_time_value' => array(
		        'key'     	=> 'nu_gm_event_end_time',
		        'compare' 	=> 'EXISTS',
		        'type' 			=> 'TIME'
		      ),
	      	array(
		        'key'     	=> 'nu_gm_event_end_time',
		        'compare' 	=> 'NOT EXISTS',
		      ),
		    ),
	    ),
    ));

    // Set ordering conditions
    $query->set('orderby', array(
    	'start_date_value' 	=> 'ASC',
    	'start_time_value' 	=> 'ASC',
    	'end_date_value' 		=> 'ASC',
    	'end_time_value' 		=> 'ASC',
    	'date' 							=> 'ASC',
    ));

  }

  if( $return ) {
    return $query;
  }
}

// Clean up Archive title for cutom content type
function nu_gm_event_update_archive_title ($title) {
	if ( is_post_type_archive( 'nu_gm_event' ) ) {
		$title = str_replace('Archives: ', '', $title);
	}

	return $title;
}

// Attach contact info fields
// Hook to 'admin_init' to make sure the meta box class is loaded before
add_action( 'rwmb_meta_boxes', 'nu_gm_event_metadata_register_meta_boxes' );
function nu_gm_event_metadata_register_meta_boxes() {
	global $meta_boxes;
	$post_types = array( 'nu_gm_event' );
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) ) {
		new RW_Meta_Box(
			array(
				'id'       => 'nu_gm_event_date_time',
				'title'    => __( 'Date & Time', 'nu_gm'),
				'pages'    => $post_types,
				'context'  => 'normal',
				'priority' => 'low',
				'fields'   => array(
					array(
						'before'				=> '<div class="nu-gm-event-datetime nu-gm-event-datetime-start">',
						'name' 	      	=> __( 'Start Date', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_start_date',
						'type'        	=> 'date',
						'placeholder' 	=> 'Start Date',
						'timestamp'			=> true,
						'class'					=> 'nu-gm-event-datetime-field nu-gm-event-start nu-gm-event-date',
					),
					array(
						'name' 	      	=> __( 'Start Time', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_start_time',
						'type'        	=> 'time',
						'placeholder' 	=> 'Start Time',
						'class'					=> 'nu-gm-event-datetime-field nu-gm-event-start nu-gm-event-time',
						'after'					=> '</div>',
					),
					array(
						'id'          	=> 'nu_gm_event_start_end_divider',
						'type'        	=> 'divider',
					),
					array(
						'before'				=> '<div class="nu-gm-event-datetime nu-gm-event-datetime-start">',
						'name' 	      	=> __( 'End Date', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_end_date',
						'type'        	=> 'date',
						'placeholder' 	=> 'End Date',
						'timestamp'			=> true,
						'class'					=> 'nu-gm-event-datetime-field nu-gm-event-end nu-gm-event-date',
					),
					array(
						'name' 	      	=> __( 'End Time', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_end_time',
						'type'        	=> 'time',
						'placeholder' 	=> 'End Time',
						'class'					=> 'nu-gm-event-datetime-field nu-gm-event-end nu-gm-event-time',
						'after'					=> '</div>',
					),
				),
			)
		);
		new RW_Meta_Box(
			array(
				'id'       => 'nu_gm_event_location',
				'title'    => __( 'Location', 'nu_gm'),
				'pages'    => $post_types,
				'context'  => 'normal',
				'priority' => 'low',
				'fields'   => array(
					array(
						'name' 	      	=> __( 'Title', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_location_title',
						'type'        	=> 'text',
						'placeholder' 	=> 'Location Title',
						'class'					=> 'nu-gm-event-location-field',
					),
					array(
						'name' 	      	=> __( 'URL', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_location_url',
						'type'        	=> 'url',
						'placeholder' 	=> 'Web Address',
						'class'					=> 'nu-gm-event-location-field',
					),
					array(
						'name' 	      	=> __( 'Address', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_location_address',
						'type'        	=> 'text',
						'placeholder' 	=> 'Street Address',
						'class'					=> 'nu-gm-event-location-field',
					),
					array(
						'name' 	      	=> __( 'Address (additional)', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_location_address_additional',
						'type'        	=> 'text',
						'placeholder' 	=> 'Room or Office #',
						'class'					=> 'nu-gm-event-location-field',
					),
					array(
						'name' 	      	=> __( 'City', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_location_city',
						'type'        	=> 'text',
						'placeholder' 	=> 'City',
						'class'					=> 'nu-gm-event-location-field',
					),
					array(
						'name' 	      	=> __( 'State', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_location_state',
						'type'        	=> 'text',
						'placeholder' 	=> 'State',
						'class'					=> 'nu-gm-event-location-field',
						'attributes'		=> array(
							'length'				=> 5,
						),
					),
					array(
						'name' 	      	=> __( 'Zip', 'nu_gm' ),
						'id'          	=> 'nu_gm_event_location_zip',
						'type'        	=> 'text',
						'placeholder' 	=> 'Zip',
						'class'					=> 'nu-gm-event-location-field',
						'attributes'		=> array(
							'length'				=> 5,
						),
					),
				),
			)
		);
  }
}

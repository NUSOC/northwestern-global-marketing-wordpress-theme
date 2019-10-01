<?php

/*
 *	Custom Content Type Definition for: nu_gm_directory_item
**/

add_action( 'init', 'register_custom_type_nu_gm_directory_item');
function register_custom_type_nu_gm_directory_item() {
	$post_type = 'nu_gm_directory_item';
	register_post_type( $post_type,
		array(
			'labels'              => array(
				'name'                => __( 'Directory', 'nu_gm' ), /* This is the Title of the Group */
				'singular_name'       => __( 'Directory Entry', 'nu_gm' ), /* This is the individual type */
				'all_items'           => __( 'All Directory Entries', 'nu_gm' ), /* the all items menu item */
				'add_new'             => __( 'Add New', 'nu_gm' ), /* The add new menu item */
				'add_new_item'        => __( 'Add New Directory Entry', 'nu_gm' ), /* Add New Display Title */
				'edit'                => __( 'Edit', 'nu_gm' ), /* Edit Dialog */
				'edit_item'           => __( 'Edit Directory Entry', 'nu_gm' ), /* Edit Display Title */
				'new_item'            => __( 'New Directory Entry', 'nu_gm' ), /* New Display Title */
				'view_item'           => __( 'View Directory Entry', 'nu_gm' ), /* View Display Title */
				'search_items'        => __( 'Search Directory Entries', 'nu_gm' ), /* Search Custom Type Title */
				'not_found'           =>  __( 'Nothing found in the Database.', 'nu_gm' ), /* This displays if there are no entries yet */
				'not_found_in_trash'  => __( 'Nothing found in Trash', 'nu_gm' ), /* This displays if there is nothing in the trash */
				'parent_item_colon'   => ''
			),
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'query_var'           => true,
			'menu_position'       => 9, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon'           => 'dashicons-id-alt', /* the icon for the directory entry type menu */
			'rewrite'	            => array( 'slug' => 'directory', 'with_front' => false ), /* you can specify its url slug */
			'has_archive'         => 'directory', /* you can rename the slug here */
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions', 'author')
		)
	);

	// Set default format for list appearance
	if(!get_theme_mod('nu_gm_directory_item_list_format_setting', false))
		set_theme_mod('nu_gm_directory_item_list_format_setting', 'people-small');

	// Add filter to clean up Archive title for custom content type
	add_filter( 'get_the_archive_title', 'nu_gm_directory_item_update_archive_title');

	// Update archive listings with name sorting, and a-z pagination
	add_action( 'pre_get_posts', 'nu_gm_directory_item_update_query' );
}

// Attach contact info fields
// Hook to 'admin_init' to make sure the meta box class is loaded before
add_action( 'rwmb_meta_boxes', 'nu_gm_directory_item_contact_info_register_meta_boxes' );
function nu_gm_directory_item_contact_info_register_meta_boxes() {
	global $meta_boxes;
	$post_types = array( 'nu_gm_directory_item' );
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) ) {
		new RW_Meta_Box(
			array(
				'id'       => 'nu_gm_directory_item_contact_info',
				'title'    => __( 'Contact Information', 'nu_gm' ),
				'pages'    => $post_types,
				'context'  => 'normal',
				'priority' => 'default',
				'fields'   => array(
					array(
						'name'       => __( 'WP User', 'nu_gm' ),
						'id' 		     => 'nu_gm_wp_user',
						'type' 	     => 'user',
						'query_args' => array('role__in' => array('administrator', 'editor', 'author', 'contributor')),
						'placeholder'=> 'Select Person...',
						'desc'	     => 'Please select the WP user this directory entry is for. Users with the subscriber role are inelligable for this field.',
					),
					array(
						'name'       => __( 'First Name', 'nu_gm' ),
						'id' 		     => 'nu_gm_first_name',
						'placeholder'=> 'First Name...',
						'type' 	     => 'text',
					),
					array(
						'name'       => __( 'Last Name', 'nu_gm' ),
						'id' 		     => 'nu_gm_last_name',
						'placeholder'=> 'Last Name...',
						'type' 	     => 'text',
					),
					array(
						'name'       => __( 'Professional Title', 'nu_gm' ),
						'id' 		     => 'nu_gm_professional_title',
						'placeholder'=> 'Professional Title...',
						'type' 	     => 'text',
					),
					array(
						'name'       => __( 'Phone Number', 'nu_gm' ),
						'id' 		     => 'nu_gm_phone_number',
						'placeholder'=> 'Phone Number...',
						'type' 	     => 'text',
					),
					array(
						'name'       => __( 'Email', 'nu_gm' ),
						'id' 		     => 'nu_gm_email',
						'placeholder'=> 'Email...',
						'type' 	     => 'email',
					),
					array(
						'name'       => __( 'NetID', 'nu_gm' ),
						'id' 		     => 'nu_gm_netid',
						'placeholder'=> 'NetID...',
						'type' 	     => 'text',
					),
					array(
						'name'       => __( 'Profile Image', 'nu_gm' ),
						'id'         => 'nu_gm_profile_image',
						'type'       => 'custom_html',
						'std'        => '<button type="button" id="nu_gm_hero_banner_featured_image_setter-insert-media-button" class="button custom_upload_image_button add_media" onclick="jQuery(\'#set-post-thumbnail\').trigger(\'click\')">Set Featured & Profile Image</button>',
					),
					array(
						'name'       => __( 'Hide Hero Image', 'nu_gm' ),
						'id'         => 'nu_gm_hide_hero_banner_image',
						'type'       => 'hidden',
						'std' 	     => 1,
					),
				),
			)
		);
	}
}

// Update archive listings with name sorting, and a-z pagination
function nu_gm_directory_item_update_query( $query ) {
	if( is_post_type_archive( 'nu_gm_directory_item' ) && $query->is_main_query() ) { // Run only on the homepage
		$query->set('meta_key', 'nu_gm_last_name');
		$query->set('orderby', 'nu_gm_last_name');
		$query->set('order', 'ASC');
		$query->set('posts_per_page', -1);
	}
}

// Function for generating letter-based pager
function nu_gm_directory_page_navi() {
	global $letters_with_entries;
	if(count($letters_with_entries) > 1) {
		$output = '<div id="letter-pager-group-bottom" class="standard-page landing-page"><nav class="center-list pagination letter-pager"><ul class="page-numbers page-letters">';
		foreach ($letters_with_entries as $letter) {
			$output .= '<li><a class="page-numbers" href="#starts-with-'.$letter.'">'.$letter.'</a></li>';
		}
		$output .= '</ul></div>';
		echo $output;
	}
}

// Clean up Archive title for cutom content type
function nu_gm_directory_item_update_archive_title ($title) {
	if ( is_post_type_archive( 'nu_gm_directory_item' ) ) {
		$title = str_replace('Archives: ', '', $title);
	}

	return $title;
}

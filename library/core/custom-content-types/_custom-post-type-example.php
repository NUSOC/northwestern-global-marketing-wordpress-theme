<?php
/*
 *	Custom Content Type Definition for: nu_gm_custom_type
 *
 *	- Dependant on Advanced Custom Fields plugin
**/

function register_custom_type_nu_gm_custom_type() { 
	register_post_type( 'nu_gm_custom_type',
		array( 'labels' => array(
				'name' => __( 'Custom Types', 'nu_gm' ), /* This is the Title of the Group */
				'singular_name' => __( 'Custom Type', 'nu_gm' ), /* This is the individual type */
				'all_items' => __( 'All Custom Types', 'nu_gm' ), /* the all items menu item */
				'add_new' => __( 'Add New', 'nu_gm' ), /* The add new menu item */
				'add_new_item' => __( 'Add New Custom Type', 'nu_gm' ), /* Add New Display Title */
				'edit' => __( 'Edit', 'nu_gm' ), /* Edit Dialog */
				'edit_item' => __( 'Edit Custom Types', 'nu_gm' ), /* Edit Display Title */
				'new_item' => __( 'New Custom Type', 'nu_gm' ), /* New Display Title */
				'view_item' => __( 'View Custom Type', 'nu_gm' ), /* View Display Title */
				'search_items' => __( 'Search Custom Types', 'nu_gm' ), /* Search Custom Type Title */ 
				'not_found' =>  __( 'Nothing found in the Database.', 'nu_gm' ), /* This displays if there are no entries yet */ 
				'not_found_in_trash' => __( 'Nothing found in Trash', 'nu_gm' ), /* This displays if there is nothing in the trash */
				'parent_item_colon' => ''
			),
			'description' => '',
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'query_var' => true,
			'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon' => 'dashicons-format-aside', /* the icon for the custom type menu */
			'rewrite'	=> array( 'slug' => 'nu-gm-custom-type', 'with_front' => false ), /* you can specify its url slug */
			'has_archive' => 'nu-gm-custom-type', /* you can rename the slug here */
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'page-attributes')
		) /* end of options */
	); /* end of register post type */
	
	/* this adds your post categories to your custom type */
	register_taxonomy_for_object_type( 'category', 'nu_gm_custom_type' );

	/* this adds your post tags to your custom type */
	register_taxonomy_for_object_type( 'post_tag', 'nu_gm_custom_type' );
	
}

// Make sure we have access to the is_plugin_active function
if ( !function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

// If ACF is active, register this custom post type, otherwise display a message requestiong the activation of the ACF plugin
if(is_plugin_active('advanced-custom-fields/acf.php')) {
	add_action( 'init', 'register_custom_type_nu_gm_custom_type');
} else {
	// display message in admin area requesting ACF be enabled
}

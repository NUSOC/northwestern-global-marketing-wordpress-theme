<?php

/*
 *	Custom Content Type Definition for: nu_gm_project
**/

add_action( 'init', 'register_custom_type_nu_gm_project');
function register_custom_type_nu_gm_project() {
	$post_type = 'nu_gm_project';
	register_post_type( $post_type,
		array(
			'labels'              => array(
				'name'                       => __( 'Projects', 'nu_gm' ), /* This is the Title of the Group */
				'singular_name'              => __( 'Project', 'nu_gm' ), /* This is the individual type */
				'all_items'                  => __( 'All Projects', 'nu_gm' ), /* the all items menu item */
				'add_new'                    => __( 'Add New', 'nu_gm' ), /* The add new menu item */
				'add_new_item'               => __( 'Add New Project', 'nu_gm' ), /* Add New Display Title */
				'edit'                       => __( 'Edit', 'nu_gm' ), /* Edit Dialog */
				'edit_item'                  => __( 'Edit Project', 'nu_gm' ), /* Edit Display Title */
				'new_item'                   => __( 'New Project', 'nu_gm' ), /* New Display Title */
				'view_item'                  => __( 'View Project', 'nu_gm' ), /* View Display Title */
				'search_items'               => __( 'Search Projects', 'nu_gm' ), /* Search Custom Type Title */
				'not_found'                  => __( 'Nothing found in the Database.', 'nu_gm' ), /* This displays if there are no entries yet */
				'not_found_in_trash'         => __( 'Nothing found in Trash', 'nu_gm' ), /* This displays if there is nothing in the trash */
				'parent_item_colon'          => ''
			),
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'query_var'           => true,
			'menu_position'       => 9, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon'           => 'dashicons-analytics', /* the icon for the project type menu */
			'rewrite'	            => array( 'slug' => 'projects', 'with_front' => false ), /* you can specify its url slug */
			'has_archive'         => 'projects', /* you can rename the slug here */
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' )
		)
	);

	// Register Custom Project Category Taxonomy
	register_taxonomy( 'nu_gm_project_category', array( 'nu_gm_project' ),
		array(
			'labels'              => array(
				'name'                       => _x( 'Project Categories', 'Taxonomy General Name', 'nu_gm' ),
				'singular_name'              => _x( 'Project Category', 'Taxonomy Singular Name', 'nu_gm' ),
				'menu_name'                  => __( 'Project Categories', 'nu_gm' ),
				'all_items'                  => __( 'Project Categories', 'nu_gm' ),
				'parent_item'                => __( 'Parent Project Categories', 'nu_gm' ),
				'parent_item_colon'          => __( 'Parent Project Categories:', 'nu_gm' ),
				'new_item_name'              => __( 'New Project Categories Name', 'nu_gm' ),
				'add_new_item'               => __( 'Add New Project Categories', 'nu_gm' ),
				'edit_item'                  => __( 'Edit Project Category', 'nu_gm' ),
				'update_item'                => __( 'Update Project Categories', 'nu_gm' ),
				'view_item'                  => __( 'View Project Categories', 'nu_gm' ),
				'separate_items_with_commas' => __( 'Separate project categories with commas', 'nu_gm' ),
				'add_or_remove_items'        => __( 'Add or remove project categories', 'nu_gm' ),
				'popular_items'              => __( 'Popular Project Categories', 'nu_gm' ),
				'search_items'               => __( 'Search Project Categories', 'nu_gm' ),
				'not_found'                  => __( 'Not Found', 'nu_gm' ),
				'no_terms'                   => __( 'No project categories', 'nu_gm' ),
				'items_list'                 => __( 'Projects list', 'nu_gm' ),
				'items_list_navigation'      => __( 'Projects list navigation', 'nu_gm' ),
			),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_admin_column'   => true,
			'show_in_nav_menus'   => true,
			'show_tagcloud'       => false,
			'show_in_quick_edit'  => true,
			'rewrite'             => array( 'slug' => 'project-categories' ),
		)
	);

	// Register Custom Project Service Taxonomy
	register_taxonomy( 'nu_gm_project_service', array( 'nu_gm_project' ),
		array(
			'labels' => array(
				'name' =>                       _x( 'Project Services', 'Taxonomy General Name', 'nu_gm' ),
				'singular_name'              => _x( 'Project Service', 'Taxonomy Singular Name', 'nu_gm' ),
				'menu_name'                  => __( 'Project Services', 'nu_gm' ),
				'all_items'                  => __( 'Project Services', 'nu_gm' ),
				'parent_item'                => __( 'Parent Project Service', 'nu_gm' ),
				'parent_item_colon'          => __( 'Parent Project Service:', 'nu_gm' ),
				'new_item_name'              => __( 'New Project Service Name', 'nu_gm' ),
				'add_new_item'               => __( 'Add New Project Service', 'nu_gm' ),
				'edit_item'                  => __( 'Edit Project Service', 'nu_gm' ),
				'update_item'                => __( 'Update Project Service', 'nu_gm' ),
				'view_item'                  => __( 'View Project Service', 'nu_gm' ),
				'separate_items_with_commas' => __( 'Separate project services with commas', 'nu_gm' ),
				'add_or_remove_items'        => __( 'Add or remove project services', 'nu_gm' ),
				'popular_items'              => __( 'Popular Project Services', 'nu_gm' ),
				'search_items'               => __( 'Search Project Services', 'nu_gm' ),
				'not_found'                  => __( 'Not Found', 'nu_gm' ),
				'no_terms'                   => __( 'No project services', 'nu_gm' ),
				'items_list'                 => __( 'Projects list', 'nu_gm' ),
				'items_list_navigation'      => __( 'Projects list navigation', 'nu_gm' ),
			),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_admin_column'   => true,
			'show_in_nav_menus'   => true,
			'show_tagcloud'       => false,
			'show_in_quick_edit'  => true,
			'rewrite'             => array( 'slug' => 'project-services' ),
		)
	);

	// Set default format for list appearance
	if(!get_theme_mod('nu_gm_project_list_format_setting', false))
		set_theme_mod('nu_gm_project_list_format_setting', 'feature-box');

	// Add filter to clean up Archive title for cutom content type
	add_filter( 'get_the_archive_title', 'nu_gm_project_update_archive_title');
}

// Clean up Archive title for cutom content type
function nu_gm_project_update_archive_title ($title) {
	if ( is_post_type_archive( 'nu_gm_project' ) ) {
		$title = str_replace('Archives: ', '', $title);
	}

	return $title;
}

// Attach contact info fields
// Hook to 'admin_init' to make sure the meta box class is loaded before
add_action( 'rwmb_meta_boxes', 'nu_gm_project_metadata_register_meta_boxes' );
function nu_gm_project_metadata_register_meta_boxes() {
	global $meta_boxes;
	$post_types = array( 'nu_gm_project' );
	// Make sure there's no errors when the plugin is deactivated or during upgrade
	if ( class_exists( 'RW_Meta_Box' ) ) {
		new RW_Meta_Box(
			array(
				'id'       => 'nu_gm_project_team',
				'title'    => __( 'Project Team', 'nu_gm'),
				'pages'    => $post_types,
				'context'  => 'normal',
				'priority' => 'low',
				'fields'   => array(
					array(
						'id'          => 'nu_gm_project_team',
						'type'        => 'group',
						'clone'       => true,
						'sort_clone'  => true,
						'fields'      => array(
							array(
								'name' 	      => __( 'Contributor', 'nu_gm' ),
								'id'          => 'nu_gm_project_team_contributor',
								'type'        => 'post',
								'post_type'   => 'nu_gm_directory_item',
								'placeholder' => 'Select Person...',
							),
							array(
								'name'         => __( 'Role', 'nu_gm' ),
								'id'           => 'nu_gm_project_team_role',
								'type'         => 'text',
								'desc'         => 'The this person played in the project.',
								'placeholder'  => 'Role...',
							),
						),
					),
				),
			)
		);
		new RW_Meta_Box(
			array(
				'id'       => 'nu_gm_project_showcase',
				'title'    => __( 'Project Showcase', 'nu_gm'),
				'pages'    => $post_types,
				'context'  => 'normal',
				'priority' => 'low',
				'fields'   => array(
					array(
						'name'         => __( 'Display Showcase in Tab View', 'nu_gm' ),
						'id'           => 'nu_gm_project_tabbed_display',
						'desc'         => 'If checked, each component of the project will be displayed in a seperate horizontal tab.',
						'type'         => 'checkbox',
						'std'          => false,
					),
					array(
						'type'         => 'divider',
					),
					array(
						'name'         => __( 'URL', 'nu_gm' ),
						'id'           => 'nu_gm_project_url',
						'type'         => 'url',
						'desc'         => 'The URL that this project can be viewed at.',
						'placeholder'  => 'Project URL...',
					),
					array(
						'type'         => 'divider',
					),
					array(
						'id'           => 'nu_gm_project_images',
						'name'         => __( 'Images', 'nu_gm' ),
						'type'         => 'image_advanced',
						'force_delete' => false,
					),
					array(
						'type'         => 'divider',
					),
					array(
						'id'           => 'nu_gm_project_video',
						'name'         => __( 'Videos', 'nu_gm' ),
						'type'         => 'oembed',
						'clone'        => true,
						'sort_clone'   => true,
						'placeholder'  => 'YouTube / Vimeo URL...',
					),
			  ),
			)
		);
  }
}

// Adds microdata to project type listing - itemprop="genre"
add_filter('term_links-nu_gm_project_category', 'nu_gm_project_category_microdata', 10, 1);
function nu_gm_project_category_microdata($links) {
  if(is_admin()) return $links;

  $new_listitems = array();
  foreach($links as $item) {
    if ($new_item = preg_replace_callback('!(<\s*a\s*)([^>]*)(>)(.*?)(<\s*/a[^>]*>)!im', function($regex_parts){
      if( !$regex_parts || count((array)$regex_parts) < 4)
        return $regex_parts;

      // Add microdata: itemprop="genre"
      if ( strpos($regex_parts[4], 'itemprop') == false ) {
      	// return '<span itemprop="genre">'.$regex_parts[4].'</span>';
        $regex_parts[4] = '<span itemprop="genre">'.$regex_parts[4].'</span>';
        array_shift($regex_parts);
        $updated_cat = implode('', $regex_parts);
        array_unshift($regex_parts, $updated_cat);
      }

      return $regex_parts[0];
    }, $item)) {
      $new_listitems[] = $new_item;
    }
  }

  return $new_listitems;
}

// Adds microdata to project service listing - itemprop="keywords"
add_filter('term_links-nu_gm_project_service', 'nu_gm_project_service_microdata', 10, 1);
function nu_gm_project_service_microdata($links) {
  if(is_admin()) return $links;

  $new_listitems = array();
  foreach($links as $item) {
    if ($new_item = preg_replace_callback('!(<\s*a\s*)([^>]*)(>)(.*?)(<\s*/a[^>]*>)!im', function($regex_parts){
      if( !$regex_parts || count((array)$regex_parts) < 4)
        return $regex_parts;

      // Add microdata: itemprop="keywords"
      if ( strpos($regex_parts[4], 'itemprop') == false ) {
      	// return '<span itemprop="keywords">'.$regex_parts[4].'</span>';
        $regex_parts[4] = '<span itemprop="keywords">'.$regex_parts[4].'</span>';
        array_shift($regex_parts);
        $updated_cat = implode('', $regex_parts);
        array_unshift($regex_parts, $updated_cat);
      }

      return $regex_parts[0];
    }, $item)) {
      $new_listitems[] = $new_item;
    }
  }

  return $new_listitems;
}


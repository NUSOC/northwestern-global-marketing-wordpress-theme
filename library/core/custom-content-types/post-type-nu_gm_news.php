<?php

/*
 *	Custom Content Type Definition for: nu_gm_news
**/

add_action( 'init', 'register_custom_type_nu_gm_news');
function register_custom_type_nu_gm_news() {
	$post_type = 'nu_gm_news';
	register_post_type( $post_type,
		array(
			'labels' => array(
				'name'                => __( 'News', 'nu_gm' ), /* This is the Title of the Group */
				'singular_name'       => __( 'News Article', 'nu_gm' ), /* This is the individual type */
				'all_items'           => __( 'All News Articles', 'nu_gm' ), /* the all items menu item */
				'add_new'             => __( 'Add New', 'nu_gm' ), /* The add new menu item */
				'add_new_item'        => __( 'Add New News Article', 'nu_gm' ), /* Add New Display Title */
				'edit'                => __( 'Edit', 'nu_gm' ), /* Edit Dialog */
				'edit_item'           => __( 'Edit News Article', 'nu_gm' ), /* Edit Display Title */
				'new_item'            => __( 'New News Article', 'nu_gm' ), /* New Display Title */
				'view_item'           => __( 'View News Article', 'nu_gm' ), /* View Display Title */
				'search_items'        => __( 'Search News Articles', 'nu_gm' ), /* Search Custom Type Title */
				'not_found'           => __( 'Nothing found in the Database.', 'nu_gm' ), /* This displays if there are no entries yet */
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
			'menu_position'       => 8, /* this is what order you want it to appear in on the left hand side menu */
			'menu_icon'           => 'dashicons-format-aside', /* the icon for the news type menu */
			'rewrite'	            => array( 'slug' => 'news', 'with_front' => false ), /* you can specify its url slug */
			'has_archive'         => 'news', /* you can rename the slug here */
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'post-formats')
		)
	);

	// Set default format for list appearance
	if(!get_theme_mod('nu_gm_news_list_format_setting', false))
		set_theme_mod('nu_gm_news_list_format_setting', 'news-listing');

	// Register rewrite rules
	nu_gm_news_rewrite_rules();

	// Add filter to update link rendering to align with rewrite rules
	add_filter('post_type_link', 'nu_gm_news_permalink', 10, 3);

	// Add filter to clean up Archive title for cutom content type
	add_filter( 'get_the_archive_title', 'nu_gm_news_update_archive_title');
}

// Function to register rewrite rules
function nu_gm_news_rewrite_rules() {
	global $wp_rewrite;
	$post_type = 'nu_gm_news';
	$news_struct = '/news'.str_replace('%postname%', '%nu_gm_news%', get_option('permalink_structure', '/%year%/%monthnum%/%day%/%postname%/'));
	$wp_rewrite->add_rewrite_tag("%nu_gm_news%", '([^/]+)', "nu_gm_news=");
	$wp_rewrite->add_permastruct('nu_gm_news', $news_struct, false);
	$search = $wp_rewrite->rewritecode;
	$replace = $wp_rewrite->rewritereplace;
	preg_match_all('/%[^%]+%/', $news_struct, $wildcard_matches);
	$id_index = count($wildcard_matches[0])-1;
	$position = 'top';

	add_rewrite_rule( str_replace( $search, $replace, $news_struct ), 'index.php?p=$matches['.$id_index.']&post_type=' . $post_type, $position );

	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/%day%/feed/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/%day%/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&feed=$matches[4]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/%day%/page/?([0-9]{1,})/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/%day%/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&post_type=' . $post_type, $position );

	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/feed/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]&post_type='. $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&feed=$matches[3]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/page/?([0-9]{1,})/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&paged=$matches[3]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/%monthnum%/?$' ), 'index.php?year=$matches[1]&monthnum=$matches[2]&post_type=' . $post_type, $position );

	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/feed/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?year=$matches[1]&feed=$matches[2]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?year=$matches[1]&feed=$matches[2]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/page/?([0-9]{1,})/?$' ), 'index.php?year=$matches[1]&paged=$matches[2]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news%date%/%year%/?$' ), 'index.php?year=$matches[1]&post_type='. $post_type, $position );

	add_rewrite_rule( str_replace( $search, $replace, 'news/author/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?author=$matches[1]&feed=$matches[2]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news/author/([^/]+)/(feed|rdf|rss|rss2|atom)/?$' ), 'index.php?author=$matches[1]&feed=$matches[2]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news/author/([^/]+)/page/?([0-9]{1,})/?$' ), 'index.php?author=$matches[1]&paged=$matches[2]&post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news/author/([^/]+)/?$' ), 'index.php?author=$matches[1]&post_type=' . $post_type, $position );

	add_rewrite_rule( str_replace( $search, $replace, 'news/?$' ), 'index.php?post_type=' . $post_type, $position );
	add_rewrite_rule( str_replace( $search, $replace, 'news/$' ), 'index.php?post_type=' . $post_type, $position );

}

// Clean up Archive title for cutom content type
function nu_gm_news_update_archive_title ($title) {
	if ( is_post_type_archive( 'nu_gm_news' ) ) {
		$title = str_replace('Archives: ', '', $title);
	}

	return $title;
}

// Adapted from get_permalink function in wp-includes/link-template.php
function nu_gm_news_permalink($permalink, $post_id, $leavename) {
	$post = get_post($post_id);

	if($post->post_type != 'nu_gm_news')
		return $permalink;

	$rewritecode = array(
		'%year%',
		'%monthnum%',
		'%day%',
		'%hour%',
		'%minute%',
		'%second%',
		$leavename? '' : '%postname%',
		'%post_id%',
		'%category%',
		'%author%',
		$leavename? '' : '%pagename%',
		$leavename? '' : '%pagename%',
	);

	if ( '' != $permalink && !in_array($post->post_status, array('draft', 'pending', 'auto-draft')) ) {
		$unixtime = strtotime($post->post_date);

		$category = '';
		if ( strpos($permalink, '%category%') !== false ) {
			$cats = get_the_category($post->ID);
			if ( $cats ) {
				usort($cats, '_usort_terms_by_ID'); // order by ID
				$category = $cats[0]->slug;
				if ( $parent = $cats[0]->parent )
					$category = get_category_parents($parent, false, '/', true) . $category;
			}
			// show default category in permalinks, without
			// having to assign it explicitly
			if ( empty($category) ) {
				$default_category = get_category( get_option( 'default_category' ) );
				$category = is_wp_error( $default_category ) ? '' : $default_category->slug;
			}
		}

		$author = '';
		if ( strpos($permalink, '%author%') !== false ) {
			$authordata = get_userdata($post->post_author);
			$author = $authordata->user_nicename;
		}

		$date = explode(" ",date('Y m d H i s', $unixtime));
		$rewritereplace =
		array(
			$date[0],
			$date[1],
			$date[2],
			$date[3],
			$date[4],
			$date[5],
			$post->post_name,
			$post->ID,
			$category,
			$author,
			$post->post_name,
		);
		$permalink = str_replace($rewritecode, $rewritereplace, $permalink);
	} else { // if they're not using the fancy permalink option
		$permalink = home_url('?p=' . $post->ID);
	}
	return $permalink;
}

// Register widget
add_action( 'widgets_init', function(){
	register_widget( 'GM_News_Widget' );
});

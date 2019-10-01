<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');

// Register meta box for hero banner fields
add_filter( 'rwmb_meta_boxes', 'nu_gm_hero_banner_register_meta_boxes', 10, 1 );
function nu_gm_hero_banner_register_meta_boxes( $meta_boxes ) {
  $post_types = array(
    'nu_gm_project',
    'nu_gm_news',
    'nu_gm_event',
    'page',
    'post',
  );
  $post_types = apply_filters( 'nu_gm_hero_banner_post_types', $post_types );

  if( !empty($post_types) ) {
    $box = array(
      'id'       => 'nu_gm_hero_banner',
      'title'    => __( 'Hero Banner', 'nu_gm' ),
      'pages'    => $post_types,
      'context'  => 'normal',
      'priority' => 'high',
      'fields'   => array(
        array(
          'name'        => __( 'Subtitle', 'nu_gm' ),
          'id'          => 'nu_gm_hero_banner_subtitle',
          'placeholder' => 'Hero Banner Subtitle...',
          'desc'        => 'Text that appears above the title in the hero banner.',
          'type'        => 'text',
        ),
        array(
          'name'        => __( 'Hero Image', 'nu_gm' ),
          'id'          => 'nu_gm_hero_banner_featured_image_setter',
          'desc'        => 'An image to be displayed in the hero banner (must be at least 1440x420 px). This is the same as setting the featured image for this content.',
          'type'        => 'custom_html',
          'std'         => '<button type="button" id="nu_gm_hero_banner_featured_image_setter-insert-media-button" class="button custom_upload_image_button add_media" onclick="jQuery(\'#set-post-thumbnail\').trigger(\'click\')">Set Featured & Hero Image</button>',
        ),
        array(
          'id'          => 'nu_gm_hero_banner_btn_header',
          'type'        => 'custom_html',
          'std'         => '<hr><strong>Button Links:</strong>',
        ),
        array(
          'id'          => 'nu_gm_hero_banner_btn',
          'type'        => 'group',
          'clone'       => true,
          'sort_clone'  => true,
          'max_clone'   => 3,
          'add_button'  => '+ Add Button',
          'fields'      => array(
            array(
              'name'        => __( 'Button URL', 'nu_gm' ),
              'id'          => 'nu_gm_hero_banner_btn_url',
              'placeholder' => 'Hero Button URL...',
              'desc'        => 'URL that the hero banner button links to.',
              'type'        => 'url',
            ),
            array(
              'name'        => __( 'Button Label', 'nu_gm' ),
              'id'          => 'nu_gm_hero_banner_btn_text',
              'placeholder' => 'Hero Button Label...',
              'desc'        => 'Text that appears in the hero banner button.',
              'type'        => 'text',
            ),
          ),
        ),
        array(
          'id'          => 'nu_gm_hero_banner_btn_footer',
          'type'        => 'custom_html',
          'std'         => '<hr>',
        ),
        array(
          'name'        => __( 'Hide Hero Banner', 'nu_gm' ),
          'id'          => 'nu_gm_hide_hero_banner_image',
          'desc'        => 'Hide the hero banner when viewing the full version of this post.',
          'type'        => 'checkbox',
        ),
      ),
    );
    $box = apply_filters( 'nu_gm_hero_banner_meta_box', $box );
    if(!empty($box)) {
      $meta_boxes[] = $box;
    }
  }

  return $meta_boxes;
}

// Hide hero banner meta box on page for posts and static front page
add_filter( 'rwmb_show_nu_gm_hero_banner', 'nu_gm_hero_banner_show_meta_box', 10, 2);
function nu_gm_hero_banner_show_meta_box($show, $meta_box){
  if(!empty($_GET['post'])) {
    $current_id = strval($_GET['post']);
    if($current_id && get_option('show_on_front', 'posts') != 'posts' && in_array($current_id, array(get_option('page_for_posts', -999), get_option('page_on_front', -999)))) {
      $show = false;
    }
  }
  return $show;
}

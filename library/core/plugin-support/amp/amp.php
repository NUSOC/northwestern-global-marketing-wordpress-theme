<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');

/************* AMP SUPPORT ******************/

/*
 * AMP Support Requires Plugin: https://wordpress.org/plugins/amp/
 */

// Add custom post type support
add_action( 'amp_init', 'nu_gm_amp_add_custom_post_types' );
function nu_gm_amp_add_custom_post_types() {
  add_post_type_support( 'nu_gm_news', AMP_QUERY_VAR );
}

// Add custom post metadata
add_filter( 'amp_post_template_metadata', 'nu_gm_amp_modify_json_metadata', 10, 2 );
function nu_gm_amp_modify_json_metadata( $metadata, $post ) {
  // Make sure schema gets set based on post type
  $metadata['@type'] = nu_gm_schema();

  // Set logo as header lockup image and fall back to Northwestern logo
  $logo = get_theme_mod('header_lockup_img_setting', '');
  if(empty($logo)) $logo = get_template_directory_uri().'/library/images/northwestern-university.svg';
  $metadata['publisher']['logo'] = array(
    '@type'  => 'ImageObject',
    'url'    => $logo,
    'height' => 52,
    'width'  => 170,
  );

  // Provide more metadata on author if available
  $author_id = $post->post_author;
  $dir_items = get_posts(array(
    'meta_key'    => 'nu_gm_wp_user',
    'meta_value'  => $author_id,
    'post_type'   => 'nu_gm_directory_item',
    'post_status' => 'publish',
    'numberposts' => 1
  ));
  $first_name   = get_the_author_meta( 'first_name', $author_id ) ?: false;
  $last_name    = get_the_author_meta( 'last_name', $author_id ) ?: false;
  $author_url   = get_author_posts_url( $author_id );
  if( !empty($dir_items) ) {
    $dir_item_id  = $dir_items[0]->ID;
    $last_name    = get_post_meta($dir_item_id, 'nu_gm_last_name', true) ?: (get_the_author_meta( 'last_name', $author_id ) ?: $last_name);
    $first_name   = get_post_meta($dir_item_id, 'nu_gm_first_name', true) ?: (get_the_author_meta( 'first_name', $author_id ) ?: $first_name);
    $author_url   = get_the_permalink($dir_item_id) ?: $author_url;
    $author_image = has_post_thumbnail($dir_item_id) ? wp_get_attachment_image_src( get_post_thumbnail_id( $dir_item_id ), 'people-medium')[0] : false;
  }
  if($first_name)   $metadata['author']['givenName']  = $first_name;
  if($last_name)    $metadata['author']['familyName'] = $last_name;
  if($author_image) $metadata['author']['image']      = $author_image;
  $metadata['author']['url']              = $author_url;
  $metadata['author']['mainEntityOfPage'] = $author_url;

  // Add tag metadata to post
  if( $tags = get_the_terms( $post->ID, 'post_tag' ) ) {
    $metadata['keywords'] = array_map(
      function($value){ return $value->name; },
      $tags
    );
  }

  // Add category metadata to post
  if( $categories = get_the_terms( $post->ID, 'category' ) ) {
    $metadata['genre'] = array_map(
      function($value){ return $value->name; },
      $categories
    );
  }

  // If no featured image is set, try to grab the first image in the post
  if(empty($metadata['image'])) {
    // Set defaults
    $metadata['image'] = array(
      '@type' => 'ImageObject',
      'url' => get_template_directory_uri().'/library/images/default-photo-feature-2.jpg',
      'width' => 720,
      'height' => 350,
    );
    $attached_images = get_posts(array(
      'post_type' => 'attachment',
      'post_mime_type' => 'image',
      'numberposts' => -1,
      'post_status' => null,
      'post_parent' => $post->ID
    ));
    if(!empty($attached_images)) {
      $metadata['image']['url'] = $attached_images[0]->guid;
      if($img_width = $attached_images[0]->width) $metadata['image']['width'] = $img_width;
      if($img_height = $attached_images[0]->height) $metadata['image']['height'] = $img_height;
    } else {
      // In case it's an older post and isn't attached in DB (embedded directly via img tag), check body for imgs
      $doc = new DOMDocument;
      $doc->loadHTML(nu_gm_amp_filter_tags($post->post_content));
      $imgs = $doc->getElementsByTagName('img');
      if(!empty($imgs)) {
        // Set the widest image available as the posts image
        $widest_width = 0;
        $widest_img   = 0;
        foreach ($imgs as $img) {
          if (($width = $img->getAttribute('width') && $width > $widest_width) || $widest_width == 0) {
            $width        = $width ?: 1;
            $widest_width = $width;
            $widest_img   = $img;
          }
        }
        if($widest_img) {
          $metadata['image']['url'] = $widest_img->getAttribute('src');
          if($img_width = $widest_img->getAttribute('width')) $metadata['image']['width'] = $img_width;
          if($img_height = $widest_img->getAttribute('height')) $metadata['image']['height'] = $img_height;
        }

        // Attempt to fetch original image and attributes from attachment metadata if it exists
        if($attachment_id = nu_gm_get_attachment_id_from_src($metadata['image']['url'])) {
          $attachment_meta = get_post_meta($attachment_id, '_wp_attachment_metadata');
          if(!empty($attachment_meta)) {
            if(!empty($attachment_meta[0]['file'])) $metadata['image']['url']      = content_url().'/'.$attachment_meta[0]['file'];
            if(!empty($attachment_meta[0]['width'])) $metadata['image']['width']   = $attachment_meta[0]['width'];
            if(!empty($attachment_meta[0]['height'])) $metadata['image']['height'] = $attachment_meta[0]['height'];
          }
        }
      }
    }
  }

  return $metadata;
}

// Replace default templates with our custom templates
add_filter( 'amp_post_template_file', 'nu_gm_amp_set_custom_template', 10, 3 );
function nu_gm_amp_set_custom_template( $file, $type, $post ) {
  if ( 'single' === $type ) {
    $file = dirname( __FILE__ ) . '/templates/single.php';
  } else if ( 'style' === $type ) {
    $file = dirname( __FILE__ ) . '/templates/style.php';
  } else if ( 'meta-author' === $type ) {
    $file = dirname( __FILE__ ) . '/templates/meta-author.php';
  }
  return $file;
}

// Insure no bad html tags have slipped through validation
add_action( 'pre_amp_render_post', 'nu_gm_amp_add_custom_actions' );
function nu_gm_amp_add_custom_actions() {
  add_filter( 'the_content', 'nu_gm_amp_filter_tags' );
}
function nu_gm_amp_filter_tags($content) {
  $tags_to_strip = array('font', 'object', 'param', 'embed');
  $tags_to_strip_re = '/<[\/]?(?:'.implode('|', $tags_to_strip).')[^>]*>/iU';
  $content = preg_replace($tags_to_strip_re, '', $content);
  return $content;
}

// Add dynamic hero image background style
add_action( 'amp_post_template_css', 'nu_gm_amp_my_additional_css_styles' );
function nu_gm_amp_my_additional_css_styles( $amp_template ) {
  $post_id = $amp_template->get( 'post_id' );
  $attachment_id = get_post_thumbnail_id( $post_id );
  $attachment_url = wp_get_attachment_image_src($attachment_id, 'hero-standard')[0];
  ?>
  .hero-image {
    background: #4e2a84 url("<?php echo $attachment_url; ?>") no-repeat center / cover;
  }
  <?php
}

// Replace gallery embed class with custom one that takes size into account
add_filter( 'amp_content_embed_handlers', 'nu_gm_amp_replace_gallery_embed', 10, 2 );
function nu_gm_amp_replace_gallery_embed( $embed_handler_classes, $post ) {
  require_once( dirname( __FILE__ ) . '/classes/class-nu-gm-amp-gallery-embed.php' );
  $embed_handler_classes[ 'NU_GM_AMP_Gallery_Embed_Handler' ] = array();
  unset($embed_handler_classes[ 'AMP_Gallery_Embed_Handler' ]);
  return $embed_handler_classes;
}

// Forcibly deregister all JS
add_action( 'amp_post_template_footer', 'nu_gm_amp_deregister_scripts', 10 );
function nu_gm_amp_deregister_scripts( $amp_template ) {
  add_action( 'wp_print_scripts', 'nu_gm_amp_deregister_scripts', 10 );
  global $wp_scripts;
  foreach( $wp_scripts->queue as $handle ) {
    wp_deregister_script($handle);
  }
}

// Forcibly deregister all CSS
add_action( 'amp_post_template_footer', 'nu_gm_amp_deregister_styles', 10 );
function nu_gm_amp_deregister_styles( $amp_template ) {
  add_action( 'wp_print_styles', 'nu_gm_amp_deregister_styles', 10 );
  global $wp_styles;
  foreach( $wp_styles->queue as $handle ) {
    wp_deregister_style($handle);
  }
}

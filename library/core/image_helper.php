<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');


/************* CUSTOM IMAGE CROP OPTIONS *************/

// Upscale thumbnails when the source image is smaller than the thumbnail size. Retain the aspect ratio.
function force_fit_crop_thumbnail_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
  if ( !$crop ) return null; // let the wordpress default function handle this

  // crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
  $aspect_ratio = $orig_w / $orig_h;
  $new_w = min($new_w, $orig_w);
  $new_h = min($new_h, $orig_h);

  if ( !$new_w ) {
    $new_w = intval($new_h * $aspect_ratio);
  }

  if ( !$new_h ) {
    $new_h = intval($new_w / $aspect_ratio);
  }

  $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

  $crop_w = round($new_w / $size_ratio);
  $crop_h = round($new_h / $size_ratio);

  $s_x = floor( ($orig_w - $crop_w) / 2 );
  $s_y = floor( ($orig_h - $crop_h) / 2 );

  return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
add_filter( 'image_resize_dimensions', 'force_fit_crop_thumbnail_upscale', 10, 6 );


/************* IMAGE SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'nu-gm-thumb-600', 600, 150, true );
add_image_size( 'nu-gm-thumb-300', 300, 100, true );

// Add custom image sizes
add_image_size ( 'feature-box-3', 360, 200, true );
add_image_size ( 'photo-feature-3', 480, 350, true );
add_image_size ( 'feature-box-2', 550, 310, true );
add_image_size ( 'photo-feature-2', 720, 350, true );
add_image_size ( 'hero-landing', 1440, 600, true );
add_image_size ( 'hero-standard', 1440, 420, true );
add_image_size ( 'news-listing', 170, 170, true );
add_image_size ( 'people-big', 360, 360, true );
add_image_size ( 'people-medium', 265, 265, true );
add_image_size ( 'people-small', 170, 170, true );

// Add image formats to image size selection UI
add_filter( 'image_size_names_choose', 'nu_gm_custom_image_sizes' );
function nu_gm_custom_image_sizes( $sizes ) {
  return array_merge( $sizes, array(
    'nu-gm-thumb-600' => __('600px by 150px', 'nu_gm'),
    'nu-gm-thumb-300' => __('300px by 100px', 'nu_gm'),
    'hero-landing' => __('1440px by 600px (Hero Image)', 'nu_gm'),
    'hero-standard' => __('1440px by 420px (Hero Image)', 'nu_gm'),
  ));
}


/************* IMAGE SWIPERJS GALLERY SUPPORT *************/

// Rewrite WP Gallery output as swiperJS slider
function nugm_gallery_style($atts, $instance, $output = ''){
  $return = $output; // fallback

  $image_size = (empty($atts['size']) ? 'hero-landing' : $atts['size']);
  $image_size_metadata = nu_gm_get_image_size($image_size);

  $nugm_gallery_output = '<div aria-label="carousel" class="wp-attachment wp-gallery" style="width:'.$image_size_metadata['width'].'px;height:auto;max-width:100%;"><div class="swiper-container"><div class="swiper-wrapper">';

  $attachment_ids = explode(',', $atts['ids']);
  foreach ($attachment_ids as $key => $attachment_id) {
    $attachment_metadata = wp_get_attachment_metadata($attachment_id);
    $nugm_gallery_output .= '<div class="swiper-slide" itemprop="image" itemscope itemid="'.home_url().'#attachment-'.$attachment_id.'" itemtype="http://schema.org/ImageObject">';
    $nugm_gallery_output .= wp_get_attachment_image($attachment_id, $image_size, false, array('itemprop' => 'url'));
    if(!empty($attachment_metadata['image_meta']['caption']))
      $nugm_gallery_output .= '<div class="caption" itemprop="caption">' . $attachment_metadata['image_meta']['caption'] . '</div>';
    $nugm_gallery_output .= '<meta itemprop="width" content="'.$image_size_metadata['width'].'" hidden />';
    $nugm_gallery_output .= '<meta itemprop="height" content="'.$image_size_metadata['height'].'" hidden />';
    $nugm_gallery_output .= '</div>';
  }

  $nugm_gallery_output .= '</div>';
  $nugm_gallery_output .= '<div class="showcase" aria-label="carousel buttons"><div class="swiper-button-next"></div><div class="swiper-button-prev"></div></div>';
  $nugm_gallery_output .= '</div></div>';

  if( !empty( $nugm_gallery_output ) ) {
    $return = $nugm_gallery_output;
  }

  return $return;
}
add_filter( 'post_gallery', 'nugm_gallery_style', 10, 3 );


/************* IMAGE HELPER FUNCTIONS *************/

/**
 * Get size information for all currently-registered image sizes.
 *
 * @global $_wp_additional_image_sizes
 * @uses   nu_gm_get_intermediate_image_sizes()
 * @return array $sizes Data for all currently-registered image sizes.
 */
function nu_gm_get_image_sizes() {
  global $_wp_additional_image_sizes;

  $sizes = array();

  foreach ( get_intermediate_image_sizes() as $_size ) {
    if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
      $sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
      $sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
      $sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
    } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
      $sizes[ $_size ] = array(
        'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
        'height' => $_wp_additional_image_sizes[ $_size ]['height'],
        'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
      );
    }
  }

  return $sizes;
}

/**
 * Get size information for a specific image size.
 *
 * @uses   nu_gm_get_image_sizes()
 * @param  string $size The image size for which to retrieve data.
 * @return bool|array $size Size data about an image size or false if the size doesn't exist.
 */
function nu_gm_get_image_size( $size ) {
  $sizes = nu_gm_get_image_sizes();

  if ( isset( $sizes[ $size ] ) ) {
    return $sizes[ $size ];
  }

  return false;
}

/**
 * Get the width of a specific image size.
 *
 * @uses   nu_gm_get_image_size()
 * @param  string $size The image size for which to retrieve data.
 * @return bool|string $size Width of an image size or false if the size doesn't exist.
 */
function nu_gm_get_image_width( $size ) {
  if ( ! $size = nu_gm_get_image_size( $size ) ) {
    return false;
  }

  if ( isset( $size['width'] ) ) {
    return $size['width'];
  }

  return false;
}

/**
 * Get the height of a specific image size.
 *
 * @uses   nu_gm_get_image_size()
 * @param  string $size The image size for which to retrieve data.
 * @return bool|string $size Height of an image size or false if the size doesn't exist.
 */
function nu_gm_get_image_height( $size ) {
  if ( ! $size = nu_gm_get_image_size( $size ) ) {
    return false;
  }

  if ( isset( $size['height'] ) ) {
    return $size['height'];
  }

  return false;
}

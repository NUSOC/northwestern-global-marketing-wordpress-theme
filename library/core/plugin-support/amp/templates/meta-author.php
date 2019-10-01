<?php $post_author = $this->get( 'post_author' );
    $dir_items = get_posts(array(
      'meta_key' => 'nu_gm_wp_user',
      'meta_value' => $post_author->ID,
      'post_type' => 'nu_gm_directory_item',
      'post_status' => 'publish',
      'numberposts' => 1
    ));
    if( !empty($dir_items) ) {
      $dir_item_id = $dir_items[0]->ID;
      $author_image = has_post_thumbnail($dir_item_id) ? wp_get_attachment_image_src( get_post_thumbnail_id( $dir_item_id ), 'people-small')[0] : false;
    }
?>
<li class="amp-wp-byline">
	<?php if ( empty($author_image) && function_exists( 'get_avatar_url' ) ) : ?>
	<amp-img src="<?php echo esc_url( get_avatar_url( $post_author->user_email, array(
		'size' => 24,
	) ) ); ?>" width="24" height="24" layout="fixed"></amp-img>
	<?php elseif( !empty($author_image) && $author_image !== false ): ?>
    <amp-img src="<?php echo $author_image; ?>" width="30" height="30" layout="fixed"></amp-img>
  <?php endif; ?>
	<span class="amp-wp-author"><?php echo esc_html( $post_author->display_name ); ?></span>
</li>

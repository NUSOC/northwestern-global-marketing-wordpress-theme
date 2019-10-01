
<span class="nu-gm-microdata" itemprop="author" itemscope itemtype="http://schema.org/Person" hidden style="display:none;">
	<?php
  	$author_id = get_the_author_meta( 'ID' );
		$dir_items = get_posts(array(
	    'meta_key' => 'nu_gm_wp_user',
	    'meta_value' => $author_id,
	    'post_type' => 'nu_gm_directory_item',
	    'post_status' => 'publish',
	    'numberposts' => 1
		));
		if( !empty($dir_items) ) {
			$dir_item_id = $dir_items[0]->ID;
			$last_name = get_post_meta($dir_item_id, 'nu_gm_last_name', true) ?: (get_the_author_meta( 'last_name' ) ?: false);
			$first_name = get_post_meta($dir_item_id, 'nu_gm_first_name', true) ?: (get_the_author_meta( 'first_name' ) ?: false);
      $author_image = has_post_thumbnail($dir_item_id) ? wp_get_attachment_image_src( get_post_thumbnail_id( $dir_item_id ), 'people-medium')[0] : false;
		} else {
			$first_name = get_the_author_meta( 'first_name' ) ?: false;
			$last_name = get_the_author_meta( 'last_name' ) ?: false;
		}
    $author_url = get_author_posts_url( $author_id );
  ?>
  <meta itemprop="url mainEntityOfPage" content="<?php echo $author_url; ?>" hidden />
  <?php if($display_name = get_the_author()): ?><meta itemprop="name" content="<?php echo $display_name; ?>" hidden /><?php endif; ?>
  <?php if($first_name): ?><meta itemprop="givenName" content="<?php echo $first_name; ?>" hidden /><?php endif; ?>
  <?php if($last_name): ?><meta itemprop="familyName" content="<?php echo $last_name; ?>" hidden /><?php endif; ?>
  <?php if($author_url): ?><meta itemprop="image" content="<?php echo $author_url; ?>" hidden /><?php endif; ?>
</span>

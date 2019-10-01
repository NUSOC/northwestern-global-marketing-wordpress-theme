<?php
/*
Comments Section
*/

// don't load it if you can't comment
if ( post_password_required() ) {
  return;
}

?>

<?php if (comments_open()): ?>

  <div class="comments-section">

  <?php if ( have_comments() ) : ?>

      <h4 id="comments-title" class="h2"><?php comments_number( __( '<span>No</span> Comments', 'nu_gm' ), __( '<span>One</span> Comment', 'nu_gm' ), __( '<span>%</span> Comments', 'nu_gm' ) );?>:</h4>
      <meta itemprop="commentCount" content="<?php echo comments_number(0,1,__('%', 'nu_gm')); ?>" />

      <div class="commentlist">
        <?php
          wp_list_comments( array(
            'style'             => 'div',
            'short_ping'        => true,
            'avatar_size'       => 40,
            'callback'          => 'nu_gm_comments',
            'type'              => 'all',
            'reply_text'        => __('Reply', 'nu_gm'),
            'page'              => '',
            'per_page'          => '',
            'reverse_top_level' => null,
            'reverse_children'  => ''
          ) );
        ?>
      </div>

      <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
      	<nav class="navigation comment-navigation" role="navigation">
        	<div class="comment-nav-prev"><?php previous_comments_link( __( '&larr; Previous Comments', 'nu_gm' ) ); ?></div>
        	<div class="comment-nav-next"><?php next_comments_link( __( 'More Comments &rarr;', 'nu_gm' ) ); ?></div>
      	</nav>
      <?php endif; ?>

      <?php if ( ! comments_open() ) : ?>
      	<p class="no-comments"><?php _e( 'Comments are closed.' , 'nu_gm' ); ?></p>
      <?php endif; ?>

    <?php endif; ?>

    <?php comment_form( array(
      'class_form' => 'web-form',
      'title_reply' => 'Leave a Comment',
      'title_reply_before' => '<h4 id="reply-title" class="comment-reply-title">',
      'title_reply_after' => '</h4>',
    ) ); ?>

  </div>

<?php endif; ?>

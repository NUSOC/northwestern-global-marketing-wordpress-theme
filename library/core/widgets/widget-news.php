<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');



/**
 * Adds GM News widget.
 */
class GM_News_Widget extends WP_Widget {
  /**
   * Register widget with WordPress.
   */
  function __construct() {
    parent::__construct(
      'GM_News_Widget', // Base ID
      __('News Widget', 'nu_gm'), // Name
      array( 'classname' => 'widget_gm_news', 'description' => __( 'Display a News Feed', 'nu_gm' ), ) // Args
    );
    $this->alt_option_name = 'widget_gm_news';

    // Enable caching
    add_action( 'save_post', array($this, 'flush_widget_cache') );
    add_action( 'deleted_post', array($this, 'flush_widget_cache') );
    add_action( 'switch_theme', array($this, 'flush_widget_cache') );
  }
  /**
   * Front-end display of widget.
   *
   * @see WP_Widget::widget()
   *
   * @param array $args     Widget arguments.
   * @param array $instance Saved values from database.
   */
  function widget( $args, $instance ) {
    // Retrieve cached widget content
    $cache = get_transient($this->id);

    if ( !empty( $cache ) ) {
      echo $cache;
      return;
    }

    // Add widget-fullwidth if needed
    if( $instance[ 'is_fullwidth_widget' ] ) {
      $args['before_widget'] = preg_replace('/\A\s*<div/miA', '<section', $args['before_widget']);
      $args['before_widget'] = preg_replace('/class="widget/mi', 'class="widget widget-fullwidth', $args['before_widget']);
      $args['after_widget'] = preg_replace('/<\/div>\s*\z/miD', '</section>', $args['after_widget']);
    }

    $output = '';
    $output .= $args['before_widget'];
    if ( ! empty( $instance['title'] ) ) {
      $output .= $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
    }

    // Construct custom query
    $max_items = (($instance['max_items'] > 3 && !$instance[ 'is_fullwidth_widget' ]) ?  3 : $instance[ 'max_items' ]);
    $news_query = new WP_Query(array(
      'post_type' => 'nu_gm_news',
      'posts_per_page' => $max_items,
    ));

    // Begin output
    ob_start();
    ?>

    <div class="landing-page">
      <?php if(empty($instance[ 'is_fullwidth_widget' ] )): ?><div class="news-event"><?php endif; ?>
      <div class="news<?php if($instance[ 'is_fullwidth_widget' ] ) { echo '-full'; } ?>" itemscope itemtype="http://schema.org/ItemList">
        <?php if ($news_query->have_posts()) : while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
          <div class="news-box" itemprop="itemListElement" itemscope itemref="footer-publisher-info" itemtype="http://schema.org/NewsArticle">
            <div class="news-image" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
              <?php if( has_post_thumbnail() ): ?>
                <?php the_post_thumbnail('news-listing', array('itemprop' => 'url')); ?>
              <?php else: ?>
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/library/images/default-news-listing.jpg" width="170" height="170" alt="<?php the_title(); ?>" itemprop="url" />
              <?php endif; ?>
              <meta itemprop="width" content="170" hidden />
              <meta itemprop="height" content="170" hidden />
            </div>
            <div class="news-text">
              <h4 itemprop="name headline"><a href="<?php the_permalink(); ?>" itemprop="url mainEntityOfPage sameAs" target="_blank" title="Read Article: <?php the_title(); ?>"><?php the_title(); ?></a></h4>
              <div class="news-date"><time class="updated entry-time" datetime="<?php echo get_the_time('Y-m-d'); ?>" itemprop="datePublished"><?php echo get_the_time(get_option('date_format')); ?></time></div>
            </div>
            <meta itemprop="dateModified" content="<?php echo the_modified_time('Y-m-d'); ?>" hidden />
            <span class="entry-author author" itemprop="author" itemscope itemtype="http://schema.org/Person" hidden>
              <meta itemprop="url mainEntityOfPage" content="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" hidden />
              <?php if($display_name = get_the_author()): ?><meta itemprop="name" content="<?php echo $display_name; ?>" hidden /><?php endif; ?>
              <?php if($first_name == get_the_author_meta( 'first_name' )): ?><meta itemprop="givenName" content="<?php echo $first_name; ?>" hidden /><?php endif; ?>
              <?php if($last_name == get_the_author_meta( 'last_name' )): ?><meta itemprop="familyName" content="<?php echo $last_name; ?>" hidden /><?php endif; ?>
            </span>
          </div>
        <?php endwhile; ?>
        <p class="more-news-page-link-wrapper content <?php if( $instance[ 'is_fullwidth_widget' ] ): ?>link<?php endif; ?>" style="text-align:center;"><a class="more-news-page-link<?php if(empty($instance[ 'is_fullwidth_widget' ] )) { echo ' button'; } ?>" href="<?php echo esc_url( home_url( '/news/' ) ); ?>" title="View More News">More News</a></p>
        <?php endif; ?>
      </div>
      <?php if(empty($instance[ 'is_fullwidth_widget' ] )): ?></div><?php endif; ?>
    </div>

    <?php
    $output .= ob_get_contents();
    ob_end_clean();

    $output .= $args['after_widget'];

    echo $output;

    // Cache content
    $cache = $output;
    set_transient($this->id, $cache, $instance[ 'cache_lifetime' ] * 60);
  }
  /**
   * Back-end widget form.
   *
   * @see WP_Widget::form()
   *
   * @param array $instance Previously saved values from database.
   */
  function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'News', 'nu_gm' );
    }
    if ( isset( $instance[ 'max_items' ] ) ) {
      $max_items = $instance[ 'max_items' ];
    }
    else {
      $max_items = 6;
    }
    if ( isset( $instance[ 'cache_lifetime' ] ) ) {
      $cache_lifetime = $instance[ 'cache_lifetime' ];
    }
    else {
      $cache_lifetime = 60;
    }
    if ( isset( $instance[ 'is_fullwidth_widget' ] ) ) {
      $is_fullwidth_widget = $instance[ 'is_fullwidth_widget' ];
    }
    else {
      $is_fullwidth_widget = false;
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'nu_gm' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'max_items' ); ?>"><?php _e( 'Maximum Number of Items to Show:', 'nu_gm' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'max_items' ); ?>" name="<?php echo $this->get_field_name( 'max_items' ); ?>" type="number" value="<?php echo esc_attr( $max_items ); ?>">
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'is_fullwidth_widget' ); ?>"><?php _e( 'Display as Full-Width:', 'nu_gm' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'is_fullwidth_widget' ); ?>" name="<?php echo $this->get_field_name( 'is_fullwidth_widget' ); ?>" type="checkbox" <?php echo (esc_attr( $is_fullwidth_widget ) == true ? 'checked="checked"' : ''); ?>>
      <br><em>Automatically reduces max # of items to show to 3 if not displaying in fullwidth.</em>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id( 'cache_lifetime' ); ?>"><?php _e( 'Minutes to Cache Output:', 'nu_gm' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'cache_lifetime' ); ?>" name="<?php echo $this->get_field_name( 'cache_lifetime' ); ?>" type="number" value="<?php echo esc_attr( $cache_lifetime ); ?>">
    </p>
    <?php
  }
  /**
   * Flush widget cache
   */
  function flush_widget_cache() {
    delete_transient($this->id);
  }
  /**
   * Sanitize widget form values as they are saved.
   *
   * @see WP_Widget::update()
   *
   * @param array $new_instance Values just sent to be saved.
   * @param array $old_instance Previously saved values from database.
   *
   * @return array Updated safe values to be saved.
   */
  function update( $new_instance, $old_instance ) {
    // Update instance with new values
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['max_items'] = ( ! empty( $new_instance['max_items'] ) ) ? strip_tags( $new_instance['max_items'] ) : 6;
    $instance['is_fullwidth_widget'] = ( ! empty( $new_instance['is_fullwidth_widget'] ) ) ? $new_instance['is_fullwidth_widget'] : false;
    $instance['cache_lifetime'] = ( ! empty( $new_instance['cache_lifetime'] ) ) ? strip_tags( $new_instance['cache_lifetime'] ) : 60;

    // Flush widget cache
    $this->flush_widget_cache();

    return $instance;
  }
} // class GM_News_Widget

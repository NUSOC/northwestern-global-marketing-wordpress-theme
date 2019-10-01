<?php global $wp_query; ?>
<?php $is_archive = (!$wp_query->is_singular('nu_gm_event') || $wp_query->is_post_type_archive('nu_gm_event')); ?>
<?php $is_single  = $wp_query->is_singular('nu_gm_event'); ?>

<article id="post-<?php the_ID(); ?>" aria-labelledby="post-<?php the_ID(); ?>-title" class="event" itemscope <?php if($is_archive): ?>itemprop="itemListElement" <?php endif; ?> itemtype="<?php echo nu_gm_schema(); ?>">
  <?php
    // Fetch date/time fields
    $start_date = get_post_meta(get_the_ID(), 'nu_gm_event_start_date', true) ?: false;
    $start_time = get_post_meta(get_the_ID(), 'nu_gm_event_start_time', true) ?: false;
    $end_date = get_post_meta(get_the_ID(), 'nu_gm_event_end_date', true) ?: false;
    $end_time = get_post_meta(get_the_ID(), 'nu_gm_event_end_time', true) ?: false;

    // Calculate event start for metadata
    if($start_date) {
      $start_date_only_str = date('m/d/Y', $start_date);
      if($start_time) {
        $start_time_str = date('g:ia', strtotime($start_time));
        $start_date_str = $start_date_only_str . ' ' . $start_time_str . ' CST';
      }
      $start_datetime = date_create($start_date_str);
      $start_date_iso_8601 = $start_datetime->format('c');
    }

    // Calculate event end for metadata
    if($end_date) {
      $end_date_only_str = date('m/d/Y', $end_date);
      if($end_time) {
        $end_time_str = date('g:ia', strtotime($end_time));
        $end_date_str = $end_date_only_str . ' ' . $end_time_str . ' CST';
      }
      $end_datetime = date_create($end_date_str);
      $end_date_iso_8601 = $end_datetime->format('c');
    }

    // Calculate event duration for metadata
    if($start_date && (($start_time && $end_time) || $end_date)) {
      $duration = $end_datetime->diff($start_datetime);
      $duration_iso_8601 = $start_datetime->format('Y-m-d').'/'.$duration->format('P%dD%HH%II');
    } else {
      $duration_iso_8601 = false;
    }

    // Determine if this is a multi-day event
    $multi_day = ($start_date_only_str && $end_date_only_str && $start_date_only_str != $end_date_only_str);

    // Fetch location fields
    $location_name                = get_post_meta(get_the_ID(), 'nu_gm_event_location_title', true) ?: false;
    $location_url                 = get_post_meta(get_the_ID(), 'nu_gm_event_location_url', true) ?: false;
    $location_address             = get_post_meta(get_the_ID(), 'nu_gm_event_location_address', true) ?: false;
    $location_address_additional  = get_post_meta(get_the_ID(), 'nu_gm_event_location_address_additional', true) ?: false;
    $location_city                = get_post_meta(get_the_ID(), 'nu_gm_event_location_city', true) ?: false;
    $location_state               = get_post_meta(get_the_ID(), 'nu_gm_event_location_state', true) ?: false;
    $location_zip                 = get_post_meta(get_the_ID(), 'nu_gm_event_location_zip', true) ?: false;
  ?>
  <?php if($start_date): ?>
    <div class="event-date">
      <div class="month"><?php echo date('M', $start_date); ?></div>
      <div class="day"><?php echo date('j', $start_date); ?></div>
      <div class="year"><?php echo date('Y', $start_date); ?></div>
    </div>
  <?php endif; ?>
    <div class="event-description">
      <h4 itemprop="name" id="post-<?php the_ID(); ?>-title">
        <?php if($is_archive): ?>
          <a href="<?php the_permalink(); ?>" itemprop="url" title="View <?php the_title(); ?>">
        <?php endif; ?>
        <?php the_title(); ?>
        <?php if($is_archive): ?>
          </a>
        <?php endif; ?>
      </h4>
      <?php if($start_time): ?>
        <p class="event-time-location event-time">
          <?php if($multi_day): // if($is_single && $multi_day): ?>
            <?php echo $start_datetime->format('M j, '); ?>
          <?php endif; ?>
          <?php echo $start_time_str; ?>
          <?php if($end_time_str): // if($is_single && $end_time_str): ?>
            <?php echo ' - '; ?>
            <?php if($multi_day) echo $end_datetime->format('M j, '); ?>
            <?php echo $end_time_str; ?>
          <?php endif; ?>
        </p>
        <?php if($location_name): ?>
          <p class="event-time-location event-location"><?php echo $location_name; ?></p>
        <?php endif; ?>
      <?php endif; ?>
      <?php if($is_single): ?>
        <div class="entry-content" itemprop="description"><?php the_content(); ?></div>
      <?php endif; ?>
      <span class="microdata-hidden" hidden style="display:none;visibility:hidden;">
        <?php if($start_date_iso_8601): ?>
          <time datetime="<?php echo $start_date_iso_8601; ?>" itemprop="startDate" hidden></time>
        <?php endif; ?>
        <?php if($end_date_iso_8601): ?>
          <time datetime="<?php echo $end_date_iso_8601; ?>" itemprop="endDate" hidden></time>
        <?php endif; ?>
        <?php if($duration_iso_8601): ?>
          <time datetime="<?php echo $duration_iso_8601; ?>" itemprop="duration" hidden></time>
        <?php endif; ?>
        <?php if($location_address && $location_city && $location_state && $location_zip): ?>
          <span itemprop="location" itemscope itemtype="http://schema.org/Place" hidden>
            <?php
              $location_full_address =  '<span itemprop="streetAddress">'.$location_address.'</span>, '.
                                        '<span itemprop="addressLocality">'.$location_city.'</span>, '.
                                        '<span itemprop="addressRegion">'.$location_state.'</span> '.
                                        '<span itemprop="postalCode">'.$location_zip.'</span>';
            ?>
            <meta itemprop="name" content="<?php echo $location_name ?: strip_tags($location_address); ?>" hidden />
            <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $location_full_address; ?></span>
          </span>
        <?php endif; ?>
        <?php if( has_post_thumbnail() ): ?><meta itemprop="image" content="<?php the_post_thumbnail_url(); ?>" hidden /><?php endif; ?>
      </span>
    </div>
    <?php echo nu_gm_get_the_loop_index(); ?>
</article>

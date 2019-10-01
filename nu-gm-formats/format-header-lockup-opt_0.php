<h1>
  <a href="<?php echo home_url(); ?>">
    <?php $header_lockup_img_url = get_theme_mod('header_lockup_img_setting', ''); ?>
    <?php if(!empty($header_lockup_img_url)): ?>
      <img alt="<?php bloginfo('name'); ?> logo" src="<?php echo $header_lockup_img_url; ?>">
      <span class="hide-label"><?php bloginfo('name'); ?> logo</span>
    <?php else: ?>
      <?php bloginfo('name'); ?>
    <?php endif; ?>
  </a>
</h1>
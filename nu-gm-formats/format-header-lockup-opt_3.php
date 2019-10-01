<?php
$header_lockup_line_1_text = get_theme_mod('header_lockup_line_1_text_setting');
$header_lockup_line_1_link = get_theme_mod('header_lockup_line_1_link_setting') ?: home_url();
$header_lockup_line_2_text = get_theme_mod('header_lockup_line_2_text_setting') ?: get_bloginfo('name');
$header_lockup_line_2_link = get_theme_mod('header_lockup_line_2_link_setting') ?: home_url();
?>
<h1>
  <?php if($header_lockup_line_1_text): ?>
    <a href="<?php echo $header_lockup_line_1_link; ?>">
      <span><?php echo $header_lockup_line_1_text; ?></span>
    </a>
  <?php endif; ?>
  <a href="<?php echo $header_lockup_line_2_link; ?>"><?php echo $header_lockup_line_2_text; ?></a>
</h1>
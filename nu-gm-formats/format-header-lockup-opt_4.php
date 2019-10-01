<?php
$header_lockup_line_1_text = get_theme_mod('header_lockup_line_1_text_setting') ?: 'Northwestern';
$header_lockup_line_1_link = get_theme_mod('header_lockup_line_1_link_setting') ?: home_url();
$header_lockup_line_2_text = get_theme_mod('header_lockup_line_2_text_setting') ?: get_bloginfo('name');
$header_lockup_line_2_link = get_theme_mod('header_lockup_line_2_link_setting') ?: home_url();
?>
<h1 class="small">
  <a href="<?php echo $header_lockup_line_1_link; ?>">
    <span><?php echo $header_lockup_line_1_text; ?></span>
  </a>
</h1>
<h2>
  <a href="<?php echo $header_lockup_line_2_link; ?>"><?php echo $header_lockup_line_2_text; ?></a>
</h2>
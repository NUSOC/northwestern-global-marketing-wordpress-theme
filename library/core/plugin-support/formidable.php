<?php

// Block direct requests
if ( !defined('ABSPATH') )
  die('-1');

/************* FORMIDABLE SUPPORT ******************/

// Add form classes
function nugm_form_classes($form){
  echo 'web-form';
}
add_action('frm_form_classes', 'nugm_form_classes');

// Update existing form classes to remove default formidable style
function nugm_update_formidable_markup( $form ) {
  $form = str_replace('with_frm_style', '', $form);
  $form = str_replace('frm_style_formidable-style', '', $form);
  $form = str_replace('form-field', 'form-field field', $form);
  $form = str_replace('<span class="frm_required">*</span>', '<span class="frm_required required">*</span>', $form);
  return $form;
}
add_filter( 'frm_filter_final_form', 'nugm_update_formidable_markup' );

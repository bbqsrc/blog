<?php
/*
Plugin Name: Brendan's Language Shortcode
Plugin URI: https://github.com/bbqsrc/blog
Description: Shortcode for marking up my translations
Version: 0.1
Author: Brendan Molloy
Author URI: http://brendan.so
License: CC0
*/

function brendan_lang_shortcode_lang($atts, $content) {
  $text_lang = $atts[0];
  if ($text_lang == null) { $text_lang = "en"; }

  $lang = $_GET['lang']; 
  if ($lang == null) { $lang = "en"; }

  if ($lang == $text_lang) {
    return $content;
  }
}
add_shortcode('lang', 'brendan_lang_shortcode_lang');

function brendan_lang_shortcode_list($atts, $content) {
  $out = '';
  foreach ($atts as $lang) {
    echo $lang;
    $out .= "<a href='?lang=" . $lang . "'>" . $lang . "</a>\n";
  }
  return $out;
}
add_shortcode('lang-list', 'brendan_lang_shortcode_list');

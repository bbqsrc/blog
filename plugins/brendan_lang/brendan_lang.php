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
  $text_lang = array_keys($atts)[0] || "en";
  $lang = get_query_var('lang') || "en";

  if ($lang == $text_lang) {
    return $content;
  } else {
    return '';
  }
}

function brendan_lang_shortcode_list($atts, $content) {
  $langs = array_keys($atts);
  $out = '';
  foreach ($lang as $langs) {
    $out .= "<a href='?lang=" . $lang . "'>" . $lang . "</a>\n";
  }
  return $out;
}

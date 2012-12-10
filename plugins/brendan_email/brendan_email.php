<?php
/*
Plugin Name: Brendan's Email Shortcode
Plugin URI: https://github.com/bbqsrc/blog
Description: Shortcode for marking up my emails
Version: 0.1
Author: Brendan Molloy
Author URI: http://brendan.so
License: CC0
 */

function brendan_get_shortcode_regex() {
	return
		  '\\['                              // Opening bracket
		. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		. "(email)"                     // 2: Shortcode name
		. '\\b'                              // Word boundary
		. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		. ')'
		. '(?:'
		.     '(\\/)'                        // 4: Self closing tag ...
		.     '\\]'                          // ... and closing bracket
		. '|'
		.     '\\]'                          // Closing bracket
		.     '(?:'
		.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.         ')'
		.         '\\[\\/\\2\\]'             // Closing shortcode tag
		.     ')?'
		. ')'
		. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
}

function brendan_do_email_shortcode_tag($m) {
    // allow [[foo]] syntax for escaping a tag
    if ( $m[1] == '[' && $m[6] == ']' ) {
        return substr($m[0], 1, -1);
    }

    $tag = $m[2];
    $attr = shortcode_parse_atts( $m[3] );

    if ( isset( $m[5] ) ) {
        // enclosing tag - extra parameter
        return $m[1] . call_user_func( 'brendan_email_shortcode', $attr, $m[5], $tag ) . $m[6];
    } else {
        // self-closing tag
        return $m[1] . call_user_func( 'brendan_email_shortcode', $attr, null,  $tag ) . $m[6];
    }
}
function brendan_do_email_shortcode($content) {
    $pattern = brendan_get_shortcode_regex();
    return preg_replace_callback( "/$pattern/s", 'brendan_do_email_shortcode_tag', $content );
}

function brendan_email_shortcode($attrs, $content = '') {
    extract(shortcode_atts(array(
        'type' => null,
        'from' => null,
        'to' => null,
        'subject' => null,
        'sent' => null
    ), $attrs));

    $out = "<pre class='email";
    if ($attrs['type'] == 'reply') {
        $out .= " reply";
    }
    $out .= "'>";
   
    if ($attrs['sent'] != null) {
        $out .= "Sent: " . $attrs['sent'] . "\n";
    } 
    
    if ($attrs['from'] != null) {
        $out .= "From: " . $attrs['from'] . "\n";
    } 
    
    if ($attrs['to'] != null) {
        $out .= "To: " . $attrs['to'] . "\n";
    } 
    
    if ($attrs['subject'] != null) {
        $out .= "Subject: " . $attrs['subject'] . "\n";
    } 
   
    $out .= "\n" . $content . "</pre>";

    return $out;
}

add_filter('the_content', 'brendan_do_email_shortcode', 0);
?>

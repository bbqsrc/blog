<?php
/*
Plugin Name: Email Shortcode
Plugin URI: https://github.com/bbqsrc/blog
Description: Shortcode for marking up my emails
Version: 0.1
Author: Brendan Molloy
Author URI: http://brendan.so
License: CC0
*/
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
add_shortcode('email', 'brendan_email_shortcode');
?>

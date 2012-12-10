<?php
/*
Plugin Name: Download Shortcode
Plugin URI: https://github.com/bbqsrc/blog
Description: Shortcode for marking up my downloads 
Version: 0.1
Author: Brendan Molloy
Author URI: http://brendan.so
License: CC0
*/
function brendan_download_shortcode($attrs, $content = null) {
    extract(shortcode_atts(array(
        'href' => null,
        'size' => null,
        'md5' => null,
        'sha1' => null
    ), $attrs));

    $out = "<pre class='download'>";

    if ($content != null) {
        if ($attrs['href'] != null) {
            $out .= "<a href='" + $attrs['href'] + "'>" . $content . "</a>";
        } else {
            $out .= $content;
        }
    }

    if ($attrs['size'] != null) {
        $size = intval($attrs['size']);

        if ($size < 1000) {
           $out .= " [" . $size . " B]";
        }
        
        else if ($size < 1000000) {
           $out .= " [" . number_format($size / 1000, 2) . " KB]";
        }
        
        else if ($size < 1000000000) {
           $out .= " [" . number_format($size / 1000000, 2) . " MB]";
        }
        
        else {
           $out .= " [" . number_format($size / 1000000000, 2) . " GB]";
        }
    }

    $out .= "\n";

    if ($attrs['md5'] != null) {
        $out .= "md5sum: " . $attrs['md5'] . "\n";
    }
    if ($attrs['sha1'] != null) {
        $out .= "sha1sum: " . $attrs['sha1'] . "\n";
    }

    $out .= "</pre>"
    return $out;
}
add_shortcode('download', 'brendan_download_shortcode');
?>

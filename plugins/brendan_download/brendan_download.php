<?php
/*
Plugin Name: Brendan's Download Shortcode
Plugin URI: https://github.com/bbqsrc/blog
Description: Shortcode for marking up my downloads 
Version: 0.2.2
Author: Brendan Molloy
Author URI: http://brendan.so
License: CC0
*/

function brendan_download_create_table() {
    global $wpdb;
    
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}brendan_downloads (
      id int unsigned not null unique auto_increment,
      file mediumtext NOT null,
      size int UNSIGNED NOT null,
      md5 varchar(32) NOT null,
      sha1 varchar(40) NOT null,
      PRIMARY KEY (id)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook( WP_PLUGIN_DIR . '/brendan_download/brendan_download.php', 'brendan_download_create_table' );


function brendan_download_query($filename) {
  global $wpdb;
  $table_name = $wpdb->prefix . "brendan_downloads";

  $row = $wpdb->get_row($wpdb->prepare("SELECT * from $table_name WHERE file = '%s'", $filename), ARRAY_A);

  if ($row == null) {
    $options = get_option('brendan_download_options');
    $path = $options['path'];
    if ($path == null) {
      return;
    }

    if (!file_exists($path . $filename)) {
      return;
    }

    // filesize() can only handle 2GB files...
    $f = fopen($path . $filename, 'r');
    fseek($f, 0, SEEK_END);
    $size = ftell($f);
    fclose($f);

    $md5sum = md5_file($path . $filename);
    $sha1sum = sha1_file($path . $filename);

    $wpdb->insert($table_name, array(
      'file' => $filename,
      'size' => $size,
      'md5' => $md5sum,
      'sha1' => $sha1sum
    ), array('%s', '%d', '%s', '%s'));

    return $wpdb->get_row($wpdb->prepare("SELECT * from $table_name WHERE file = '%s'", $filename), ARRAY_A);
  }

  return $row;
}

function brendan_download_human_filesize($bytes, $decimals = 2) {
  $sz = 'BKMGTP';
  $factor = floor((strlen($bytes) - 1) / 3);
  return sprintf("%.{$decimals}f ", $bytes / pow(1024, $factor)) . @$sz[$factor] . "B";
}

function brendan_download_shortcode($attrs, $content = null) {
    $out = "<pre class='download'>";

    if ($content == null) {
      return $out . "</pre>";
    }
    
    $options = get_option('brendan_download_options');
    $url = $options['url'];
    $fullurl = $url . $content;

    $filedata = brendan_download_query($content);
    if ($filedata == null) {
      // Dashes get converted and break things.
      $content = str_replace('–', '-', $content);
      $filedata = brendan_download_query($content);
    }

    if ($filedata == null) {
      return $out . "File '$content' not found.</pre>";
    }

    $out .= "<a href='$fullurl'>" . basename($content) . "</a>";
    $out .= " [" . brendan_download_human_filesize($filedata['size']) . "]\n";
    $out .= "md5sum: " . $filedata['md5'] . "\n";
    $out .= "sha1sum: " . $filedata['sha1'] . "\n";
    $out .= "</pre>";
    return $out;
}
add_shortcode('download', 'brendan_download_shortcode');

function brendan_download_opts_page() {
?>
<div class="wrap">
<h2>Brendan's Download Shortcode Options</h2>

<form method="post" action="options.php">
    <?php settings_fields('brendan_download_options'); ?>
    <?php do_settings_sections('brendan_download_options_page'); ?>
    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>">
</form>
</div><?php
}

function brendan_download_options_table() {
  global $wpdb;

  $results = $wpdb->get_results("SELECT * from {$wpdb->prefix}brendan_downloads");

  $out = "<table><tr>";
  foreach (array('ID', 'File', 'Size', 'MD5', 'SHA1', 'Actions') as $h) {
    $out .= "<th>{$h}</th>";
  }
  $out .= "</tr>";

  foreach ($results as $row) {
    $out .= "<tr>";
    $out .= "<td>" . $row->id . "</td>";
    $out .= "<td>" . $row->file . "</td>";
    $out .= "<td>" . $row->size . "</td>";
    $out .= "<td>" . $row->md5 . "</td>";
    $out .= "<td>" . $row->sha1 . "</td>";
    $out .= "<td><a href='#'>&times;</a></td>";
    $out .= "</tr>";
  }
  return $out . "</table>";
}

function brendan_download_options_path() {
  $options = get_option('brendan_download_options');
  echo "<input id='brendan_brendan_options_path' name='brendan_download_options[path]' value='{$options['path']}'>";
}

function brendan_download_options_url() {
  $options = get_option('brendan_download_options');
  echo "<input id='brendan_brendan_options_url' name='brendan_download_options[url]' value='{$options['url']}'>";
  echo brendan_download_options_table();
}

function brendan_download_options_section_text() {
  echo "Set the path and URL for file access.";
}

function brendan_download_admin_init() {
  register_setting( 'brendan_download_options', 'brendan_download_options' );
  add_settings_section( 'brendan_download_options_main', 'Main Settings', 'brendan_download_options_section_text', 'brendan_download_options_page' );
  add_settings_field( 'path', 'Path to files on operating system', 'brendan_download_options_path', 'brendan_download_options_page', 'brendan_download_options_main' );
  add_settings_field( 'url', 'URL prefix (eg "/my.tld/files")', 'brendan_download_options_url', 'brendan_download_options_page', 'brendan_download_options_main' );
  
}
add_action( 'admin_init', 'brendan_download_admin_init' );

function brendan_download_menu_init() {
  add_options_page( "Brendan's Download Shortcode", 'Download Shortcode', 'edit_files', 'brendan-download-shortcode', 'brendan_download_opts_page' );
}
add_action( 'admin_menu', "brendan_download_menu_init" );

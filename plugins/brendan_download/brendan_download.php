<?php
/*
Plugin Name: Brendan's Download Shortcode
Plugin URI: https://github.com/bbqsrc/blog
Description: Shortcode for marking up my downloads 
Version: 0.2
Author: Brendan Molloy
Author URI: http://brendan.so
License: CC0
*/

function brendan_download_query($filename) {
  global $wpdb;
  $table_name = $wpdb->prefix . "brendan_downloads";

  $row = $wpdb->get_row($wpdb->prepare("SELECT * from $table_name WHERE name = '%s'", $filename));

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

    return $wpdb->get_row($wpdb->prepare("SELECT * from $table_name WHERE name = '%s'", $filename));
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
      return $out . "File '$content' not found.</pre>";
    }

    $out .= "<a href='$fullurl'>$content</a>";
    $out .= "[" . brendan_download_human_filesize($filedata['size']) . "]\n";
    $out .= "md5sum: " . $filedata['md5'] . "\n";
    $out .= "sha1sum: " . $filedata['sha1'] . "\n";
    $out .= "</pre>";
    return $out;
}
add_shortcode('download', 'brendan_download_shortcode');

function brendan_download_create_table() {
    global $wpdb;

    $sql = "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "brendan_downloads (
      file text NOT null UNIQUE,
      size int UNSIGNED NOT null,
      md5 varchar NOT null,
      sha1 varchar NOT null,
      PRIMARY KEY (file)
    );";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook( __FILE__, 'brendan_download_create_table' );


function brendan_download_opts_page() {
?>
<form action="options.php" method="post">
  <?php settings_fields('brendan_download_options'); do_settings_sections('brendan_download'); ?>
  <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form>
<?php
}

function brendan_download_options_path() {
  $options = get_option('brendan_download_options');
  echo "<input id='brendan_brendan_options_path' name='brendan_download_options[path]' value='{$options['path']}'>";
}

function brendan_download_options_url() {
  $options = get_option('brendan_download_options');
  echo "<input id='brendan_brendan_options_url' name='brendan_download_options[url]' value='{$options['url']}'>";
}

function brendan_download_admin_init() {
  register_setting( 'brendan_download_options', 'brendan_download_options' );
  add_settings_section( 'brendan_download_options_main', 'Main Settings', 'brendan_download_options_section_text', 'brendan_download_options_page' );
  add_settings_field( 'path', 'Path to files on operating system', 'brendan_download_options_path', 'brendan_download_options_page', 'brendan_download_options_main' );
  add_settings_field( 'url', 'URL prefix (eg "/my.tld/files")', 'brendan_download_options_url', 'brendan_download_options_page', 'brendan_download_options_main' );
	add_options_page( "Brendan's Download Shortcode", 'Download Shortcode', 'administrator', 'brendan-download-shortcode', 'brendan_download_opts_page' );
  
}
add_action( 'admin_init', 'brendan_download_admin_init' );

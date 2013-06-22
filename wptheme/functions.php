<?php
function brendan_version() {
  return "0.16";
}


function brendan_setup() {
  add_theme_support( 'automatic-feed-links' );
}
add_action('after_setup_theme', 'brendan_setup');


function brendan_widgets_init() {
  register_sidebar(array(
    'name' => 'Main Sidebar',
    'id' => 'sidebar',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => "</div>",
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'
  ));

  register_sidebar(array(
    'name' => 'Top Menu',
    'id' => 'top-menu',
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '<h2 class="widget-title">',
    'after_title' => '</h2>'
  ));
}
add_action( 'widgets_init', 'brendan_widgets_init' );


function brendan_scripts_styles() {
	global $wp_styles;
	global $wp_scripts;

	$protocol = is_ssl() ? 'https' : 'http';

	/* XXX: doesn't work in WP yet...
	wp_enqueue_script('brendan-html5shiv', get_template_directory_uri() . '/html5shiv.js');
	$wp_scripts->add_data('brendan-html5shiv', 'conditional', 'lt IE 9');
	*/

	wp_enqueue_style('brendan-shit-browsers', get_template_directory_uri() . '/shit-browsers.css', array(), brendan_version());
	$wp_styles->add_data('brendan-shit-browsers', 'conditional', 'lt IE 8');

	wp_enqueue_style('brendan-style', get_stylesheet_uri(), array(), brendan_version());
}
add_action( 'wp_enqueue_scripts', 'brendan_scripts_styles' );


function brendan_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'brendan' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'brendan' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-meta">
				<div class="comment-author vcard">
					<?php
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'brendan' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								sprintf( __( '%1$s at %2$s', 'brendan' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'brendan' ), '<span class="edit-link">', '</span>' ); ?>
				</div>

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'brendan' ); ?></em>
					<br />
				<?php endif; ?>

			</div>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'brendan' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</article>

	<?php
			break;
	endswitch;
}
add_action( 'admin_menu', 'brendan_theme_menu' );


function brendan_add_subtitle_metabox() {
    $screens = array( 'post', 'page' );
    foreach ($screens as $screen) {
        add_meta_box(
            'brendan_subtitle',
            'Subtitle',
            'brendan_print_subtitle_metabox',
            $screen,
            'normal',
            'high'
        );
    }
}
add_action("add_meta_boxes", "brendan_add_subtitle_metabox");

function brendan_print_subtitle_metabox( $post ) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'brendan_subtitle_nonce' );

  // The actual fields for data entry
  // Use get_post_meta to retrieve an existing value from the database and use the value for the form
  $value = get_post_meta( $post->ID, '_brendan_subtitle', true );
  echo '<input type="text" style="padding: 3px 8px; font-size: 1.7em; line-height: 100%; width: 100%; outline: 0;" name="brendan_subtitle" value="'.esc_attr($value).'">';
}


function brendan_save_subtitle( $post_id ) {
  if ( 'page' == $_POST['post_type'] ) {
    if ( ! current_user_can( 'edit_page', $post_id ) )
        return;
  } else {
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return;
  }

  if ( ! isset( $_POST['brendan_subtitle_nonce'] ) || ! wp_verify_nonce( $_POST['brendan_subtitle_nonce'], plugin_basename( __FILE__ ) ) )
      return;

  $post_ID = $_POST['post_ID'];
  $mydata = sanitize_text_field( $_POST['brendan_subtitle'] );

  update_post_meta($post_ID, '_brendan_subtitle', $mydata);
  // or a custom table (see Further Reading section below)

}
add_action("save_post", "brendan_save_subtitle");

function brendan_theme_menu() {
  add_theme_page( "Brendan's Theme Bits", "Theme Bits", "administrator", "brendans-theme-bits", 'brendan_theme_options' );
  add_action( "admin_init", "brendan_register_settings" );
}


function brendan_register_settings() {
  register_setting( 'brendan_options', 'brendan_options' );
  add_settings_section( 'brendan_options_main', 'Main Settings', 'brendan_options_section_text', 'brendan_options_page' );
  add_settings_field( 'head', 'Content for &lt;head&gt;', 'brendan_options_head_string', 'brendan_options_page', 'brendan_options_main' );
  add_settings_field( 'header', 'Content after blog header', 'brendan_options_header_string', 'brendan_options_page', 'brendan_options_main' );
  add_settings_field( 'before_footer', 'Content before &lt;/body&gt;', 'brendan_options_before_footer_string', 'brendan_options_page', 'brendan_options_main' );
  add_settings_field( 'footer', 'Content for &lt;footer&gt;', 'brendan_options_footer_string', 'brendan_options_page', 'brendan_options_main' );
}


function brendan_options_head_string() {
  $options = get_option('brendan_options');
  echo "<textarea style='width: 100%; height: 200px' id='brendan_options_main_head' name='brendan_options[head]'>{$options['head']}</textarea>";
}


function brendan_options_header_string() {
  $options = get_option('brendan_options');
  echo "<textarea style='width: 100%; height: 200px' id='brendan_options_main_header' name='brendan_options[header]'>{$options['header']}</textarea>";
}


function brendan_options_before_footer_string() {
  $options = get_option('brendan_options');
  echo "<textarea style='width: 100%; height: 200px' id='brendan_options_main_before_footer' name='brendan_options[before_footer]'>{$options['before_footer']}</textarea>";
}


function brendan_options_footer_string() {
  $options = get_option('brendan_options');
  echo "<textarea style='width: 100%; height: 200px' id='brendan_options_main_footer' name='brendan_options[footer]'>{$options['footer']}</textarea>";
}


function brendan_theme_options() {
?>
<div class="wrap">
<h2>Brendan's Theme Bits</h2>

<form method="post" action="options.php">
    <?php settings_fields('brendan_options'); ?>
    <?php do_settings_sections('brendan_options_page'); ?>
    <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>">
</form>
</div><?php
}


function brendan_post_queries( $query ) {
  if (!is_admin() && $query->is_main_query()){
    if(is_home() || is_category()){
      $query->set('posts_per_page', 5);
    }
  }
}
add_action('pre_get_posts', 'brendan_post_queries');

<div class="post" id="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'brendan' ); ?></p>
	</div>
	<?php
			return;
		endif;
	?>

	<?php if ( have_comments() ) : ?>
    <header>
      <h2 id="comments-title">
			  <?php
			  	printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'brendan' ),
            number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
		    ?>
      </h2>
    </header>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-above">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'twentyeleven' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'brendan' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'brendan' ) ); ?></div>
		</nav>
		<?php endif;  ?>

		<ol class="commentlist">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use twentyeleven_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define twentyeleven_comment() and that will be used instead.
				 * See twentyeleven_comment() in twentyeleven/functions.php for more.
				 */
				wp_list_comments(array('callback' => 'brendan_comment'));
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h1 class="assistive-text"><?php _e( 'Comment navigation', 'twentyeleven' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyeleven' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyeleven' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments"><?php _e( 'Comments are closed.', 'twentyeleven' ); ?></p>
  <?php else: ?>
    <h2 style="margin: 0" id="comments-title">No comments</h2>

  <?php endif; ?>

</div>
<div class='post'>
  <?php comment_form(); ?>
</div>

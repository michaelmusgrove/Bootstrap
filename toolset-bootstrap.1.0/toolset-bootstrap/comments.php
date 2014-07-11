<?php
/**
 * The template for displaying Comments.
 *
 */
if ( post_password_required() )
	return;
?>

<?php if ( wpbootstrap_get_setting('general_settings', 'display_comments' ) ): ?>
	<section id="comments">
		<?php if (comments_open()): ?>
			<?php if ( have_comments() ) : ?>
				<h2 id="comments-title">
					<?php
						printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'wpbootstrap' ),
							number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
					?>
				</h2>

				<ol class="commentlist unstyled">
					<?php wp_list_comments(array('walker'=>new Wpbootstrap_Comments())); ?>
				</ol> <!-- .commentlist -->

				<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
				<ul class="pager">
					<li class="previous"><?php previous_comments_link( __( '&larr; Older Comments', 'wpbootstrap' ) ); ?></li>
					<li class="next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'wpbootstrap' ) ); ?></li>
				</ul>
				<?php endif; // check for comment navigation ?>

				<?php
				// If there are no comments and comments are closed
				if ( ! comments_open() && get_comments_number() ) : ?>
				<p class="nocomments"><?php _e( 'Comments are closed.' , 'wpbootstrap' ); ?></p>
				<?php endif; ?>

			<?php endif; // have_comments() ?>

			<?php comment_form(); ?>

		<?php else: ?>
			<?php if ( wpbootstrap_get_setting('general_settings', 'display_comments_closed_info' ) ): ?>
				<p class="alert alert-info">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?php _e('Comments are closed','wpbootstrap'); ?>
				</p>
			<?php endif; ?>
		<?php endif;?>

	</section><!-- #comments -->
<?php endif;
<?php
/**
 * The template for displaying all single posts
 *
 */
get_header(); ?>

	<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'content', get_post_format() );
		endwhile;
	?>

	<ul class="nav-single pager" role="navigation">
		<li class="nav-previous previous">
			<?php previous_post_link( '%link', '' . _x( '&larr; ', 'Previous post link', 'wpbootstrap' ) . '%title' ); ?>
		</li>
		<li class="nav-next next">
			<?php next_post_link( '%link', '%title' . _x( ' &rarr;', 'Next post link', 'wpbootstrap' ) ); ?>
		</li>
	</ul>

<?php get_footer();
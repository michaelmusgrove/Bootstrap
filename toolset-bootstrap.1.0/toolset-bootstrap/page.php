<?php
/**
 * The template for displaying all pages.
 *
 */
get_header(); ?>

	<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'page' );
		endwhile;
	?>

<?php get_footer();
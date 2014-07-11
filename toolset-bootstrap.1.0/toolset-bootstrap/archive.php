<?php
/**
 * The template for displaying Archives.
 *
 */
get_header(); ?>

<?php if ( have_posts() ) : ?>

	<?php if ( wpbootstrap_get_setting('titles_settings','display_archives_headers') ): ?>
		<h1 class="archive-title">
			<?php
				if ( is_day() ) :
					printf( __( 'Daily Archives: %s', 'wpbootstrap' ), get_the_date() );
				elseif ( is_month() ) :
					printf( __( 'Monthly Archives: %s', 'wpbootstrap' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'wpbootstrap' ) ) );
				elseif ( is_year() ) :
					printf( __( 'Yearly Archives: %s', 'wpbootstrap' ), get_the_date( _x( 'Y', 'yearly archives date format', 'wpbootstrap' ) ) );
				else :
					_e( 'Archives', 'wpbootstrap' );
				endif;
			?>
		</h1>
	<?php endif; ?>

	<?php
		while ( have_posts() ) : the_post();
			get_template_part( 'content', get_post_format() );
		endwhile;
		wpbootstrap_content_nav();
	?>

<?php endif; ?>

<?php get_footer();
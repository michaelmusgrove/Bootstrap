<?php
/**
 * The template for the right sidebar
 *
 */?>
<?php if (is_active_sidebar( 'footer-widgets' )) : ?>
	<section id="footer-widgets">
		<div class="row">
			<?php dynamic_sidebar( 'Footer widgets area' ); ?>
		</div>
	</section><!-- #header-widgets -->
<?php endif;
<?php
/**
 * The template for displaying the footer.
 *
 */
?>
			</section><!-- #content -->
			<?php
				if( wpbootstrap_get_setting('general_settings','display_sidebar') && (!is_page_template('page-fullwidth.php')) ) {
					get_sidebar('sidebar');
				}
			?>
			<?php do_action( 'wpbootstrap_after_content' ); ?>
		</div><!-- #main -->

		<?php
			if( wpbootstrap_get_setting('general_settings','display_footer_widgets') ) {
				get_sidebar('footer-widgets');
			}
		?>

		<?php do_action( 'wpbootstrap_before_footer' ); ?>
		<?php if ( wpbootstrap_get_setting('general_settings', 'display_footer' ) ): ?>
		    <?php if (of_get_option('display_credit_footer_id')) { ?>
			<footer id="footer" class="muted">			    
				<p class="pull-left"><?php echo of_get_option('display_credit_footer_id_left'); ?></p>
				<p class="pull-right"><?php echo of_get_option('display_credit_footer_id_right'); ?></p>
			</footer>
			<?php } ?>
		<?php endif; ?>
		<?php do_action( 'wpbootstrap_after_footer' ); ?>

	</div><!-- .container -->

<?php do_action( 'wpbootstrap_before_wp_footer' ); ?>
<?php wp_footer(); ?>
<?php do_action( 'wpbootstrap_after_wp_footer' ); ?>

</body>
</html>
<?php
/**
 * The template for displaying the footer.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

	</div><!-- #content -->
</div><!-- #page -->

<?php
/**
 * kalki_before_footer hook.
 *
 */
do_action( 'kalki_before_footer' );
?>

<div <?php kalki_footer_class(); ?>>
	<?php
	/**
	 * kalki_before_footer_content hook.
	 *
	 */
	do_action( 'kalki_before_footer_content' );

	/**
	 * kalki_footer hook.
	 *
	 *
	 * @hooked kalki_construct_footer_widgets - 5
	 * @hooked kalki_construct_footer - 10
	 */
	do_action( 'kalki_footer' );

	/**
	 * kalki_after_footer_content hook.
	 *
	 */
	do_action( 'kalki_after_footer_content' );
	?>
</div><!-- .site-footer -->

<?php
/**
 * kalki_after_footer hook.
 *
 */
do_action( 'kalki_after_footer' );

wp_footer();
?>

</body>
</html>

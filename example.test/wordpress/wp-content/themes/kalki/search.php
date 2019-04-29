<?php
/**
 * The template for displaying Search Results pages.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php kalki_content_class(); ?>>
		<main id="main" <?php kalki_main_class(); ?>>
			<?php
			/**
			 * kalki_before_main_content hook.
			 *
			 */
			do_action( 'kalki_before_main_content' );

			if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title">
						<?php
						printf( // WPCS: XSS ok.
							/* translators: 1: Search query name */
							__( 'Search Results for: %s', 'kalki' ),
							'<span>' . get_search_query() . '</span>'
						);
						?>
					</h1>
				</header><!-- .page-header -->

				<?php while ( have_posts() ) : the_post();

					get_template_part( 'content', 'search' );

				endwhile;

				kalki_content_nav( 'nav-below' );

			else :

				get_template_part( 'no-results', 'search' );

			endif;

			/**
			 * kalki_after_main_content hook.
			 *
			 */
			do_action( 'kalki_after_main_content' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php
	/**
	 * kalki_after_primary_content_area hook.
	 *
	 */
	 do_action( 'kalki_after_primary_content_area' );

	 kalki_construct_sidebars();

get_footer();

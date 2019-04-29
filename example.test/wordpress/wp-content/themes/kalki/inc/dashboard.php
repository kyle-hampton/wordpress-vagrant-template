<?php
/**
 * Builds our admin page.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'kalki_create_menu' ) ) {
	add_action( 'admin_menu', 'kalki_create_menu' );
	/**
	 * Adds our "Kalki" dashboard menu item
	 *
	 */
	function kalki_create_menu() {
		$kalki_page = add_theme_page( 'Kalki', 'Kalki', apply_filters( 'kalki_dashboard_page_capability', 'edit_theme_options' ), 'kalki-options', 'kalki_settings_page' );
		add_action( "admin_print_styles-$kalki_page", 'kalki_options_styles' );
	}
}

if ( ! function_exists( 'kalki_options_styles' ) ) {
	/**
	 * Adds any necessary scripts to the Kalki dashboard page
	 *
	 */
	function kalki_options_styles() {
		wp_enqueue_style( 'kalki-options', get_template_directory_uri() . '/css/admin/admin-style.css', array(), KALKI_VERSION );
	}
}

if ( ! function_exists( 'kalki_settings_page' ) ) {
	/**
	 * Builds the content of our Kalki dashboard page
	 *
	 */
	function kalki_settings_page() {
		?>
		<div class="wrap">
			<div class="metabox-holder">
				<div class="kalki-masthead clearfix">
					<div class="kalki-container">
						<div class="kalki-title">
							<a href="<?php echo esc_url(KALKI_THEME_URL); ?>" target="_blank"><?php esc_html_e( 'Kalki', 'kalki' ); ?></a> <span class="kalki-version"><?php echo esc_html( KALKI_VERSION ); ?></span>
						</div>
						<div class="kalki-masthead-links">
							<?php if ( ! defined( 'KALKI_PREMIUM_VERSION' ) ) : ?>
								<a class="kalki-masthead-links-bold" href="<?php echo esc_url(KALKI_THEME_URL); ?>" target="_blank"><?php esc_html_e( 'Premium', 'kalki' );?></a>
							<?php endif; ?>
							<a href="<?php echo esc_url(KALKI_WPKOI_AUTHOR_URL); ?>" target="_blank"><?php esc_html_e( 'WPKoi', 'kalki' ); ?></a>
                            <a href="<?php echo esc_url(KALKI_DOCUMENTATION); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'kalki' ); ?></a>
						</div>
					</div>
				</div>

				<?php
				/**
				 * kalki_dashboard_after_header hook.
				 *
				 */
				 do_action( 'kalki_dashboard_after_header' );
				 ?>

				<div class="kalki-container">
					<div class="postbox-container clearfix" style="float: none;">
						<div class="grid-container grid-parent">

							<?php
							/**
							 * kalki_dashboard_inside_container hook.
							 *
							 */
							 do_action( 'kalki_dashboard_inside_container' );
							 ?>

							<div class="form-metabox grid-70" style="padding-left: 0;">
								<h2 style="height:0;margin:0;"><!-- admin notices below this element --></h2>
								<form method="post" action="options.php">
									<?php settings_fields( 'kalki-settings-group' ); ?>
									<?php do_settings_sections( 'kalki-settings-group' ); ?>
									<div class="customize-button hide-on-desktop">
										<?php
										printf( '<a id="kalki_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
											esc_url( admin_url( 'customize.php' ) ),
											esc_html__( 'Customize', 'kalki' )
										);
										?>
									</div>

									<?php
									/**
									 * kalki_inside_options_form hook.
									 *
									 */
									 do_action( 'kalki_inside_options_form' );
									 ?>
								</form>

								<?php
								$modules = array(
									'Backgrounds' => array(
											'url' => KALKI_THEME_URL,
									),
									'Blog' => array(
											'url' => KALKI_THEME_URL,
									),
									'Colors' => array(
											'url' => KALKI_THEME_URL,
									),
									'Copyright' => array(
											'url' => KALKI_THEME_URL,
									),
									'Disable Elements' => array(
											'url' => KALKI_THEME_URL,
									),
									'Demo Import' => array(
											'url' => KALKI_THEME_URL,
									),
									'Hooks' => array(
											'url' => KALKI_THEME_URL,
									),
									'Import / Export' => array(
											'url' => KALKI_THEME_URL,
									),
									'Menu Plus' => array(
											'url' => KALKI_THEME_URL,
									),
									'Page Header' => array(
											'url' => KALKI_THEME_URL,
									),
									'Secondary Nav' => array(
											'url' => KALKI_THEME_URL,
									),
									'Spacing' => array(
											'url' => KALKI_THEME_URL,
									),
									'Typography' => array(
											'url' => KALKI_THEME_URL,
									),
									'Elementor Addon' => array(
											'url' => KALKI_THEME_URL,
									)
								);

								if ( ! defined( 'KALKI_PREMIUM_VERSION' ) ) : ?>
									<div class="postbox kalki-metabox">
										<h3 class="hndle"><?php esc_html_e( 'Premium Modules', 'kalki' ); ?></h3>
										<div class="inside" style="margin:0;padding:0;">
											<div class="premium-addons">
												<?php foreach( $modules as $module => $info ) { ?>
												<div class="add-on activated kalki-clear addon-container grid-parent">
													<div class="addon-name column-addon-name" style="">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php echo esc_html( $module ); ?></a>
													</div>
													<div class="addon-action addon-addon-action" style="text-align:right;">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php esc_html_e( 'More info', 'kalki' ); ?></a>
													</div>
												</div>
												<div class="kalki-clear"></div>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php
								endif;

								/**
								 * kalki_options_items hook.
								 *
								 */
								do_action( 'kalki_options_items' );
								?>
							</div>

							<div class="kalki-right-sidebar grid-30" style="padding-right: 0;">
								<div class="customize-button hide-on-mobile">
									<?php
									printf( '<a id="kalki_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
										esc_url( admin_url( 'customize.php' ) ),
										esc_html__( 'Customize', 'kalki' )
									);
									?>
								</div>

								<?php
								/**
								 * kalki_admin_right_panel hook.
								 *
								 */
								 do_action( 'kalki_admin_right_panel' );

								  ?>
                                
                                <div class="wpkoi-doc">
                                	<h3><?php esc_html_e( 'Kalki documentation', 'kalki' ); ?></h3>
                                	<p><?php esc_html_e( 'If You`ve stuck, the documentation may help on WPKoi.com', 'kalki' ); ?></p>
                                    <a href="<?php echo esc_url(KALKI_DOCUMENTATION); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Kalki documentation', 'kalki' ); ?></a>
                                </div>
                                
                                <div class="wpkoi-social">
                                	<h3><?php esc_html_e( 'WPKoi on Facebook', 'kalki' ); ?></h3>
                                	<p><?php esc_html_e( 'If You want to get useful info about WordPress and the theme, follow WPKoi on Facebook.', 'kalki' ); ?></p>
                                    <a href="<?php echo esc_url(KALKI_WPKOI_SOCIAL_URL); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Go to Facebook', 'kalki' ); ?></a>
                                </div>
                                
                                <div class="wpkoi-review">
                                	<h3><?php esc_html_e( 'Help with You review', 'kalki' ); ?></h3>
                                	<p><?php esc_html_e( 'If You like Kalki theme, show it to the world with Your review. Your feedback helps a lot.', 'kalki' ); ?></p>
                                    <a href="<?php echo esc_url(KALKI_WORDPRESS_REVIEW); ?>" class="wpkoi-admin-button" target="_blank"><?php esc_html_e( 'Add my review', 'kalki' ); ?></a>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'kalki_admin_errors' ) ) {
	add_action( 'admin_notices', 'kalki_admin_errors' );
	/**
	 * Add our admin notices
	 *
	 */
	function kalki_admin_errors() {
		$screen = get_current_screen();

		if ( 'appearance_page_kalki-options' !== $screen->base ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
			 add_settings_error( 'kalki-notices', 'true', esc_html__( 'Settings saved.', 'kalki' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'imported' == $_GET['status'] ) {
			 add_settings_error( 'kalki-notices', 'imported', esc_html__( 'Import successful.', 'kalki' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'reset' == $_GET['status'] ) {
			 add_settings_error( 'kalki-notices', 'reset', esc_html__( 'Settings removed.', 'kalki' ), 'updated' );
		}

		settings_errors( 'kalki-notices' );
	}
}

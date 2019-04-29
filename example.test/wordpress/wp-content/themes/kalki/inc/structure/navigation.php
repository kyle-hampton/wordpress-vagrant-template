<?php
/**
 * Navigation elements.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'kalki_navigation_position' ) ) {
	/**
	 * Build the navigation.
	 *
	 */
	function kalki_navigation_position() {
		?>
		<nav itemtype="https://schema.org/SiteNavigationElement" itemscope="itemscope" id="site-navigation" <?php kalki_navigation_class(); ?>>
			<div <?php kalki_inside_navigation_class(); ?>>
				<?php
				/**
				 * kalki_inside_navigation hook.
				 *
				 *
				 * @hooked kalki_navigation_search - 10
				 * @hooked kalki_mobile_menu_search_icon - 10
				 */
				do_action( 'kalki_inside_navigation' );
				?>
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php do_action( 'kalki_inside_mobile_menu' ); ?>
					<span class="mobile-menu"><?php echo apply_filters( 'kalki_mobile_menu_label', __( 'Menu', 'kalki' ) ); // WPCS: XSS ok. ?></span>
				</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container' => 'div',
						'container_class' => 'main-nav',
						'container_id' => 'primary-menu',
						'menu_class' => '',
						'fallback_cb' => 'kalki_menu_fallback',
						'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', kalki_get_menu_class() ) . '">%3$s</ul>'
					)
				);
				?>
			</div><!-- .inside-navigation -->
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'kalki_menu_fallback' ) ) {
	/**
	 * Menu fallback.
	 *
	 *
	 * @param  array $args
	 * @return string
	 */
	function kalki_menu_fallback( $args ) {
		$kalki_settings = wp_parse_args(
			get_option( 'kalki_settings', array() ),
			kalki_get_defaults()
		);
		?>
		<div id="primary-menu" class="main-nav">
			<ul <?php kalki_menu_class(); ?>>
				<?php
				$args = array(
					'sort_column' => 'menu_order',
					'title_li' => '',
					'walker' => new Kalki_Page_Walker()
				);

				wp_list_pages( $args );

				if ( 'enable' == $kalki_settings['nav_search'] ) {
					echo '<li class="search-item" title="' . esc_attr_x( 'Search', 'submit button', 'kalki' ) . '"><a href="#"><span class="screen-reader-text">' . esc_html_x( 'Search', 'submit button', 'kalki' ) . '</span></a></li>';
				}
				?>
			</ul>
		</div><!-- .main-nav -->
		<?php
	}
}

/**
 * Generate the navigation based on settings
 *
 * It would be better to have all of these inside one action, but these
 * are kept this way to maintain backward compatibility for people
 * un-hooking and moving the navigation/changing the priority.
 *
 */

if ( ! function_exists( 'kalki_add_navigation_after_header' ) ) {
	add_action( 'kalki_after_header', 'kalki_add_navigation_after_header', 5 );
	function kalki_add_navigation_after_header() {
		if ( 'nav-below-header' == kalki_get_navigation_location() ) {
			kalki_navigation_position();
		}
	}
}

if ( ! function_exists( 'kalki_add_navigation_before_header' ) ) {
	add_action( 'kalki_before_header', 'kalki_add_navigation_before_header', 5 );
	function kalki_add_navigation_before_header() {
		if ( 'nav-above-header' == kalki_get_navigation_location() ) {
			kalki_navigation_position();
		}
	}
}

if ( ! function_exists( 'kalki_add_navigation_float_right' ) ) {
	add_action( 'kalki_after_header_content', 'kalki_add_navigation_float_right', 5 );
	function kalki_add_navigation_float_right() {
		if ( 'nav-float-right' == kalki_get_navigation_location() || 'nav-float-left' == kalki_get_navigation_location() ) {
			kalki_navigation_position();
		}
	}
}

if ( ! function_exists( 'kalki_add_navigation_before_right_sidebar' ) ) {
	add_action( 'kalki_before_right_sidebar_content', 'kalki_add_navigation_before_right_sidebar', 5 );
	function kalki_add_navigation_before_right_sidebar() {
		if ( 'nav-right-sidebar' == kalki_get_navigation_location() ) {
			echo '<div class="gen-sidebar-nav">';
				kalki_navigation_position();
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'kalki_add_navigation_before_left_sidebar' ) ) {
	add_action( 'kalki_before_left_sidebar_content', 'kalki_add_navigation_before_left_sidebar', 5 );
	function kalki_add_navigation_before_left_sidebar() {
		if ( 'nav-left-sidebar' == kalki_get_navigation_location() ) {
			echo '<div class="gen-sidebar-nav">';
				kalki_navigation_position();
			echo '</div>';
		}
	}
}

if ( ! class_exists( 'Kalki_Page_Walker' ) && class_exists( 'Walker_Page' ) ) {
	/**
	 * Add current-menu-item to the current item if no theme location is set
	 * This means we don't have to duplicate CSS properties for current_page_item and current-menu-item
	 *
	 */
	class Kalki_Page_Walker extends Walker_Page {
		function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
			$css_class = array( 'page_item', 'page-item-' . $page->ID );
			$button = '';

			if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
				$css_class[] = 'menu-item-has-children';
				$button = '<span role="button" class="dropdown-menu-toggle" aria-expanded="false"></span>';
			}

			if ( ! empty( $current_page ) ) {
				$_current_page = get_post( $current_page );
				if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
					$css_class[] = 'current-menu-ancestor';
				}
				if ( $page->ID == $current_page ) {
					$css_class[] = 'current-menu-item';
				} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
					$css_class[] = 'current-menu-parent';
				}
			} elseif ( $page->ID == get_option('page_for_posts') ) {
				$css_class[] = 'current-menu-parent';
			}

			$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

			$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
			$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

			$output .= sprintf(
				'<li class="%s"><a href="%s">%s%s%s%s</a>',
				$css_classes,
				get_permalink( $page->ID ),
				$args['link_before'],
				apply_filters( 'the_title', $page->post_title, $page->ID ),
				$args['link_after'],
				$button
			);
		}
	}
}

if ( ! function_exists( 'kalki_dropdown_icon_to_menu_link' ) ) {
	add_filter( 'nav_menu_item_title', 'kalki_dropdown_icon_to_menu_link', 10, 4 );
	/**
	 * Add dropdown icon if menu item has children.
	 *
	 *
	 * @param string $title The menu item title.
	 * @param WP_Post $item All of our menu item data.
	 * @param stdClass $args All of our menu item args.
	 * @param int $dept Depth of menu item.
	 * @return string The menu item.
	 */
	function kalki_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {

		$role = 'presentation';
		$tabindex = '';

		if ( 'click-arrow' === kalki_get_setting( 'nav_dropdown_type' ) ) {
			$role = 'button';
			$tabindex = ' tabindex="0"';
		}

		// Loop through our menu items and add our dropdown icons.
		if ( 'main-nav' === $args->container_class ) {
			foreach ( $item->classes as $value ) {
				if ( 'menu-item-has-children' === $value  ) {
					$title = $title . '<span role="' . $role . '" class="dropdown-menu-toggle"' . $tabindex .'></span>';
				}
			}
		}

		// Return our title.
		return $title;
	}
}

if ( ! function_exists( 'kalki_navigation_search' ) ) {
	add_action( 'kalki_inside_navigation', 'kalki_navigation_search' );
	/**
	 * Add the search bar to the navigation.
	 *
	 */
	function kalki_navigation_search() {
		$kalki_settings = wp_parse_args(
			get_option( 'kalki_settings', array() ),
			kalki_get_defaults()
		);

		if ( 'enable' !== $kalki_settings['nav_search'] ) {
			return;
		}

		echo get_search_form();
	}
}

if ( ! function_exists( 'kalki_menu_search_icon' ) ) {
	add_filter( 'wp_nav_menu_items', 'kalki_menu_search_icon', 10, 2 );
	/**
	 * Add search icon to primary menu if set
	 *
	 *
	 * @param string $nav The HTML list content for the menu items.
	 * @param stdClass $args An object containing wp_nav_menu() arguments.
	 * @return string The search icon menu item.
	 */
	function kalki_menu_search_icon( $nav, $args ) {
		$kalki_settings = wp_parse_args(
			get_option( 'kalki_settings', array() ),
			kalki_get_defaults()
		);

		// If the search icon isn't enabled, return the regular nav.
		if ( 'enable' !== $kalki_settings['nav_search'] ) {
			return $nav;
		}

		// If our primary menu is set, add the search icon.
		if ( $args->theme_location == 'primary' ) {
			return $nav . '<li class="search-item" title="' . esc_attr_x( 'Search', 'submit button', 'kalki' ) . '"><a href="#"><span class="screen-reader-text">' . _x( 'Search', 'submit button', 'kalki' ) . '</span></a></li>';
		}

		// Our primary menu isn't set, return the regular nav.
		// In this case, the search icon is added to the kalki_menu_fallback() function in navigation.php.
	    return $nav;
	}
}

if ( ! function_exists( 'kalki_mobile_menu_search_icon' ) ) {
	add_action( 'kalki_inside_navigation', 'kalki_mobile_menu_search_icon' );
	/**
	 * Add search icon to mobile menu bar
	 *
	 */
	function kalki_mobile_menu_search_icon() {
		$kalki_settings = wp_parse_args(
			get_option( 'kalki_settings', array() ),
			kalki_get_defaults()
		);

		// If the search icon isn't enabled, return the regular nav.
		if ( 'enable' !== $kalki_settings['nav_search'] ) {
			return;
		}

		?>
		<div class="mobile-bar-items">
			<?php do_action( 'kalki_inside_mobile_menu_bar' ); ?>
			<span class="search-item" title="<?php echo esc_attr_x( 'Search', 'submit button', 'kalki' ); ?>">
				<a href="#">
					<span class="screen-reader-text"><?php echo esc_attr_x( 'Search', 'submit button', 'kalki' ); ?></span>
				</a>
			</span>
		</div><!-- .mobile-bar-items -->
		<?php
	}
}

add_action( 'wp_footer', 'kalki_clone_sidebar_navigation' );
/**
 * Clone our sidebar navigation and place it below the header.
 * This places our mobile menu in a more user-friendly location.
 *
 * We're not using wp_add_inline_script() as this needs to happens
 * before menu.js is enqueued.
 *
 */
function kalki_clone_sidebar_navigation() {
	if ( 'nav-left-sidebar' !== kalki_get_navigation_location() && 'nav-right-sidebar' !== kalki_get_navigation_location() ) {
		return;
	}
	?>
	<script>
		var target, nav, clone;
		nav = document.getElementById( 'site-navigation' );
		if ( nav ) {
			clone = nav.cloneNode( true );
			clone.className += ' sidebar-nav-mobile';
			clone.setAttribute( 'aria-label', '<?php esc_attr_e( 'Mobile Menu', 'kalki' ); ?>' );
			target = document.getElementById( 'masthead' );
			if ( target ) {
				target.insertAdjacentHTML( 'afterend', clone.outerHTML );
			} else {
				document.body.insertAdjacentHTML( 'afterbegin', clone.outerHTML )
			}
		}
	</script>
	<?php
}

function kalki_nav_menu_link_attributes( $atts, $item, $args ) {

	$kalki_settings = wp_parse_args(
		get_option( 'kalki_settings', array() ),
		kalki_get_defaults()
	);
	$naveffect = $kalki_settings[ 'nav_effect' ];
	
	if ( $naveffect == 'styled' ) {
		$atts['data-hover'] = $item->title;
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'kalki_nav_menu_link_attributes', 10, 3 );
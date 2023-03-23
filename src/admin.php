<?php
/**
* @package XtremCache Helper
*/

namespace XtremCache;

class Admin {

	/**
	 * Add the admin bar Purge Cache menu.
	 *
	 * @since 0.1
	 *
	 * @param (object) $wp_admin_bar
	 * @return void
	 */
	public static function admin_bar( $wp_admin_bar ) {
		if ( ! current_user_can( 'publish_posts' ) ) {
			return;
		}

		global $pagenow;
		$menu_id = 'xtremcache';

		// Parent menu entry.
		$wp_admin_bar->add_menu(
			array(
				'id' => $menu_id,
				'title' => __( 'XtremCache', 'xtremcache-helper' ),
				'href' => ''
			)
		);

		// Child entries.
		if ( ! is_admin() || 'post.php' === $pagenow || 'term.php' === $pagenow ) {
			$wp_admin_bar->add_menu(
				array(
					'parent' => $menu_id,
					'title'  => __( 'Purge this', 'xtremcache-helper' ),
					'id'     => $menu_id . 'purge-url',
					'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=purge-url' ), 'purge-url' )
				)
			);
		}
		$wp_admin_bar->add_menu(
			array(
				'parent' => $menu_id,
				'title'  => __( 'Purge front page', 'xtremcache-helper' ),
				'id'     => $menu_id . 'purge-home',
				'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=purge-home' ), 'purge-home' )
			)
		);
		$wp_admin_bar->add_menu(
			array(
				'parent' => $menu_id,
				'title'  => __( 'Purge media library', 'xtremcache-helper' ),
				'id'     => $menu_id . 'purge-media',
				'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=purge-media' ), 'purge-media' )
			)
		);
		$wp_admin_bar->add_menu(
			array(
				'parent' => $menu_id,
				'title'  => __( 'Purge theme files', 'xtremcache-helper' ),
				'id'     => $menu_id . 'purge-theme',
				'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=purge-theme' ), 'purge-theme' )
			)
		);
		$wp_admin_bar->add_menu(
			array(
				'parent' => $menu_id,
				'title'  => __( 'Purge js & css', 'xtremcache-helper' ),
				'id'     => $menu_id . 'purge-js-css',
				'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=purge-js-css' ), 'purge-js-css' )
			)
		);
		$wp_admin_bar->add_menu(
			array(
				'parent' => $menu_id,
				'title'  => __( 'Purge everything', 'xtremcache-helper' ),
				'id'     => $menu_id . 'purge-all',
				'href'   => wp_nonce_url( admin_url( 'admin-post.php?action=purge-all' ), 'purge-all' )
			)
		);
	}

	/**
	 * Catch admin notices.
	 *
	 * @since 0.5
	 *
	 * @return void
	 */
	public static function admin_notices() {
		$transient = 'xtremcache_admin_notice_' . \get_current_user_id();
		$notice = \get_transient( $transient );

		if ( $notice ) {
			echo '<div class="notice notice-warning is-dismissible"><p>' . $notice . '</p></div>';
			\delete_transient( $transient );
		}

	}

	/**
	 * Add a Settings link to the plugin action menu.
	 *
	 * @since 0.x
	 *
	 * @param array  $links
	 * @return array
	 */
	public static function action_links( $links ) {
		array_unshift( $links, '<a href="' . admin_url( 'admin.php?page=xtremcache_helper' ) . '">' . __( 'Settings' ) . '</a>' );

		return $links;
	}

	/**
	 * Add the admin menu entry and register settings.
	 *
	 * @since 0.x
	 *
	 * @return void
	 */
	public static function admin_menu() {
		add_options_page(
			__( 'XtremCache Helper', 'xtremcache-helper' ),
			__( 'XtremCache','xtremcache-helper' ),
			'manage_options',
			'xtremcache_helper',
			array( 'XtremCache\\Admin', 'settings_page' )
		);

		register_setting( 'xtremcache_settings', 'xtremcache_settings' );
	}

	/**
	 * Render the settings page.
	 *
	 * @since 0.x
	 *
	 * @return void
	 */
	public static function settings_page() {
		//add_settings_section
		//add_settings_field...
		// include view/settings-page.php
	}
}

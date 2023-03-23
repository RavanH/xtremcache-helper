<?php
/** 
* @package XtremCache Helper 
*/

namespace XtremCache;

class Actions {

	/**
	 * Admin post callback to call the purge by URL.
	 *
	 * @since 0.1
	 *  
	 * @return void
	 */
	public static function admin_purge_url() {
		$referer = \wp_get_referer();

		if ( ! $referer || ! isset( $_GET['_wpnonce'] ) || ! \wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
			die( 'Invalid request.' );
		}

		$url = \esc_url( $referer );

		// Purge.
		$response_code = Cache::purge_url( $url );

		if ( 200 === $response_code ) {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s URL */ __( 'XtremCache purge: Succesfully removed %s from the cache.', 'xtremcache-helper' ), '<code>' . $url . '</code>' ), MINUTE_IN_SECONDS );
		} else {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s status response code (other than 200) */ __( 'XtremCache purge: Unexpected response %s.', 'xtremcache-helper' ), '<code>' . $response_code . '</code>' ), MINUTE_IN_SECONDS );
		}

		\wp_redirect( $referer );
		die();
	}

	/**
	 * Admin post callback to purge front page.
	 *
	 * @since 0.6
	 *  
	 * @return void
	 */
	public static function admin_purge_home() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! \wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
			die( 'Invalid request.' );
		}

		$urls = array();
		$response_codes = array();
		$home = \home_url();
		$post_type_root = \get_post_type_archive_link( 'post' );

		// Add home URL.
		$urls[] = $home;
		// Add blog or CPT root page URL.
		if ( $post_type_root && $post_type_root !== $home ) {
			$urls[] = $post_type_root;
		}

		// Purge.
		foreach ( $urls as $url ) {
			$response_codes[] = Cache::purge_url( $url );
		}

		$response_code = $response_codes[0];
		if ( isset( $response_codes[1] ) && 200 !== $response_codes[1] ) {
			$response_code = $response_codes[1];
		}

		if ( 200 === $response_code ) {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), __( 'XtremCache: Succesfully purged front and blog pages.', 'xtremcache-helper' ), MINUTE_IN_SECONDS );
		} else {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s status response code (other than 200) */ __( 'XtremCache: Unexpected response %s.', 'xtremcache-helper' ), '<code>' . $response_code . '</code>' ), MINUTE_IN_SECONDS );
		}

		\wp_redirect( \wp_get_referer() );
		die();
	}

	/**
	 * Admin post callback to purge media files.
	 *
	 * @since 0.2
	 *  
	 * @return void
	 */
	public static function admin_purge_media() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! \wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
			die( 'Invalid request.' );
		}

		$upload_dir = \wp_upload_dir( null, false );

		$path = \wp_make_link_relative( $upload_dir['baseurl'] );

		// Purge.
		$response_code = Cache::purge_regex( $path . '/.*' );

		if ( 200 === $response_code ) {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s path */ __( 'XtremCache: Succesfully purged everything from %s.', 'xtremcache-helper' ), '<code>' . $path . '</code>' ), MINUTE_IN_SECONDS );
		} else {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s status response code (other than 200) */ __( 'XtremCache: Unexpected response %s.', 'xtremcache-helper' ), '<code>' . $response_code . '</code>' ), MINUTE_IN_SECONDS );
		}

		\wp_redirect( \wp_get_referer() );
		die();
	}

	/**
	 * Admin post callback to purge theme files.
	 *
	 * @since 0.6
	 *  
	 * @return void
	 */
	public static function admin_purge_theme() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! \wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
			die( 'Invalid request.' );
		}

		$urls = array();
		$response_codes = array();

		$template_uri =  \get_template_directory_uri();
		$stylesheet_uri = \get_stylesheet_directory_uri();

		$urls[] = \wp_make_link_relative( $template_uri );
		if ( $stylesheet_uri !== $template_uri ) {
			$urls[] = \wp_make_link_relative( $stylesheet_uri );
		}

		// Purge.
		foreach ( $urls as $url ) {
			$response_codes[] = Cache::purge_regex( $url . '/.*' );
		}

		$response_code = $response_codes[0];
		if ( isset( $response_codes[1] ) && 200 !== $response_codes[1] ) {
			$response_code = $response_codes[1];
		}

		if ( 200 === $response_code ) {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), __( 'XtremCache: Succesfully purged all active theme files.', 'xtremcache-helper' ), MINUTE_IN_SECONDS );
		} else {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s status response code (other than 200) */ __( 'XtremCache: Unexpected response %s.', 'xtremcache-helper' ), '<code>' . $response_code . '</code>' ), MINUTE_IN_SECONDS );
		}

		\wp_redirect( \wp_get_referer() );
		die();
	}

	/**
	 * Admin post callback to purge plugin files.
	 *
	 * @since 0.6
	 *  
	 * @return void
	 */
	public static function admin_purge_plugins() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! \wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
			die( 'Invalid request.' );
		}

		$path = \wp_make_link_relative( WP_PLUGIN_URL );

		// Purge.
		$response_code = Cache::purge_regex( $path . '/.*' );

		if ( 200 === $response_code ) {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s path */ __( 'XtremCache: Succesfully purged everything from %s.', 'xtremcache-helper' ), '<code>' . $path . '</code>' ), MINUTE_IN_SECONDS );
		} else {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s status response code (other than 200) */ __( 'XtremCache: Unexpected response %s.', 'xtremcache-helper' ), '<code>' . $response_code . '</code>' ), MINUTE_IN_SECONDS );
		}

		\wp_redirect( \wp_get_referer() );
		die();
	}

	/**
	 * Admin post callback to purge .js and .css files.
	 *
	 * @since 0.2
	 *  
	 * @return void
	 */
	public static function admin_purge_js_css() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! \wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
			die( 'Invalid request.' );
		}

		// Purge.
		$response_code = Cache::purge_regex( '.*\.[j|cs]s' );

		if ( 200 === $response_code ) {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), __( 'XtremCache: Succesfully removed all js and css files from the cache.', 'xtremcache-helper' ), MINUTE_IN_SECONDS );
		} else {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s status response code (other than 200) */ __( 'XtremCache: Unexpected response %s.', 'xtremcache-helper' ), '<code>' . $response_code . '</code>' ), MINUTE_IN_SECONDS );
		}

		\wp_redirect( \wp_get_referer() );
		die();
	}

	/**
	 * Admin post callback to purge everything.
	 *
	 * @since 0.1
	 *  
	 * @return void
	 */
	public static function admin_purge_all() {
		if ( ! isset( $_GET['_wpnonce'] ) || ! \wp_verify_nonce( $_GET['_wpnonce'], $_GET['action'] ) ) {
			die( 'Invalid request.' );
		}

		// Purge.
		$response_code = Cache::purge_regex( '.*' );

		if ( 200 === $response_code ) {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), __( 'XtremCache: Succesfully purged everything.', 'xtremcache-helper' ), MINUTE_IN_SECONDS );
		} else {
			// Prepare admin message.
			\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), sprintf( /* translators: %s status response code (other than 200) */ __( 'XtremCache: Unexpected response %s.', 'xtremcache-helper' ), '<code>' . $response_code . '</code>' ), MINUTE_IN_SECONDS );
		}

		\wp_redirect( \wp_get_referer() );
		die();
	}

	/**
	 * Callback to purge all on admin actions, asynchonously.
	 *
	 * @since 0.4
	 *  
	 * @return void
	 */
	public static function async_purge_all() {
		// Prepare admin message.
		\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), __( 'XtremCache purge initiated in the background.', 'xtremcache-helper' ), MINUTE_IN_SECONDS );

		// Purge.
		Cache::purge_regex( '.*', array(
			'blocking'   => false
		) ); // TODO make this a WP Cron job.

		return;
	}

	/**
	 * Callback to purge on post transition, asynchonously.
	 *
	 * @since 0.4
	 *  
	 * @return void
	 */
	public static function async_purge_post( $post_ID ) {
		// Abort if not a public post.
		if ( defined( 'DOING_AUTOSAVE' ) || 'publish' !== \get_post_status( $post_ID ) ) {
			return;
		}

		// Get the post type.
		$post_type = \get_post_type( $post_ID );

		// Prepare admin message.
		\set_transient( 'xtremcache_admin_notice_' . \get_current_user_id(), __( 'XtremCache purge initiated in the background.', 'xtremcache-helper' ), MINUTE_IN_SECONDS );

		if ( in_array( $post_type, array( 'wp_template', 'wp_template_part', 'wp_navigation' ) ) ) {
			// Purge async.
			Cache::purge_regex( '.*', array(
				'blocking'   => false
			) ); // TODO make this a WP Cron job.
		} else {
			$urls = array();
			$home = \home_url();
			$post_type_root = \get_post_type_archive_link( $post_type );

			// Add home URL.
			$urls[] = $home;
			// Add blog or CPT root page URL.
			if ( $post_type_root && $post_type_root !== $home ) {
				$urls[] = $post_type_root;
			}
			// Add post URL.
			$urls[] = \get_permalink( $post_ID );

			// Purge async.
			foreach ( $urls as $url ) {
				Cache::purge_url( $url, array(
					'blocking'   => false
				) ); // TODO make this a WP Cron job.
			}
		}

		return;
	}

}
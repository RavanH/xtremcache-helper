<?php
/**
* @package XtremCache Helper
*/

/*
Plugin Name: XtremCache Helper
Plugin URI: https://status301.net/
Description: Helper for o2switch XtremCache. Provides a cache purge admin menu and automatic purging.
Version: 0.6
Author: RavanH
Author URI: https://status301.net/
License: GPLv2 or later
Text Domain: xtremcache-helper
*/

defined( 'WPINC' ) || die;

/**
 * Load the i18n textdomain.
 *
 * @since 0.1
 *
 * @return void
 */
add_action( 'init', function() {
	load_plugin_textdomain( 'xtremcache-helper', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
} );

/**
 * Catch admin notices.
 *
 * @since 0.5
 *
 * @return void
 */
add_action( 'admin_notices', array( 'XtremCache\\Admin', 'admin_notices' ) );

/**
 * Admin menu & links.
 *
 * @since 0.1
 */
add_action( 'admin_bar_menu', array( 'XtremCache\\Admin', 'admin_bar' ), PHP_INT_MAX );
//add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  array( 'XtremCache\\Admin', 'action_links' ) );
//add_action( 'admin_menu', array( 'XtremCache\\Admin', 'admin_menu' ) );

/**
 * Amdin purge actions.
 *
 * @since 0.1
 */
add_action( 'admin_post_purge-url',    array( 'XtremCache\\Actions', 'admin_purge_url' )    );
add_action( 'admin_post_purge-home',   array( 'XtremCache\\Actions', 'admin_purge_home' )   );
add_action( 'admin_post_purge-media',  array( 'XtremCache\\Actions', 'admin_purge_media' )  );
add_action( 'admin_post_purge-theme',  array( 'XtremCache\\Actions', 'admin_purge_theme' )  );
add_action( 'admin_post_purge-js-css', array( 'XtremCache\\Actions', 'admin_purge_js_css' ) );
add_action( 'admin_post_purge-all',    array( 'XtremCache\\Actions', 'admin_purge_all' )    );

/**
 * Automatic purge actions.
 *
 * @since 0.4
 */
//add_action( 'add_link'                                            , array( 'XtremCache\\Actions', 'async_purge_all' ) );
//add_action( 'create_term'                                         , array( 'XtremCache\\Actions', 'async_purge_all' ) );
//add_action( 'customize_save'                                      , array( 'XtremCache\\Actions', 'async_purge_all' ) );
//add_action( 'delete_link'                                         , array( 'XtremCache\\Actions', 'async_purge_all' ) );
//add_action( 'delete_term'                                         , array( 'XtremCache\\Actions', 'async_purge_all' ) );
//add_action( 'deleted_user'                                        , array( 'XtremCache\\Actions', 'async_purge_all' ) );
//add_action( 'edit_link'                                           , array( 'XtremCache\\Actions', 'async_purge_all' ) );
//add_action( 'edited_terms'                                        , array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'permalink_structure_changed',                          array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'switch_theme',                                         array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'update_option_category_base',                          array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'update_option_sidebars_widgets',                       array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'update_option_tag_base',                               array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'update_option_theme_mods_'.get_option( 'stylesheet' ), array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'wp_update_nav_menu',                                   array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'upgrader_process_complete',                            array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'activated_plugin',                                     array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'deactivated_plugin',                                   array( 'XtremCache\\Actions', 'async_purge_all' ) );
add_action( 'save_post',                                            array( 'XtremCache\\Actions', 'async_purge_post' )  ); // could be handled by transition_post_status
add_action( 'wp_trash_post',                                        array( 'XtremCache\\Actions', 'async_purge_post' )  ); // could be handled by transition_post_status
add_action( 'delete_post',                                          array( 'XtremCache\\Actions', 'async_purge_post' )  ); // verify if post has "published" status?
add_action( 'wp_update_comment_count',                              array( 'XtremCache\\Actions', 'async_purge_post' )  );
//add_action( 'transition_post_status',  array( 'XtremCache\\Actions', 'async_purge_transition' ), 99, 3 ); // used for publishing posts

/**
 * Exclude pages and global cache expiration.
 *
 * @since 0.7
 */
add_filter( 'wp_headers', array( 'XtremCache\\Filters', 'cache_control' ), 999 );

/**
 * Autoload for our hooks.
 *
 * @since 0.2
 */
spl_autoload_register( function( $class ) {
	$namespace = 'XtremCache\\';

	// Bail if the class is not in our namespace.
	if ( 0 !== strpos( $class, $namespace ) ) {
		return;
	}

	// Build the filename.
	$class = str_replace( $namespace, '', $class );
	$class = strtolower( $class );
	//$class = str_replace( '_', '-', $class );
	$file = realpath( __DIR__ ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';

	// If the file exists for the class name, load it.
	if ( file_exists( $file ) ) {
		include_once( $file );
	}
} );


/**
 * Add the default settings on plugin activation. Not compatible with Network activation.
 *
 * @since 0.3
 *
 * @return void
 */
function xtremcache_helper_activation() {
	add_option( 'xtremcache_settings', array() );
}
//register_activation_hook( __FILE__, 'xtremcache_helper_activation' );

/**
 * Delete setting on uninstall. Not compatible with WP Multisite.
 *
 * @since 0.3
 *
 * @return void
 */
function xtremcache_helper_uninstall() {
	delete_option( 'xtremcache_settings' );
}
//register_uninstall_hook( __FILE__, array( 'XtremCache\\Actions', 'uninstaller' );

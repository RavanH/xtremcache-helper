<?php
/**
* @package XtremCache Helper
*/

namespace XtremCache;

class Cache {

	/**
	 * Actually purge with remote request.
	 *
	 * @since 0.1
	 *
	 * @param  string     $purge Either the URL path or a regex string to purge.
	 * @param  array      $args  Optional remote request arguments.
	 * @return int|string        Remote request response code or empty string.
	 */
	private static function purge( $url, $args = array() ) {

		$args = array_merge(
			array(
				'method'     => 'PURGE',
				'ssl_verify' => false,
				'timeout'    => 3
			),
			$args
		);

		$response = \wp_remote_request( $url, $args );
		$code = \wp_remote_retrieve_response_code( $response );

		// TODO optionally log results to DB or file for review in admin.
		if ( \defined('WP_DEBUG') && WP_DEBUG ) {
			\error_log( '**PURGED**' );
			\error_log( 'URL: ' . $url );
			\error_log( print_r( $args, true ) );
			\error_log( 'Response code: ' . $code );
		}

		return $code;
	}

	/**
	 * Purge an URL.
	 *
	 * @since 0.3
	 *
	 * @param  string     $url  The URL path to purge.
	 * @param  array      $args Optional remote request arguments.
	 * @return int|string       Remote request response code or empty string.
	 */
	public static function purge_url( $url, $args = array() ) {
		// Validate URL for use in HTTP or return 999 status code.
		if( ! wp_http_validate_url( $url ) ) {
			return 999;
		}

		$response = self::purge( $url, $args );

		return $response;
	}

	/**
	 * Purge by Regex.
	 *
	 * @since 0.3
	 *
	 * @param  string     $purge Either the URL path or a regex string to purge.
	 * @param  array      $args  Optional remote request arguments.
	 * @return int|string        Remote request response code or empty string.
	 */
	public static function purge_regex( $regex, $args = array() ) {
		// TODO verify regex?
		if ( @preg_match( $regex, '' ) === false) {
			return 999;
		}

		$args = array_merge(
			array(
				'headers' => array(
					'X-Purge-Regex' => $regex
				)
			),
			$args
		);

		$response = self::purge( \home_url(), $args );

		return $response;
	}

}

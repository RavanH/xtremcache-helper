<?php
/**
* @package XtremCache Helper
*/

namespace XtremCache;

class Filters {

	/**
	 * Add Cache-Control: no-cache header.
	 *
	 * @since 0.7
	 *
	 * @param  array $headers The HTTP Headers array.
	 * @return array
	 */
	public static function cache_control( $headers ) {
		// Set default cache TTL.
		$settings = get_option( 'xtremcache_settings' );
		$max_age = ( ! empty( $settings ) && isset( $settings['max-age'] ) ) ? (int) $settings['max-age'] : DAY_IN_SECONDS;

		// Override for single pages.
		if ( is_singular() && $post_id = get_the_ID() ) {
			// Check post meta.
			$post_max_age = get_post_meta( $post_id, 'xtremcache_max_age', true );
			if ( is_numeric( $post_max_age ) ) {
				$max_age = (int) $post_max_age;
			}
		}

		// TODO Override for archive pages.

		// Override for sitemaps.
		if ( ! empty( get_query_var( 'sitemap', '' ) ) ) {
			$max_age = 0;
		}

		$headers['Cache-Control'] = 'public, max-age=' . $max_age;

		return $headers;
	}

}

<?php
/**
* @package XtremCache Helper
*/

namespace XtremCache;

class Filters {

	/**
	 * Add or update Cache-Control header.
	 *
	 * @since 0.7
	 *
	 * @param  array $headers The HTTP Headers array.
	 * @return array
	 */
	public static function cache_control( $headers ) {
		// Set default cache TTL.
		$settings = get_option( 'xtremcache_settings' );
		$max_age = ( ! empty( $settings ) && isset( $settings['max-age'] ) ) ? (int) $settings['max-age'] : false;

		// Override for single pages.
		if ( is_singular() && $post_id = get_the_ID() ) {
			// Check post meta.
			$post_max_age = get_post_meta( $post_id, 'xtremcache_max_age', true );
			if ( is_numeric( $post_max_age ) ) {
				$max_age = (int) $post_max_age;
			}
		}

		// TODO Override for archive pages.

		// Override for sitemaps. TODO test without this (sitemaps have default nocache must reval max-age 0?)
		if ( ! empty( get_query_var( 'sitemap', '' ) ) ) {
			$max_age = 0;
		}

		if ( false !== $max_age ) {
			if ( ! empty( $headers['Cache-Control'] ) ) {
				// Update Cache-Control header.
				if ( strpos($headers['Cache-Control'], 'max-age' ) ) {
					// Replace max age.
					$headers['Cache-Control'] = preg_replace( '/max-age=\d+/', 'max-age='.$max_age, $headers['Cache-Control'] );
				} else {
					// Append max age.
					$headers['Cache-Control'] .= ', max-age=' . $max_age;
				}
			} elseif ( 0 === $max_age ) {
				$headers = array_merge( $headers, wp_get_nocache_headers() );
			} else {
				// Set Cache-Control header.
				$headers['Cache-Control'] = 'public, max-age=' . $max_age;
			}
		}

		return $headers;
	}

}

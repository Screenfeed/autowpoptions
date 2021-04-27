<?php
/**
 * Trait that contains tools to work with file paths.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Traits;

use WP_Error;
use WP_Filesystem_Base;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Trait that contains tools to work with file paths.
 *
 * @since 2.0.0
 */
trait FilePathTools {

	/**
	 * Normalizes a directory path.
	 * On windows systems, replaces backslashes with forward slashes and forces upper-case drive letters.
	 * Allows for two leading slashes for Windows network shares, but ensures that all other duplicate slashes are reduced to a single.
	 *
	 * @since 2.0.0
	 * @see   wp_normalize_path()
	 *
	 * @param  string $path  Full path to normalize.
	 * @return array<string> {
	 *     Array of path's infos.
	 *
	 *     @type string $wrapper The wrapper if the path is a stream URL. Ex: `ftp://`.
	 *     @type string $root    The root of the path. Ex: `/`, ``, `C:/`, `C:`.
	 *     @type string $path    The normalized full path without trailing slash.
	 * }
	 */
	protected function normalize_dir_path_array( $path ) {
		$wrapper = '';
		$root    = '';

		if ( wp_is_stream( $path ) ) {
			list( $wrapper, $path ) = explode( '://', $path, 2 );

			$wrapper .= '://';
		}

		// Standardise all paths to use '/'.
		$path = str_replace( [ '\\', DIRECTORY_SEPARATOR ], '/', $path );

		// Replace multiple slashes down to a singular, allowing for network shares having two slashes.
		$path = (string) preg_replace( '@(?<=.)/+@', '/', $path );

		if ( ':' === substr( $path, 1, 1 ) ) {
			// Windows paths should uppercase the drive letter.
			$path = ucfirst( $path ); // `C:`, `C:/`, `C:path-to-file/`, `C:/path-to-file/`.

			if ( preg_match( '@^(?<root>.:/?)(?<path>.*)$@', $path, $matches ) ) {
				$root = $matches['root']; // `C:`, `C:/`.
				$path = $matches['root'] . rtrim( $matches['path'], '/' ); // `C:path-to-file`, `C:/path-to-file`.
			}
		} elseif ( ! empty( $wrapper ) ) {
			// Stream, not Windows.
			$path = trim( $path, '/' ); // ``, `path-to-file`.
		} elseif ( '/' === substr( $path, 0, 1 ) ) {
			// Not a stream, not Windows, absolute path.
			$path = rtrim( $path, '/' ); // ``, `/path-to-file`.
			$root = '/';

			if ( '' === $path ) {
				$path = '/'; // `/`.
			}
		} else {
			// Not a stream, not Windows, relative path.
			$path = rtrim( $path, '/' ); // ``, `path-to-file`.
		} // end if.

		$path = $wrapper . $path;

		return compact( 'wrapper', 'root', 'path' );
	}

	/**
	 * Returns the list of the dirs composing a path.
	 *
	 * @since 2.0.0
	 * @see   $this->normalize_dir_path_array()
	 *
	 * @param  array<string> $normalized_path {
	 *     Array of path's infos.
	 *
	 *     @type string $wrapper The wrapper if the path is a stream URL.
	 *     @type string $root    The root of the path. Ex: `/`, ``, `C:/`, `C:`.
	 *     @type string $path    The normalized full path without trailing slash.
	 * }
	 * @return array<string>
	 */
	protected function get_path_bits( $normalized_path ) {
		$root = $normalized_path['wrapper'] . $normalized_path['root'];

		if ( '' !== $root ) {
			$root = preg_quote( $root, '@' );
			$path = (string) preg_replace( "@^$root@", '', $normalized_path['path'] );
		} else {
			$path = $normalized_path['path'];
		}

		return explode( '/', $path );
	}
}

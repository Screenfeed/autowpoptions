<?php
/**
 * Bootstraps the AutoWPOptions Unit Tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Unit
 */

namespace Screenfeed\AutoWPOptions\Tests\Unit;

use function Screenfeed\AutoWPOptions\Tests\init_test_suite;

require_once dirname( dirname( __FILE__ ) ) . '/bootstrap-functions.php';

/**
 * Get the WordPress' core directory.
 *
 * @return string Returns The directory path to the WordPress core files.
 */
function get_wp_core_dir() {
	$core_dir = getenv( 'WP_CORE_DIR' );
	$core_dir = rtrim( $core_dir, '\\/' );

	if ( empty( $core_dir ) ) {
		$core_dir = '/tmp/wordpress';
	}

	if ( ! file_exists( $core_dir . '/wp-includes' ) ) {
		trigger_error( 'Unable to run the unit tests, because the WordPress core could not be located.', E_USER_ERROR );
	}

	return $core_dir;
}

init_test_suite( 'Unit' );

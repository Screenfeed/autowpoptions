<?php
/**
 * Test Case for the `Filesystem` unit tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Unit
 */

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Filesystem\Filesystem;

use Brain\Monkey\Functions;
use org\bovigo\vfs\vfsStream;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase as BaseUnitTestCase;
use WP_Error;
use WP_Filesystem_Direct;

abstract class TestCase extends BaseUnitTestCase {
	/**
	 * @var vfsStreamDirectory
	 */
	protected $fs_root;

	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		require_once ABSPATH . 'wp-includes/class-wp-error.php';
	}

	protected function filesystem_init( $filesystem_structure = [], $error = false ) {
		if ( is_array( $filesystem_structure ) ) {
			$this->fs_root = vfsStream::setup( 'root', 0755, $filesystem_structure );
		}

		$credentials = 'credentials' === $error ? false : [];

		Functions\when( 'is_wp_error' )->alias(
			function( $thing ) {
				return $thing instanceof WP_Error;
			}
		);

		Functions\expect( 'request_filesystem_credentials' )
			->with( '' )
			->andReturn( $credentials );

		Functions\expect( 'WP_Filesystem' )
			->with( [] )
			->andReturnUsing(
				function ( $args = false, $context = false, $allow_relaxed_file_ownership = false ) use ( $error ) {
					global $wp_filesystem;

					$wp_filesystem = new WP_Filesystem_Direct( $args );

					if ( 'sandbox' === $error ) {
						trigger_error( 'Internal Error', E_USER_WARNING );
					}

					if ( ! defined( 'FS_CONNECT_TIMEOUT' ) ) {
						define( 'FS_CONNECT_TIMEOUT', 30 );
					}
					if ( ! defined( 'FS_TIMEOUT' ) ) {
						define( 'FS_TIMEOUT', 30 );
					}

					if ( 'wp_error' === $error ) {
						$wp_filesystem->errors = new WP_Error( 'test-error', 'Test error' );
					}

					if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
						return false;
					}

					if ( 'connect' === $error || ! $wp_filesystem->connect() ) {
						return false;
					}

					if ( ! defined( 'FS_CHMOD_DIR' ) ) {
						define( 'FS_CHMOD_DIR', 0755 );
					}
					if ( ! defined( 'FS_CHMOD_FILE' ) ) {
						define( 'FS_CHMOD_FILE', 0644 );
					}

					return true;
				}
			);
	}

	protected function get_file_path( $file_name ) {
		return $this->fs_root->url() . '/' . $file_name;
	}
}

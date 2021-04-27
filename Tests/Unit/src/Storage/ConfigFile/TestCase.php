<?php
/**
 * Test Case for the `ConfigFile` unit tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Unit
 */

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\ConfigFile;

use Brain\Monkey\Functions;
use org\bovigo\vfs\vfsStream;
use Screenfeed\AutoWPOptions\Tests\FilesystemTestCaseTrait;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase as BaseUnitTestCase;
use WP_Filesystem_Direct;

abstract class TestCase extends BaseUnitTestCase {
	use FilesystemTestCaseTrait;

	protected function filesystem_init( $filesystem_structure = [], $filesystem_credentials = [] ) {
		$this->fs_root = vfsStream::setup( 'root', 0755, $filesystem_structure );

		Functions\expect( 'request_filesystem_credentials' )
			->with( '' )
			->andReturn( $filesystem_credentials );

		Functions\expect( 'WP_Filesystem' )
			->with( $filesystem_credentials )
			->andReturnUsing(
				function ( $args = false, $context = false, $allow_relaxed_file_ownership = false ) {
					global $wp_filesystem;

					$wp_filesystem = new WP_Filesystem_Direct( $args );

					if ( ! defined( 'FS_CONNECT_TIMEOUT' ) ) {
						define( 'FS_CONNECT_TIMEOUT', 30 );
					}
					if ( ! defined( 'FS_TIMEOUT' ) ) {
						define( 'FS_TIMEOUT', 30 );
					}

					if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
						return false;
					}

					if ( ! $wp_filesystem->connect() ) {
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
}

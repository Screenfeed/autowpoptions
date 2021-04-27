<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Filesystem\Filesystem;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Filesystem\Filesystem;
use WP_Error;
use WP_Filesystem_Direct;

/**
 * Tests for Filesystem::__construct().
 *
 * @covers Filesystem::__construct
 * @group  Filesystem
 */
class Test___Construct extends TestCase {

	public function testShouldSetPropertiesAutomaticallyWhenBaseFilesystemIsNotProvided() {
		$this->filesystem_init( false );

		$filesystem      = new Filesystem();
		$prop_filesystem = $this->getPropertyValue( $filesystem, 'filesystem' );

		$this->assertInstanceOf( WP_Filesystem_Direct::class, $prop_filesystem );

		$prop_fs_chmod_dir = $this->getPropertyValue( $filesystem, 'fs_chmod_dir' );
		$fs_chmod_dir      = defined( 'FS_CHMOD_DIR' ) ? FS_CHMOD_DIR : fileperms( ABSPATH ) & 0777 | 0755;

		$this->assertSame( $fs_chmod_dir, $prop_fs_chmod_dir );

		$prop_fs_chmod_file = $this->getPropertyValue( $filesystem, 'fs_chmod_file' );
		$fs_chmod_file      = defined( 'FS_CHMOD_FILE' ) ? FS_CHMOD_FILE : fileperms( ABSPATH . 'index.php' ) & 0777 | 0644;

		$this->assertSame( $fs_chmod_file, $prop_fs_chmod_file );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertEmpty( $errors->get_error_codes() );
	}

	public function testShouldUseProvidedBaseFilesystem() {
		global $wp_filesystem;

		$this->filesystem_init( false );

		$this->assertTrue( WP_Filesystem() );

		$wp_filesystem->errors->add( 'test-error', 'Test error' );

		$filesystem     = new Filesystem( $wp_filesystem );
		$propfilesystem = $this->getPropertyValue( $filesystem, 'filesystem' );

		$this->assertInstanceOf( WP_Filesystem_Direct::class, $propfilesystem );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'test-error', $errors->get_error_code() );
	}

	public function testShouldGetCredentialsError() {
		$this->filesystem_init( false, 'credentials' );

		$filesystem = new Filesystem();
		$errors     = $this->getPropertyValue( $filesystem, 'errors' );
		$message    = 'Unable to get the filesystem credentials.';

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'unable_to_get_filesystem_credentials', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldCatchInternalError() {
		$this->filesystem_init( false, 'sandbox' );

		Functions\expect( 'wp_normalize_path' )
			->once()
			->andReturnUsing(
			function( $path ) {
				return str_replace( '\\', '/', $path );
			}
		);
		Functions\expect( 'wp_debug_backtrace_summary' )
			->once()
			->andReturnUsing(
			function( $ignore_class = null, $skip_frames = 0, $pretty = true ) {
				return debug_backtrace( false );
			}
		);

		$filesystem = new Filesystem();
		$errors     = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'sandbox_error', $errors->get_error_code() );
		$this->assertSame( 'Internal Error', $errors->get_error_message() );

		$error_data = $errors->get_error_data();
		$this->assertIsArray( $error_data );
		$this->assertArrayHasKey( 'callable', $error_data );
		$this->assertSame( 'WP_Filesystem', $error_data['callable'] );
	}

	public function testShouldGetWPError() {
		$this->filesystem_init( false, 'wp_error' );

		$filesystem = new Filesystem();
		$errors     = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'test-error', $errors->get_error_code() );
		$this->assertSame( 'Test error', $errors->get_error_message() );
	}

	public function testShouldGetConnectError() {
		$this->filesystem_init( false, 'connect' );

		$filesystem = new Filesystem();
		$errors     = $this->getPropertyValue( $filesystem, 'errors' );
		$message    = 'Unable to connect to the filesystem. Please confirm your credentials.';

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'unable_to_connect_to_filesystem', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

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
		add_filter( 'request_filesystem_credentials', [ $this, 'return_false' ] );

		$filesystem = new Filesystem();
		$errors     = $this->getPropertyValue( $filesystem, 'errors' );
		$message    = 'Unable to get the filesystem credentials.';

		remove_filter( 'request_filesystem_credentials', [ $this, 'return_false' ] );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'unable_to_get_filesystem_credentials', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldCatchInternalError() {
		$filesystem_credentials = function() {
			return [ 'error' => 'sandbox' ];
		};
		$this->add_filesystem_filters( $filesystem_credentials );

		$filesystem = new Filesystem();

		$this->remove_filesystem_filters( $filesystem_credentials );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'sandbox_error', $errors->get_error_code() );
		$this->assertSame( 'Internal Error', $errors->get_error_message() );

		$error_data = $errors->get_error_data();
		$this->assertIsArray( $error_data );
		$this->assertArrayHasKey( 'callable', $error_data );
		$this->assertSame( 'WP_Filesystem', $error_data['callable'] );
	}

	public function testShouldGetWPError() {
		$filesystem_credentials = function() {
			return [ 'error' => 'wp_error' ];
		};
		$this->add_filesystem_filters( $filesystem_credentials );

		$filesystem = new Filesystem();

		$this->remove_filesystem_filters( $filesystem_credentials );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'test-error', $errors->get_error_code() );
		$this->assertSame( 'Test error', $errors->get_error_message() );
	}

	public function testShouldGetConnectError() {
		$filesystem_credentials = function() {
			return [ 'error' => 'connect' ];
		};
		$this->add_filesystem_filters( $filesystem_credentials );

		$filesystem = new Filesystem();

		$this->remove_filesystem_filters( $filesystem_credentials );

		$errors  = $this->getPropertyValue( $filesystem, 'errors' );
		$message = 'Unable to connect to the filesystem. Please confirm your credentials.';

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'unable_to_connect_to_filesystem', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	private function add_filesystem_filters( $filesystem_credentials ) {
		add_filter( 'request_filesystem_credentials', $filesystem_credentials );
		add_filter( 'filesystem_method', [ $this, 'filesystem_method' ] );
		add_filter( 'filesystem_method_file', [ $this, 'filesystem_class_path' ], 10, 2 );
	}

	private function remove_filesystem_filters( $filesystem_credentials ) {
		remove_filter( 'filesystem_method_file', [ $this, 'filesystem_class_path' ], 10 );
		remove_filter( 'filesystem_method', [ $this, 'filesystem_method' ] );
		remove_filter( 'request_filesystem_credentials', $filesystem_credentials );
	}

	public function filesystem_method() {
		return 'AWPO_Fixture_Error';
	}

	public function filesystem_class_path( $path, $method ) {
		if ( $this->filesystem_method() !== $method ) {
			return $path;
		}

		return AUTOWPOPTIONS_FIXTURES_ROOT . 'src/Filesystem/Filesystem/__construct/class-wp-filesystem-awpo_fixture_error.php';
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Filesystem\Filesystem;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Filesystem\Filesystem;
use WP_Error;

/**
 * Tests for Filesystem::mkdir_recursive().
 *
 * @covers Filesystem::mkdir_recursive
 * @group  Filesystem
 */
class Test_MkdirRecursive extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		Functions\when( 'wp_is_stream' )->alias(
			function( $path ) {
				return false !== strpos( $path, '://' );
			}
		);
	}

	public function testShouldReturnFalseWhenUsingPathTraversals() {
		$this->filesystem_init( [] );

		$filesystem = new Filesystem();
		$path       = $this->get_file_path( 'foo/../bar' );
		$success    = $filesystem->mkdir_recursive( $path );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'Path traversals (use of `../`) are not allowed in `%s`.', $path );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'path_traversals_not_allowed', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldReturnFalseWhenPathExistsAndIsNotADir() {
		$this->filesystem_init( [ 'foo/bar/baz' => '<?php' ] );

		$filesystem = new Filesystem();
		$path       = $this->get_file_path( 'foo/bar/baz' );
		$success    = $filesystem->mkdir_recursive( $path );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'The path `%s` exists but is not a folder.', $path );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'path_not_dir', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldReturnFalseWhenPathExistsAndChmodFails() {
		$this->filesystem_init( [ 'foo/bar/baz' => [] ] );

		$path = $this->get_file_path( 'foo/bar/baz' );
		chmod( $path, 0444 );
		chown( $path, 123456 );

		$filesystem = new Filesystem();
		$success    = $filesystem->mkdir_recursive( $path );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'Failed to apply file permissions, the path `%s` is not writable.', $path );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'failed_to_apply_file_permissions', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldReturnTrueWhenPathExistsAndChmodSucceeds() {
		$this->filesystem_init( [ 'foo/bar/baz' => [] ] );

		$path = $this->get_file_path( 'foo/bar/baz' );
		chmod( $path, 0444 );

		$filesystem = new Filesystem();
		$success    = $filesystem->mkdir_recursive( $path );

		$this->assertTrue( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}

	public function testShouldReturnFalseWhenParentExistsAndIsNotADir() {
		$this->filesystem_init( [ 'foo' => '<?php' ] );

		$filesystem = new Filesystem();
		$success    = $filesystem->mkdir_recursive( $this->get_file_path( 'foo/bar/baz' ) );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'The path `%s` exists but is not a folder.', $this->get_file_path( 'foo' ) );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'path_not_dir', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldReturnFalseWhenParentExistsAndChmodFails() {
		$this->filesystem_init( [ 'foo' => [] ] );

		$parent = $this->get_file_path( 'foo' );
		chmod( $parent, 0444 );
		chown( $parent, 123456 );

		$filesystem = new Filesystem();
		$success    = $filesystem->mkdir_recursive( $this->get_file_path( 'foo/bar/baz' ) );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'Failed to apply file permissions, the path `%s` is not writable.', $parent );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'failed_to_apply_file_permissions', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldReturnTrueWhenParentExistsAndChmodSucceeds() {
		$this->filesystem_init( [ 'foo' => [] ] );

		chmod( $this->get_file_path( 'foo' ), 0444 );

		$filesystem = new Filesystem();
		$success    = $filesystem->mkdir_recursive( $this->get_file_path( 'foo/bar/baz' ) );

		$this->assertTrue( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}

	public function testShouldReturnTrueWhenDirExists() {
		$this->filesystem_init( [ 'foo/bar/baz' => [] ] );

		$filesystem = new Filesystem();
		$success    = $filesystem->mkdir_recursive( $this->get_file_path( 'foo/bar/baz' ) );

		$this->assertTrue( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}

	public function testShouldReturnTrueWhenCreateDir() {
		$this->filesystem_init( [ 'foo' => [] ] );

		$filesystem = new Filesystem();
		$success    = $filesystem->mkdir_recursive( $this->get_file_path( 'foo/bar/baz' ) );

		$this->assertTrue( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}
}

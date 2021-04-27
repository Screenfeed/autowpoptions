<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

use Screenfeed\AutoWPOptions\Filesystem\Filesystem;
use WP_Error;

/**
 * Tests for Filesystem::chmod_dir_and_check_if_writable().
 *
 * @covers Filesystem::chmod_dir_and_check_if_writable
 * @group  Filesystem
 */
class Test_ChmodDirAndCheckIfWritable extends TestCase {

	public function testShouldReturnFalseWhenChmodFails() {
		$this->filesystem_init( [ 'foo/bar/baz' => [] ] );

		$path = $this->get_file_path( 'foo/bar/baz' );
		chmod( $path, 0444 );
		chown( $path, 123456 );

		$filesystem = new Filesystem();
		$success    = $this->invokeMethod( $filesystem, 'chmod_dir_and_check_if_writable', [ $path, 0755 ] );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'Failed to apply file permissions, the path `%s` is not writable.', $path );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'failed_to_apply_file_permissions', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldReturnTrueWhenChmodSucceeds() {
		$this->filesystem_init( [ 'foo/bar/baz' => [] ] );

		$path = $this->get_file_path( 'foo/bar/baz' );
		chmod( $path, 0444 );

		$filesystem = new Filesystem();
		$success    = $this->invokeMethod( $filesystem, 'chmod_dir_and_check_if_writable', [ $path, 0755 ] );

		$this->assertTrue( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}
}

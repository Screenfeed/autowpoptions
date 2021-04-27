<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Filesystem\Filesystem;

use Screenfeed\AutoWPOptions\Filesystem\Filesystem;
use WP_Error;

/**
 * Tests for Filesystem::path_is_dir().
 *
 * @covers Filesystem::path_is_dir
 * @group  Filesystem
 */
class Test_PathIsDir extends TestCase {

	public function testShouldReturnFalseWhenPathIsNotADir() {
		$this->filesystem_init( [ 'foo/bar/baz' => '<?php' ] );

		$filesystem = new Filesystem();
		$path       = $this->get_file_path( 'foo/bar/baz' );
		$success    = $this->invokeMethod( $filesystem, 'path_is_dir', [ $path ] );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'The path `%s` exists but is not a folder.', $path );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'path_not_dir', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function testShouldReturnTrueWhenPathIsADir() {
		$this->filesystem_init( [ 'foo/bar/baz' => [] ] );

		$filesystem = new Filesystem();
		$path       = $this->get_file_path( 'foo/bar/baz' );
		$success    = $this->invokeMethod( $filesystem, 'path_is_dir', [ $path ] );

		$this->assertTrue( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

use Screenfeed\AutoWPOptions\Filesystem\Filesystem;
use WP_Error;

/**
 * Tests for Filesystem::path_uses_traversals().
 *
 * @covers Filesystem::path_uses_traversals
 * @group  Filesystem
 */
class Test_PathUsesTraversals extends TestCase {

	/**
	 * @dataProvider noTraversalsDataProvider
	 */
	public function testShouldReturnFalseWhenNotUsingPathTraversals( $relative_path ) {
		$this->filesystem_init( [] );

		$filesystem = new Filesystem();
		$path       = $this->get_file_path( $relative_path );
		$success    = $this->invokeMethod( $filesystem, 'path_uses_traversals', [ $path ] );

		$this->assertFalse( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}

	/**
	 * @dataProvider hasTraversalsDataProvider
	 */
	public function testShouldReturnTrueWhenUsingPathTraversals( $relative_path ) {
		$this->filesystem_init( [] );

		$filesystem = new Filesystem();
		$path       = $this->get_file_path( $relative_path );
		$success    = $this->invokeMethod( $filesystem, 'path_uses_traversals', [ $path ] );

		$this->assertTrue( $success );

		$errors = $this->getPropertyValue( $filesystem, 'errors' );
		$message = sprintf( 'Path traversals (use of `../`) are not allowed in `%s`.', $path );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'path_traversals_not_allowed', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );
	}

	public function noTraversalsDataProvider() {
		return [
			[ 'foo/bar/baz' ],
			[ '..foo/bar/baz' ],
			[ 'foo../bar/baz' ],
			[ 'f..oo/bar/baz' ],
			[ 'foo/..bar/baz' ],
			[ 'foo/bar../baz' ],
			[ 'foo/b..ar/baz' ],
			[ 'foo/bar/..baz' ],
			[ 'foo/bar/baz..' ],
			[ 'foo/bar/b..az' ],
		];
	}

	public function hasTraversalsDataProvider() {
		return [
			[ '../foo/bar/baz' ],
			[ 'foo/../bar/baz' ],
			[ 'foo/bar/baz/..' ],
			[ 'foo/../bar../baz' ],
		];
	}
}

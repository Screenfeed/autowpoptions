<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

use Screenfeed\AutoWPOptions\Filesystem\Filesystem;

/**
 * Tests for Filesystem::get_remaining_dirs().
 *
 * @covers Filesystem::get_remaining_dirs
 * @group  Filesystem
 */
class Test_GetRemainingDirs extends TestCase {

	/**
	 * @dataProvider dataProvider
	 */
	public function testShouldReturnMissingDirs( $path, $sub_path, $expected ) {
		$filesystem = new Filesystem();
		$result     = $this->invokeMethod( $filesystem, 'get_remaining_dirs', [ $path, $sub_path ] );

		$this->assertSame( $result, $expected );
	}

	public function dataProvider() {
		return [
			[ 'vfs://foo/bar/baz', 'vfs://foo', [ 'bar', 'baz' ] ],
			[ 'foo/bar/baz', 'foo', [ 'bar', 'baz' ] ],
			[ '/foo/bar/baz/', '/foo', [ 'bar', 'baz' ] ],
			[ '/foo/bar/baz/', '/foo/', [ 'bar', 'baz' ] ],
			[ 'foo/bar/baz', 'foo/bar', [ 'baz' ] ],
			[ 'foo/bar/baz', '', [ 'foo', 'bar', 'baz' ] ],
			[ 'foo/bar/baz', 'foo/bar/baz', [] ],
			[ 'foo/bar/baz', 'foo/bar/baz/yolo', [] ],
		];
	}
}

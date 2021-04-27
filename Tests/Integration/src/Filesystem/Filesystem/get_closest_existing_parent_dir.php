<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

use org\bovigo\vfs\vfsStream;
use Screenfeed\AutoWPOptions\Filesystem\Filesystem;

/**
 * Tests for Filesystem::get_closest_existing_parent_dir().
 *
 * @covers Filesystem::get_closest_existing_parent_dir
 * @group  Filesystem
 */
class Test_GetClosestExistingParentDir extends TestCase {

	/**
	 * @dataProvider dataProvider
	 */
	public function testShouldReturnClosestExistingParentDir( $structure, $normalized_path, $expected ) {
		if ( 'vfs_no_root' === $structure ) {
			$structure     = false;
			$this->fs_root = vfsStream::setup( '' );
		}

		$this->filesystem_init( $structure );

		$filesystem = new Filesystem();
		$result     = $this->invokeMethod( $filesystem, 'get_closest_existing_parent_dir', [ $normalized_path ] );

		$this->assertSame( $expected, $result );
	}

	public function dataProvider() {
		$wrapper = vfsStream::SCHEME . '://';
		$base    = $wrapper . 'root';
		return [
			[
				false,
				[
					'wrapper' => '',
					'root'    => '/',
					'path'    => '/foo/bar/baz',
				],
				'/',
			],
			[
				'vfs_no_root',
				[
					'wrapper' => $wrapper,
					'root'    => '',
					'path'    => $base . '/foo/bar/baz',
				],
				$wrapper,
			],
			[
				[],
				[
					'wrapper' => $wrapper,
					'root'    => '',
					'path'    => $base . '/foo/bar/baz',
				],
				$base,
			],
			[
				[
					'foo' => [],
				],
				[
					'wrapper' => $wrapper,
					'root'    => '',
					'path'    => $base . '/foo/bar/baz',
				],
				$base . '/foo',
			],
			[
				[
					'foo/bar/baz' => [],
				],
				[
					'wrapper' => $wrapper,
					'root'    => '',
					'path'    => $base . '/foo/bar/baz',
				],
				$base . '/foo/bar/baz',
			],
		];
	}
}

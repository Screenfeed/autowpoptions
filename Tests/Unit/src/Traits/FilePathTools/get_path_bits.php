<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Traits\FilePathTools;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\FilePathTools\FilePathToolsWrapper;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for FilePathTools::get_path_bits().
 *
 * @covers FilePathTools::get_path_bits
 * @group  FilePathTools
 */
class Test_GetPathBits extends TestCase {

	/**
	 * @dataProvider dataProvider
	 */
	public function testShouldExplodePath( $normalized_path, $expected ) {
		$tools  = new FilePathToolsWrapper();
		$result = $this->invokeMethod(
			$tools,
			'get_path_bits',
			[ $normalized_path ]
		);

		$this->assertSame( $expected, $result );
	}

	public function dataProvider() {
		return [
			[
				[
					'wrapper' => '',
					'root'    => '',
					'path'    => 'foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => '',
					'root'    => '',
					'path'    => 'f@@/bar/baz',
				],
				[ 'f@@', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => '',
					'root'    => '/',
					'path'    => '/foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => '',
					'root'    => 'C:/',
					'path'    => 'C:/foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => '',
					'root'    => 'C:',
					'path'    => 'C:foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => 'vfs://',
					'root'    => '',
					'path'    => 'vfs://foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => 'vfs://',
					'root'    => '',
					'path'    => 'vfs://foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => 'vfs://',
					'root'    => 'C:/',
					'path'    => 'vfs://C:/foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
			[
				[
					'wrapper' => 'vfs://',
					'root'    => 'C:',
					'path'    => 'vfs://C:foo/bar/baz',
				],
				[ 'foo', 'bar', 'baz' ],
			],
		];
	}
}

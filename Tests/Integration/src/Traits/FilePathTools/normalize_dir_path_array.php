<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Traits\FilePathTools;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\FilePathTools\FilePathToolsWrapper;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for FilePathTools::normalize_dir_path_array().
 *
 * @covers FilePathTools::normalize_dir_path_array
 * @group  FilePathTools
 */
class Test_NormalizeDirPathArray extends TestCase {

	/**
	 * @dataProvider dataProvider
	 */
	public function testShouldNormalizePath( $path, $expected ) {
		$tools  = new FilePathToolsWrapper();
		$result = $this->invokeMethod(
			$tools,
			'normalize_dir_path_array',
			[ $path ]
		);

		$this->assertSame( $expected, $result );
	}

	public function dataProvider() {
		return [
			[
				'foo/bar/baz',
				[
					'wrapper' => '',
					'root'    => '',
					'path'    => 'foo/bar/baz',
				],
			],
			[
				'\\foo\\\\bar\\\\\baz\\',
				[
					'wrapper' => '',
					'root'    => '/',
					'path'    => '/foo/bar/baz',
				],
			],
			[
				'c:/foo/bar/baz/',
				[
					'wrapper' => '',
					'root'    => 'C:/',
					'path'    => 'C:/foo/bar/baz',
				],
			],
			[
				'c:foo/bar/baz/',
				[
					'wrapper' => '',
					'root'    => 'C:',
					'path'    => 'C:foo/bar/baz',
				],
			],
			[
				'vfs://foo/bar/baz',
				[
					'wrapper' => 'vfs://',
					'root'    => '',
					'path'    => 'vfs://foo/bar/baz',
				],
			],
			[
				'vfs:////foo/bar/baz/',
				[
					'wrapper' => 'vfs://',
					'root'    => '',
					'path'    => 'vfs://foo/bar/baz',
				],
			],
			[
				'vfs://c:/foo/bar/baz/',
				[
					'wrapper' => 'vfs://',
					'root'    => 'C:/',
					'path'    => 'vfs://C:/foo/bar/baz',
				],
			],
			[
				'vfs://c:foo/bar/baz/',
				[
					'wrapper' => 'vfs://',
					'root'    => 'C:',
					'path'    => 'vfs://C:foo/bar/baz',
				],
			],
		];
	}
}
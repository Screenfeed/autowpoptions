<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

use Screenfeed\AutoWPOptions\Filesystem\Filesystem;

/**
 * Tests for Filesystem::get_dir_permissions().
 *
 * @covers Filesystem::get_dir_permissions
 * @group  Filesystem
 */
class Test_GetDirPermissions extends TestCase {

	public function testShouldReturnDirPermissions() {
		$dir_permissions = ( new Filesystem() )->get_dir_permissions();
		$expected        = defined( 'FS_CHMOD_DIR' ) ? FS_CHMOD_DIR : fileperms( ABSPATH ) & 0777 | 0755;

		$this->assertSame( $expected, $dir_permissions );
	}
}

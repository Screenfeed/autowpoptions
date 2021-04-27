<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Filesystem\Filesystem;

use Screenfeed\AutoWPOptions\Filesystem\Filesystem;

/**
 * Tests for Filesystem::get_file_permissions().
 *
 * @covers Filesystem::get_file_permissions
 * @group  Filesystem
 */
class Test_GetFilePermissions extends TestCase {

	public function testShouldReturnDirPermissions() {
		$this->filesystem_init( false );

		$file_permissions = ( new Filesystem() )->get_file_permissions();
		$expected         = defined( 'FS_CHMOD_FILE' ) ? FS_CHMOD_FILE : fileperms( ABSPATH . 'index.php' ) & 0777 | 0644;

		$this->assertSame( $expected, $file_permissions );
	}
}

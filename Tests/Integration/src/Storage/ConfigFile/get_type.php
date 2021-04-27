<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::get_type().
 *
 * @covers ConfigFile::get_type
 * @group  ConfigFile
 */
class Test_GetType extends TestCase {

	public function testShouldReturnType() {
		$this->filesystem_init();

		$type = ( new ConfigFile( $this->get_raw_file_path(), false ) )->get_type();

		$this->assertSame( 'config_file', $type );
	}
}

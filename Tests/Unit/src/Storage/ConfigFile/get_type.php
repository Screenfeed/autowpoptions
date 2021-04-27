<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\ConfigFile;

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

		$type = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->get_type();

		$this->assertSame( 'config_file', $type );
	}
}

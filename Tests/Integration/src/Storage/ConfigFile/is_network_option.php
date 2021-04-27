<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::is_network_option().
 *
 * @covers ConfigFile::is_network_option
 * @group  ConfigFile
 */
class Test_IsNetworkOption extends TestCase {

	public function testShouldReturnIfNetworkOption() {
		$this->filesystem_init();

		$is_network_option = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->is_network_option();

		$this->assertTrue( $is_network_option );

		$is_network_option = ( new ConfigFile( $this->get_raw_file_path(), false, [ 'network_id' => 3 ] ) )->is_network_option();

		$this->assertFalse( $is_network_option );
	}
}

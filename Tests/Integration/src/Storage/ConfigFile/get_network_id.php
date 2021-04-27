<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::get_network_id().
 *
 * @covers ConfigFile::get_network_id
 * @group  ConfigFile
 */
class Test_GetNetworkId extends TestCase {

	public function testShouldReturnNetworkId() {
		$this->filesystem_init();

		// With provided network ID.
		$network_id = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->get_network_id();

		$this->assertSame( 3, $network_id );

		// With automatic network/blog IDs.
		$network_id = ( new ConfigFile( $this->get_raw_file_path(), false ) )->get_network_id();

		$this->assertSame( get_current_network_id(), $network_id );
	}
}

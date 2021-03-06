<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::get_network_id().
 *
 * @covers WpOption::get_network_id
 * @group  WpOption
 */
class Test_GetNetworkId extends TestCase {

	public function testShouldReturnCurrentNetworkId() {
		$id = ( new WpOption( $this->option_name, false ) )->get_network_id();

		$this->assertIsInt( $id );
		$this->assertSame( get_current_network_id(), $id );
	}

	public function testShouldReturnGivenNetworkId() {
		$id = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->get_network_id();

		$this->assertSame( 4, $id );
	}
}

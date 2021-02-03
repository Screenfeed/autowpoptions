<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\WpOption;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::get_network_id().
 *
 * @covers WpOption::get_network_id
 * @group  WpOption
 */
class Test_GetNetworkId extends TestCase {

	public function testShouldReturnAnInteger() {
		$id = ( new WpOption( $this->option_name, false, [ 'network_id' => '4' ] ) )->get_network_id();

		$this->assertIsInt( $id );
	}

	public function testShouldReturnCurrentNetworkId() {
		Functions\expect( 'get_current_network_id' )
			->once()
			->andReturn( 6 );

		$id = ( new WpOption( $this->option_name, false ) )->get_network_id();

		$this->assertSame( 6, $id );
	}

	public function testShouldReturnGivenNetworkId() {
		$id = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->get_network_id();

		$this->assertSame( 4, $id );
	}
}

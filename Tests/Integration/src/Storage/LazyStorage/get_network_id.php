<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::get_network_id().
 *
 * @covers LazyStorage::get_network_id
 * @group  LazyStorage
 */
class Test_GetNetworkId extends TestCase {

	public function testShouldReturnType() {
		$storage    = new WpOption( $this->option_name, false, [ 'network_id' => $this->network_id ] );
		$network_id = ( new LazyStorage( $storage ) )->get_network_id();

		$this->assertSame( $this->network_id, $network_id );
	}
}

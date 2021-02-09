<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::is_network_option().
 *
 * @covers LazyStorage::is_network_option
 * @group  LazyStorage
 */
class Test_IsNetworkOption extends TestCase {

	public function testShouldReturnIfNetworkOption() {
		$storage = new WpOption( $this->option_name, false );
		$is      = ( new LazyStorage( $storage ) )->is_network_option();

		$this->assertFalse( $is );

		$storage = new WpOption( $this->option_name, true, [ 'network_id' => $this->network_id ] );
		$is      = ( new LazyStorage( $storage ) )->is_network_option();

		$this->assertTrue( $is );
	}
}

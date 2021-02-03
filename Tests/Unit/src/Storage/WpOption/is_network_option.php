<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::is_network_option().
 *
 * @covers WpOption::is_network_option
 * @group  WpOption
 */
class Test_IsNetworkOption extends TestCase {

	public function testShouldReturnABoolean() {
		$is = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->is_network_option();

		$this->assertIsBool( $is );
	}

	public function testShouldReturnIfNetworkOption() {
		$is = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->is_network_option();

		$this->assertFalse( $is );

		$is = ( new WpOption( $this->option_name, true, [ 'network_id' => 4 ] ) )->is_network_option();

		$this->assertTrue( $is );
	}
}

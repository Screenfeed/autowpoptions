<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::is_network_option().
 *
 * @covers WpOption::is_network_option
 * @group  WpOption
 */
class Test_IsNetworkOption extends TestCase {

	public function testShouldReturnIfNetworkOption() {
		$is = ( new WpOption( $this->option_name, false ) )->is_network_option();

		$this->assertFalse( $is );

		$is = ( new WpOption( $this->option_name, true, [ 'network_id' => $this->network_id ] ) )->is_network_option();

		$this->assertTrue( $is );
	}
}

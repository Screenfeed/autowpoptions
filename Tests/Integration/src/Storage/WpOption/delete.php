<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::delete().
 *
 * @covers WpOption::delete
 * @group  WpOption
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteNetworkOptionWhenIsNetworkOption() {
		update_network_option( $this->network_id, $this->option_name, 'foobar' );

		$deleted = ( new WpOption( $this->option_name, true, [ 'network_id' => $this->network_id ] ) )->delete();

		$this->assertTrue( $deleted );
		$this->assertFalse( get_network_option( $this->network_id, $this->option_name ) );
	}

	public function testShouldDeleteSiteOptionWhenIsNotNetworkOption() {
		update_option( $this->option_name, 'foobar' );

		$deleted = ( new WpOption( $this->option_name, false ) )->delete();

		$this->assertTrue( $deleted );
		$this->assertFalse( get_option( $this->option_name ) );
	}
}

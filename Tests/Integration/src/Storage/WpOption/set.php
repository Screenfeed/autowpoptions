<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::set().
 *
 * @covers WpOption::set
 * @group  WpOption
 */
class Test_Set extends TestCase {

	public function testShouldDeleteOptionWhenEmptyValue() {
		update_option( $this->option_name, 'foobar' );

		$deleted = ( new WpOption( $this->option_name, false ) )->set( [] );

		$this->assertTrue( $deleted );
		$this->assertFalse( get_option( $this->option_name ) );
	}

	public function testShouldUpdateNetworkOptionWhenIsNetworkOption() {
		$values = [
			'the_array'  => [ 6 ],
			'the_number' => 6,
		];

		$updated = ( new WpOption( $this->option_name, true ) )->set( $values );

		$this->assertTrue( $updated );
		$this->assertSame( $values, get_network_option( $this->network_id, $this->option_name ) );
	}

	public function testShouldUpdateSiteOptionWhenIsNotNetworkOption() {
		$values = [
			'the_array'  => [ 6 ],
			'the_number' => 6,
		];

		$updated = ( new WpOption( $this->option_name, false ) )->set( $values );

		$this->assertTrue( $updated );
		$this->assertSame( $values, get_option( $this->option_name ) );
	}
}

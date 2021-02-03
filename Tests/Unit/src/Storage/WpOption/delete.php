<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\WpOption;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::delete().
 *
 * @covers WpOption::delete
 * @group  WpOption
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteNetworkOptionWhenIsNetworkOption() {
		Functions\expect( 'delete_network_option' )
			->once()
			->with( 4, $this->option_name )
			->andReturn( true );
		Functions\expect( 'delete_option' )
			->never();

		$deleted = ( new WpOption( $this->option_name, true, [ 'network_id' => 4 ] ) )->delete();

		$this->assertTrue( $deleted );
	}

	public function testShouldDeleteSiteOptionWhenIsNotNetworkOption() {
		Functions\expect( 'delete_network_option' )
			->never();
		Functions\expect( 'delete_option' )
			->once()
			->with( $this->option_name )
			->andReturn( true );

		$deleted = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->delete();

		$this->assertTrue( $deleted );
	}
}

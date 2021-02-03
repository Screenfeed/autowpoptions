<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\WpOption;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::set().
 *
 * @covers WpOption::set
 * @group  WpOption
 */
class Test_Set extends TestCase {

	public function testShouldDeleteOptionWhenEmptyValue() {
		Functions\expect( 'delete_network_option' )
			->never();
		Functions\expect( 'delete_option' )
			->once()
			->with( $this->option_name )
			->andReturn( true );
		Functions\expect( 'update_option' )
			->never();
		Functions\expect( 'update_network_option' )
			->never();

		$deleted = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->set( [] );

		$this->assertTrue( $deleted );
	}

	public function testShouldUpdateNetworkOptionWhenIsNetworkOption() {
		$values = [
			'the_array'  => [ 6 ],
			'the_number' => 6,
		];
		Functions\expect( 'delete_network_option' )
			->never();
		Functions\expect( 'delete_option' )
			->never();
		Functions\expect( 'update_option' )
			->never();
		Functions\expect( 'update_network_option' )
			->once()
			->with( 4, $this->option_name, $values )
			->andReturn( true );

		$updated = ( new WpOption( $this->option_name, true, [ 'network_id' => 4 ] ) )->set( $values );

		$this->assertTrue( $updated );
	}

	public function testShouldUpdateAutoloadedSiteOptionWhenIsNotNetworkOption() {
		$values = [
			'the_array'  => [ 6 ],
			'the_number' => 6,
		];
		Functions\expect( 'delete_network_option' )
			->never();
		Functions\expect( 'delete_option' )
			->never();
		Functions\expect( 'update_option' )
			->times( 3 )
			->with( $this->option_name, $values, 'yes' )
			->andReturn( true );
		Functions\expect( 'update_network_option' )
			->never();

		$updated = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->set( $values );

		$this->assertTrue( $updated );

		$updated = ( new WpOption( $this->option_name, false, [ 'network_id' => 4, 'autoload' => 'yes' ] ) )->set( $values );

		$this->assertTrue( $updated );

		$updated = ( new WpOption( $this->option_name, false, [ 'network_id' => 4, 'autoload' => true ] ) )->set( $values );

		$this->assertTrue( $updated );
	}

	public function testShouldUpdateNonAutoloadedSiteOptionWhenIsNotNetworkOption() {
		$values = [
			'the_array'  => [ 6 ],
			'the_number' => 6,
		];
		Functions\expect( 'delete_network_option' )
			->never();
		Functions\expect( 'delete_option' )
			->never();
		Functions\expect( 'update_option' )
			->times( 2 )
			->with( $this->option_name, $values, 'no' )
			->andReturn( true );
		Functions\expect( 'update_network_option' )
			->never();

		$updated = ( new WpOption( $this->option_name, false, [ 'network_id' => 4, 'autoload' => 'no' ] ) )->set( $values );

		$this->assertTrue( $updated );

		$updated = ( new WpOption( $this->option_name, false, [ 'network_id' => 4, 'autoload' => false ] ) )->set( $values );

		$this->assertTrue( $updated );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\WpOption;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::get().
 *
 * @covers WpOption::get
 * @group  WpOption
 */
class Test_Get extends TestCase {

	public function testShouldReturnAnEmptyArrayWhenInvalidValue() {
		Functions\expect( 'get_option' )
			->once()
			->with( $this->option_name )
			->andReturn( 'invalid value' );
		Functions\expect( 'get_network_option' )
			->never();

		$options = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->get();

		$this->assertSame( [], $options );
	}

	public function testShouldReturnFalseWhenValueIsFalse() {
		Functions\expect( 'get_option' )
			->once()
			->with( $this->option_name )
			->andReturn( false );
		Functions\expect( 'get_network_option' )
			->never();

		$options = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->get();

		$this->assertFalse( $options );
	}

	public function testShouldReturnAnArrayWhenValidValue() {
		Functions\expect( 'get_option' )
			->twice()
			->with( $this->option_name )
			->andReturn( [], [ 'foo' => 'bar' ] );
		Functions\expect( 'get_network_option' )
			->never();

		$option = new WpOption( $this->option_name, false, [ 'network_id' => 4 ] );

		$this->assertSame( [], $option->get() );
		$this->assertSame( [ 'foo' => 'bar' ], $option->get() );
	}

	public function testShouldReturnNetworkOptionWhenIsNetworkOption() {
		Functions\expect( 'get_network_option' )
			->once()
			->with( 4, $this->option_name )
			->andReturn( [ 'foo' => 'bar' ] );
		Functions\expect( 'get_option' )
			->never();

		$options = ( new WpOption( $this->option_name, true, [ 'network_id' => 4 ] ) )->get();

		$this->assertSame( [ 'foo' => 'bar' ], $options );
	}
}

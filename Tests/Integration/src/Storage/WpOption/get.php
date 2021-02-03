<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::get().
 *
 * @covers WpOption::get
 * @group  WpOption
 */
class Test_Get extends TestCase {

	public function testShouldReturnAnEmptyArrayWhenInvalidValue() {
		update_option( $this->option_name, 'invalid value' );

		$options = ( new WpOption( $this->option_name, false ) )->get();

		$this->assertSame( [], $options );
	}

	public function testShouldReturnFalseWhenValueIsFalse() {
		$options = ( new WpOption( $this->option_name, false ) )->get();

		$this->assertFalse( $options );
	}

	public function testShouldReturnAnArrayWhenValidValue() {
		update_option( $this->option_name, [] );

		$options = ( new WpOption( $this->option_name, false ) )->get();

		$this->assertSame( [], $options );

		update_option( $this->option_name, [ 'foo' => 'bar' ] );

		$options = ( new WpOption( $this->option_name, false ) )->get();

		$this->assertSame( [ 'foo' => 'bar' ], $options );
	}

	public function testShouldReturnNetworkOptionWhenIsNetworkOption() {
		update_network_option( $this->network_id, $this->option_name, [ 'foo' => 'bar' ] );

		$options = ( new WpOption( $this->option_name, true ) )->get();

		$this->assertSame( [ 'foo' => 'bar' ], $options );
	}
}

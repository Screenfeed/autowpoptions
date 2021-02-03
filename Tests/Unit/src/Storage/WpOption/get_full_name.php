<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::get_full_name().
 *
 * @covers WpOption::get_full_name
 * @group  WpOption
 */
class Test_GetFullName extends TestCase {

	public function testShouldReturnAString() {
		$name = ( new WpOption( 22, false, [ 'network_id' => 4 ] ) )->get_full_name();

		$this->assertSame( '22', $name );
	}

	public function testShouldReturnOptionName() {
		$name = ( new WpOption( $this->option_name, false, [ 'network_id' => 4 ] ) )->get_full_name();

		$this->assertSame( $this->option_name, $name );
	}
}

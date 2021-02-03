<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::get_type().
 *
 * @covers WpOption::get_type
 * @group  WpOption
 */
class Test_GetType extends TestCase {

	public function testShouldReturnType() {
		$type = ( new WpOption( $this->option_name, false ) )->get_type();

		$this->assertSame( 'wp_option', $type );
	}
}

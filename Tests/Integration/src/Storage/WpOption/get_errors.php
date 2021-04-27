<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use WP_Error;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for WpOption::get_errors().
 *
 * @covers WpOption::get_errors
 * @group  WpOption
 */
class Test_GetErrors extends TestCase {

	public function testShouldReturnWPErrorInstance() {
		$errors = ( new WpOption( $this->option_name, false ) )->get_errors();

		$this->assertInstanceOf( WP_Error::class, $errors );
	}
}

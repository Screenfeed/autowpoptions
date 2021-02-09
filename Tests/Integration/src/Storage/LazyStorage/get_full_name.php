<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::get_full_name().
 *
 * @covers LazyStorage::get_full_name
 * @group  LazyStorage
 */
class Test_GetFullName extends TestCase {

	public function testShouldReturnFullName() {
		$storage   = new WpOption( $this->option_name, false );
		$full_name = ( new LazyStorage( $storage ) )->get_full_name();

		$this->assertSame( $this->option_name, $full_name );
	}
}

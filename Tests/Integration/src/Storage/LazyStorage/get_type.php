<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::get_type().
 *
 * @covers LazyStorage::get_type
 * @group  LazyStorage
 */
class Test_GetType extends TestCase {

	public function testShouldReturnType() {
		$storage = new WpOption( $this->option_name, false );
		$type    = ( new LazyStorage( $storage ) )->get_type();

		$this->assertSame( 'lazy|wp_option', $type );
	}
}

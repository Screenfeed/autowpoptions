<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::get().
 *
 * @covers LazyStorage::get
 * @group  LazyStorage
 */
class Test_Get extends TestCase {

	public function testShouldReturnOptionValues() {
		$expected = [ 'foo' => 'bar' ];

		update_option( $this->option_name, $expected );

		$storage = new WpOption( $this->option_name, false );
		$options = ( new LazyStorage( $storage ) )->get();

		$this->assertSame( $expected, $options );
	}
}

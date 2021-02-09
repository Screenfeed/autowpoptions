<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::set().
 *
 * @covers LazyStorage::set
 * @group  LazyStorage
 */
class Test_Set extends TestCase {

	public function testShouldSetOptionValues() {
		$expected = [ 'the_number' => 6 ];

		update_option( $this->option_name, [ 'the_number' => 8 ] );

		$inner_storage = new WpOption( $this->option_name, false );
		$storage       = new LazyStorage( $inner_storage );
		$result        = $storage->set( $expected );

		$this->assertTrue( $result );

		$values = $this->getPropertyValue( $storage, 'values' );

		$this->assertSame( $expected, $values );

		$this->setPropertyValue( $storage, 'values', null );
	}
}

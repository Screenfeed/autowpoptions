<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::delete().
 *
 * @covers LazyStorage::delete
 * @group  LazyStorage
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteOptionValues() {
		update_option( $this->option_name, [ 'the_number' => 8 ] );

		$inner_storage = new WpOption( $this->option_name, false );
		$storage       = new LazyStorage( $inner_storage );
		$result        = $storage->delete();

		$this->assertTrue( $result );

		$values = $this->getPropertyValue( $storage, 'values' );

		$this->assertFalse( $values );

		$this->setPropertyValue( $storage, 'values', null );
	}

	public function testShouldNotDeleteOptionValues() {
		$inner_storage = new WpOption( $this->option_name, false );
		$storage       = new LazyStorage( $inner_storage );
		$result        = $storage->delete();

		$this->assertFalse( $result );

		$values = $this->getPropertyValue( $storage, 'values' );

		$this->assertFalse( $values );

		$this->setPropertyValue( $storage, 'values', null );
	}
}

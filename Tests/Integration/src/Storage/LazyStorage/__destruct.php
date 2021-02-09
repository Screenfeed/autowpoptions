<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::__destruct().
 *
 * @covers LazyStorage::__destruct
 * @group  LazyStorage
 */
class Test___Destruct extends TestCase {

	public function testShouldDoNothing() {
		$expected = [ 'the_number' => 8 ];

		update_option( $this->option_name, $expected );

		$inner_storage = new WpOption( $this->option_name, false );
		$storage       = new LazyStorage( $inner_storage );

		unset( $storage );

		$this->assertSame( $expected, get_option( $this->option_name ) );
	}

	public function testShouldDeleteOptionValues() {
		update_option( $this->option_name, [ 'the_number' => 8 ] );

		$inner_storage = new WpOption( $this->option_name, false );
		$storage       = new LazyStorage( $inner_storage );

		$storage->delete();

		unset( $storage );

		$this->assertFalse( get_option( $this->option_name ) );

		$storage = new LazyStorage( $inner_storage );

		$storage->set( [] );

		unset( $storage );

		$this->assertFalse( get_option( $this->option_name ) );
	}

	public function testShouldSetOptionValues() {
		update_option( $this->option_name, [ 'the_number' => 8 ] );

		$inner_storage = new WpOption( $this->option_name, false );
		$storage       = new LazyStorage( $inner_storage );

		$storage->set( [ 'the_number' => 4 ] );

		unset( $storage );

		$this->assertSame( [ 'the_number' => 4 ], get_option( $this->option_name ) );
	}
}

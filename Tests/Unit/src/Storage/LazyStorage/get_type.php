<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::get_type().
 *
 * @covers LazyStorage::get_type
 * @group  LazyStorage
 */
class Test_GetType extends TestCase {

	public function testShouldReturnType() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get_type' )
			->willReturn( 'wp_option' );

		$type = ( new LazyStorage( $inner_storage ) )->get_type();

		$this->assertSame( 'lazy|wp_option', $type );
	}
}

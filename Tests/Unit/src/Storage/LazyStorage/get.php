<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::get().
 *
 * @covers LazyStorage::get
 * @group  LazyStorage
 */
class Test_Get extends TestCase {

	public function testShouldReturnOptionValues() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( [ 'the_number' => 8 ] );

		$values = ( new LazyStorage( $inner_storage ) )->get();

		$this->assertSame( [ 'the_number' => 8 ], $values );
	}
}

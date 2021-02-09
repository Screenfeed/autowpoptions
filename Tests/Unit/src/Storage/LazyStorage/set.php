<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::set().
 *
 * @covers LazyStorage::set
 * @group  LazyStorage
 */
class Test_Set extends TestCase {

	public function testShouldSetOptionValues() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( [ 'the_number' => 8 ] );

		$storage = new LazyStorage( $inner_storage );
		$result  = $storage->set( [ 'the_number' => 6 ] );

		$this->assertTrue( $result );

		$values = $this->getPropertyValue( $storage, 'values' );

		$this->assertSame( [ 'the_number' => 6 ], $values );

		$this->setPropertyValue( $storage, 'values', null );
	}
}

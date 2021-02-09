<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::delete().
 *
 * @covers LazyStorage::delete
 * @group  LazyStorage
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteOptionValues() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( [ 'the_number' => 8 ] );

		$storage = new LazyStorage( $inner_storage );
		$result  = $storage->delete();

		$this->assertTrue( $result );

		$values = $this->getPropertyValue( $storage, 'values' );

		$this->assertFalse( $values );

		$this->setPropertyValue( $storage, 'values', null );
	}

	public function testShouldNotDeleteOptionValues() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( false );

		$storage = new LazyStorage( $inner_storage );
		$result  = $storage->delete();

		$this->assertFalse( $result );

		$values = $this->getPropertyValue( $storage, 'values' );

		$this->assertFalse( $values );

		$this->setPropertyValue( $storage, 'values', null );
	}
}

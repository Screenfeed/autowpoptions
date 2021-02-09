<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::__destruct().
 *
 * @covers LazyStorage::__destruct
 * @group  LazyStorage
 */
class Test___Destruct extends TestCase {

	public function testShouldDoNothing() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->never() )
			->method( 'delete' );
		$inner_storage
			->expects( $this->never() )
			->method( 'set' );

		$storage = new LazyStorage( $inner_storage );

		unset( $storage );
	}

	public function testShouldDeleteOptionValues() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'delete' );
		$inner_storage
			->expects( $this->never() )
			->method( 'set' );

		$storage = new LazyStorage( $inner_storage );

		$this->setPropertyValue( $storage, 'values', false );

		unset( $storage );
	}

	public function testShouldSetOptionValues() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->never() )
			->method( 'delete' );
		$inner_storage
			->expects( $this->exactly( 2 ) )
			->method( 'set' )
			->withConsecutive(
				[ [ 'the_number' => 8 ] ],
				[ [] ]
			);

		$storage = new LazyStorage( $inner_storage );

		$this->setPropertyValue( $storage, 'values', [ 'the_number' => 8 ] );

		unset( $storage );

		$storage = new LazyStorage( $inner_storage );

		$this->setPropertyValue( $storage, 'values', [] );

		unset( $storage );
	}
}

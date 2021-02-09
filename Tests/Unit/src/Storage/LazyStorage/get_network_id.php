<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::get_network_id().
 *
 * @covers LazyStorage::get_network_id
 * @group  LazyStorage
 */
class Test_GetNetworkId extends TestCase {

	public function testShouldReturnNetworkId() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get_network_id' )
			->willReturn( 6 );

		$network_id = ( new LazyStorage( $inner_storage ) )->get_network_id();

		$this->assertSame( 6, $network_id );
	}
}

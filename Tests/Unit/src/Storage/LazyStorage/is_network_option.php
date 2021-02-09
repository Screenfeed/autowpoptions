<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::is_network_option().
 *
 * @covers LazyStorage::is_network_option
 * @group  LazyStorage
 */
class Test_IsNetworkOption extends TestCase {

	public function testShouldReturnIfNetworkOption() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'is_network_option' )
			->willReturn( true );

		$is_network_option = ( new LazyStorage( $inner_storage ) )->is_network_option();

		$this->assertTrue( $is_network_option );
	}
}

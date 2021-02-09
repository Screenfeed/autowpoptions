<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::get_full_name().
 *
 * @covers LazyStorage::get_full_name
 * @group  LazyStorage
 */
class Test_GetFullName extends TestCase {

	public function testShouldReturnFullName() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get_full_name' )
			->willReturn( 'autowpoptions_tests_settings' );

		$full_name = ( new LazyStorage( $inner_storage ) )->get_full_name();

		$this->assertSame( 'autowpoptions_tests_settings', $full_name );
	}
}

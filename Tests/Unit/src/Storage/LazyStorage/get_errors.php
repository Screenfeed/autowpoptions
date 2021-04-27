<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\LazyStorage;

use WP_Error;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

/**
 * Tests for LazyStorage::get_errors().
 *
 * @covers LazyStorage::get_errors
 * @group  LazyStorage
 */
class Test_GetErrors extends TestCase {

	public function testShouldReturnWPErrorInstance() {
		$inner_storage = $this->createMock( StorageInterface::class );
		$inner_storage
			->expects( $this->once() )
			->method( 'get_errors' )
			->willReturn( new WP_Error() );

		$errors = ( new LazyStorage( $inner_storage ) )->get_errors();

		$this->assertInstanceOf( WP_Error::class, $errors );
	}
}

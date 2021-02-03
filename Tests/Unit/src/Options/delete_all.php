<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::delete_all().
 *
 * @covers Options::delete_all
 * @group  Options
 */
class Test_DeleteAll extends TestCase {

	public function testShouldDeleteAll() {
		$storage = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'delete' )
			->willReturn( true );
		$sanitization = $this->createMock( SanitizationInterface::class );

		$result = ( new Options( $storage, $sanitization ) )->delete_all();

		$this->assertTrue( $result );
	}
}

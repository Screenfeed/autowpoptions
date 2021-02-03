<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::get_storage().
 *
 * @covers Options::get_storage
 * @group  Options
 */
class Test_GetStorage extends TestCase {

	public function testShouldReturnStorage() {
		$storage      = $this->createMock( StorageInterface::class );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$retrieved    = ( new Options( $storage, $sanitization ) )->get_storage();

		$this->assertSame( $storage, $retrieved );
	}
}

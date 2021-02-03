<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::has().
 *
 * @covers Options::has
 * @group  Options
 */
class Test_Has extends TestCase {

	public function testShouldReturnIfHasKey() {
		$storage      = $this->createMock( StorageInterface::class );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$sanitization
			->expects( $this->exactly( 2 ) )
			->method( 'get_default_values' )
			->willReturn(
				[
					'the_array'  => [],
					'the_number' => 0,
				]
			);

		$options = new Options( $storage, $sanitization );

		$this->assertFalse( $options->has( 22 ) );
		$this->assertFalse( $options->has( 'test' ) );
		$this->assertTrue( $options->has( 'the_array' ) );
	}
}

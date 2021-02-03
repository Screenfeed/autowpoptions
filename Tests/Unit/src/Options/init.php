<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Brain\Monkey\Filters;
use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::init().
 *
 * @covers Options::init
 * @group  Options
 */
class Test_Init extends TestCase {
	private $full_name = 'foobar_settings';

	public function testShouldRegisterHook() {
		$storage = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get_full_name' )
			->willReturn( $this->full_name );

		$sanitization = $this->createMock( SanitizationInterface::class );

		Filters\expectAdded( 'sanitize_option_' . $this->full_name )->with( [ $sanitization, 'sanitize_and_validate_on_update' ], 50 );

		( new Options( $storage, $sanitization ) )->init();
	}
}

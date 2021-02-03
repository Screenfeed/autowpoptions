<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::init().
 *
 * @covers Options::init
 * @group  Options
 */
class Test_Init extends TestCase {

	public function testShouldRegisterAndApplyHook() {
		$storage      = new Storage();
		$sanitization = new Sanitization();
		$options      = new Options( $storage, $sanitization );
		$expected     = [
			'the_array'  => [ '8' ],
			'the_number' => 0,
		];

		$options->init();

		$this->assertHookCallbackRegistered( 'sanitize_option_fixture_settings', [ $sanitization, 'sanitize_and_validate_on_update' ], 50 );

		$values = sanitize_option(
			'fixture_settings',
			[
				'the_array'  => [ '8' ],
				'the_number' => '6',
			]
		);

		remove_filter( 'sanitize_option_fixture_settings', [ $sanitization, 'sanitize_and_validate_on_update' ] );

		$this->assertSame( $expected, $values );
	}
}

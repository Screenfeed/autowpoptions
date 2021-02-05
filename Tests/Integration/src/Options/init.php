<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitizer;
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
		$storage   = new Storage();
		$sanitizer = new Sanitizer();
		$options   = new Options( $storage, $sanitizer );
		$expected  = [
			'the_array'  => [ '8' ],
			'the_number' => 0,
		];

		$options->init();

		$this->assertHookCallbackRegistered( 'sanitize_option_fixture_settings', [ $sanitizer, 'sanitize_and_validate_values' ], 50 );

		$values = sanitize_option(
			'fixture_settings',
			[
				'the_array'  => [ '8' ],
				'the_number' => '6',
			]
		);

		remove_filter( 'sanitize_option_fixture_settings', [ $sanitizer, 'sanitize_and_validate_values' ], 50 );

		$this->assertSame( $expected, $values );
	}
}

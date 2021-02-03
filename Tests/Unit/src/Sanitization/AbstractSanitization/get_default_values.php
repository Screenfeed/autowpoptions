<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\AbstractSanitization;

use Brain\Monkey\Filters;

/**
 * Tests for AbstractSanitization::get_default_values().
 *
 * @covers AbstractSanitization::get_default_values
 * @group  AbstractSanitization
 */
class Test_GetDefaultValues extends TestCase {

	public function testShouldReturnDefaultValues() {
		$sanitization = $this->getSanitizationMock(
			[
				'methods' => [ 'get_prefix' ],
			]
		);
		$sanitization
			->expects( $this->once() )
			->method( 'get_prefix' )
			->willReturn( 'fixture' );

		$expected     = [
			'version'    => '',
			'the_array'  => [],
			'the_number' => 0,
			'the_text'   => 'some text',
		];

		// Uncached version.
		Filters\expectApplied( 'fixture_default_settings_values' )
			->once()
			->with( [], $expected );

		$this->assertSame( $expected, $sanitization->get_default_values() );

		// Test for "cached" key.
		$default_values = $this->getPropertyValue( $sanitization, 'default_values' );

		$this->assertArrayHasKey( 'cached', $default_values );

		// Cached version.
		$this->assertSame( $expected, $sanitization->get_default_values() );
	}
}

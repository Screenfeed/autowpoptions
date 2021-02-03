<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\AbstractSanitization;

use Brain\Monkey\Filters;

/**
 * Tests for AbstractSanitization::get_reset_values().
 *
 * @covers AbstractSanitization::get_reset_values
 * @group  AbstractSanitization
 */
class Test_GetResetValues extends TestCase {

	public function testShouldReturnResetValues() {
		$sanitization = $this->getSanitizationMock(
			[
				'methods' => [ 'get_prefix' ],
			]
		);
		$sanitization
			->expects( $this->exactly( 2 ) ) // Called once by `get_reset_values()` and once by `get_default_values()`.
			->method( 'get_prefix' )
			->willReturn( 'fixture' );

		$expected     = [
			'version'    => '',
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'reset text',
		];

		// Uncached version.
		Filters\expectApplied( 'fixture_reset_settings_values' )
			->once()
			->with( $expected );

		$this->assertSame( $expected, $sanitization->get_reset_values() );

		// Test for "cached" key.
		$reset_values = $this->getPropertyValue( $sanitization, 'reset_values' );

		$this->assertArrayHasKey( 'cached', $reset_values );

		// Cached version.
		$this->assertSame( $expected, $sanitization->get_reset_values() );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\Sanitizer;

use Brain\Monkey\Filters;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_reset_values().
 *
 * @covers Sanitizer::get_reset_values
 * @group  Sanitizer
 */
class Test_GetResetValues extends TestCase {

	public function testShouldReturnResetValues() {
		$option_definition = $this->getOptionDefinitionMock();
		$option_definition
			->expects( $this->exactly( 2 ) ) // Called once by `get_reset_values()` and once by `get_default_values()`.
			->method( 'get_prefix' )
			->willReturn( $this->optionDefPrefix );
		$option_definition
			->expects( $this->exactly( 2 ) ) // Called once by `get_reset_values()` and once by `get_default_values()`.
			->method( 'get_identifier' )
			->willReturn( $this->optionDefIdentifier );

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );
		$expected  = [
			'version'    => '',
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'reset text',
		];

		// Uncached version.
		Filters\expectApplied( "{$this->optionDefPrefix}_reset_{$this->optionDefIdentifier}_values" )
			->once()
			->with( $expected );

		$this->assertSame( $expected, $sanitizer->get_reset_values() );

		// Test for "cached" key.
		$reset_values = $this->getPropertyValue( $sanitizer, 'reset_values' );

		$this->assertArrayHasKey( 'cached', $reset_values );

		// Cached version.
		$this->assertSame( $expected, $sanitizer->get_reset_values() );
	}
}

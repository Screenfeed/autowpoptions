<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\Sanitizer;

use Brain\Monkey\Filters;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_default_values().
 *
 * @covers Sanitizer::get_default_values
 * @group  Sanitizer
 */
class Test_GetDefaultValues extends TestCase {

	public function testShouldReturnDefaultValues() {
		$option_definition = $this->getOptionDefinitionMock();
		$option_definition
			->expects( $this->once() )
			->method( 'get_prefix' )
			->willReturn( $this->optionDefPrefix );
		$option_definition
			->expects( $this->once() )
			->method( 'get_identifier' )
			->willReturn( $this->optionDefIdentifier );

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );

		$expected     = [
			'version'    => '',
			'the_array'  => [],
			'the_number' => 0,
			'the_text'   => 'default text',
		];

		// Uncached version.
		Filters\expectApplied( "{$this->optionDefPrefix}_default_{$this->optionDefIdentifier}_values" )
			->once()
			->with( [], $expected );

		$this->assertSame( $expected, $sanitizer->get_default_values() );

		// Test for "cached" key.
		$default_values = $this->getPropertyValue( $sanitizer, 'default_values' );

		$this->assertArrayHasKey( 'cached', $default_values );

		// Cached version.
		$this->assertSame( $expected, $sanitizer->get_default_values() );
	}
}

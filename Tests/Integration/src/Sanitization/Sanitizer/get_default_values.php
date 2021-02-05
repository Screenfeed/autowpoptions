<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\Sanitizer\ExampleOptionDefinition;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer\TestCase;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_default_values().
 *
 * @covers Sanitizer::get_default_values
 * @group  Sanitizer
 */
class Test_GetDefaultValues extends TestCase {

	public function testShouldReturnDefaultValues() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );
		$expected  = [
			'version'    => '',
			'the_array'  => [],
			'the_number' => 0,
			'the_text'   => 'Default text',
		];

		// Uncached version.
		$this->assertSame( $expected, $sanitizer->get_default_values() );

		// Test for "cached" key.
		$default_values = $this->getPropertyValue( $sanitizer, 'default_values' );

		$this->assertArrayHasKey( 'cached', $default_values );

		// Cached version.
		$this->assertSame( $expected, $sanitizer->get_default_values() );
	}

	public function testShouldUseFilter() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );
		$expected  = [
			'version'    => '',
			'the_array'  => [],
			'the_number' => 0,
			'the_text'   => 'Default text',
			'new_entry'  => 'foo',
		];

		add_filter( 'fixture_default_settings_values', [ $this, 'default_values_filter' ], 1000, 2 );

		$this->assertSame( $expected, $sanitizer->get_default_values() );

		remove_filter( 'fixture_default_settings_values', [ $this, 'default_values_filter' ], 1000 );
	}

	public function default_values_filter( $values, $default_values ) {
		$this->assertSame( [], $values );
		$this->assertSame(
			[
				'version'    => '',
				'the_array'  => [],
				'the_number' => 0,
				'the_text'   => 'Default text',
			],
			$default_values
		);

		$values['new_entry']  = 'foo';
		$values['the_number'] = 4;

		return $values;
	}
}

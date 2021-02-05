<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\Sanitizer\ExampleOptionDefinition;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer\TestCase;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_reset_values().
 *
 * @covers Sanitizer::get_reset_values
 * @group  Sanitizer
 */
class Test_GetResetValues extends TestCase {

	public function testShouldReturnDefaultValues() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );
		$expected     = [
			'version'    => '',
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'Reset text',
		];

		// Uncached version.
		$this->assertSame( $expected, $sanitizer->get_reset_values() );

		// Test for "cached" key.
		$reset_values = $this->getPropertyValue( $sanitizer, 'reset_values' );

		$this->assertArrayHasKey( 'cached', $reset_values );

		// Cached version.
		$this->assertSame( $expected, $sanitizer->get_reset_values() );
	}

	public function testShouldUseFilter() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );
		$expected     = [
			'version'    => '',
			'the_array'  => [ 2 ],
			'the_number' => 4,
			'the_text'   => 'Reset text',
			'new_entry'  => 'foo',
		];

		add_filter( 'fixture_reset_settings_values', [ $this, 'reset_values_filter' ], 1000 );

		$this->assertSame( $expected, $sanitizer->get_reset_values() );

		remove_filter( 'fixture_reset_settings_values', [ $this, 'reset_values_filter' ], 1000 );
	}

	public function reset_values_filter( $values ) {
		$this->assertSame(
			[
				'version'    => '',
				'the_array'  => [ 2 ],
				'the_number' => 2,
				'the_text'   => 'Reset text',
			],
			$values
		);

		$values['new_entry']  = 'foo';
		$values['the_number'] = 4;
		unset( $values['the_text'] );

		return $values;
	}
}

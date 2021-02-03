<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\AbstractSanitization\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization\TestCase;

/**
 * Tests for AbstractSanitization::get_default_values().
 *
 * @covers AbstractSanitization::get_default_values
 * @group  AbstractSanitization
 */
class Test_GetDefaultValues extends TestCase {

	public function testShouldReturnDefaultValues() {
		$sanitization = new Sanitization( $this->testVersion );
		$expected     = [
			'version'    => '',
			'the_array'  => [],
			'the_number' => 0,
			'the_text'   => 'some text',
		];

		// Uncached version.
		$this->assertSame( $expected, $sanitization->get_default_values() );

		// Test for "cached" key.
		$default_values = $this->getPropertyValue( $sanitization, 'default_values' );

		$this->assertArrayHasKey( 'cached', $default_values );

		// Cached version.
		$this->assertSame( $expected, $sanitization->get_default_values() );
	}

	public function testShouldUseFilter() {
		$sanitization = new Sanitization( $this->testVersion );
		$expected     = [
			'version'    => '',
			'the_array'  => [],
			'the_number' => 0,
			'the_text'   => 'some text',
			'new_entry'  => 'foo',
		];

		add_filter( 'fixture_default_settings_values', [ $this, 'default_values_filter' ], 1000, 2 );

		$this->assertSame( $expected, $sanitization->get_default_values() );

		remove_filter( 'fixture_default_settings_values', [ $this, 'default_values_filter' ] );
	}

	public function default_values_filter( $values, $default_values ) {
		$this->assertSame( [], $values );
		$this->assertSame(
			[
				'version'    => '',
				'the_array'  => [],
				'the_number' => 0,
				'the_text'   => 'some text',
			],
			$default_values
		);

		$values['new_entry']  = 'foo';
		$values['the_number'] = 4;

		return $values;
	}
}

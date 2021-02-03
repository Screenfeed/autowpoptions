<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\AbstractSanitization\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization\TestCase;

/**
 * Tests for AbstractSanitization::get_reset_values().
 *
 * @covers AbstractSanitization::get_reset_values
 * @group  AbstractSanitization
 */
class Test_GetResetValues extends TestCase {

	public function testShouldReturnDefaultValues() {
		$sanitization = new Sanitization( $this->testVersion );
		$expected     = [
			'version'    => '',
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'reset text',
		];

		// Uncached version.
		$this->assertSame( $expected, $sanitization->get_reset_values() );

		// Test for "cached" key.
		$reset_values = $this->getPropertyValue( $sanitization, 'reset_values' );

		$this->assertArrayHasKey( 'cached', $reset_values );

		// Cached version.
		$this->assertSame( $expected, $sanitization->get_reset_values() );
	}

	public function testShouldUseFilter() {
		$sanitization = new Sanitization( $this->testVersion );
		$expected     = [
			'version'    => '',
			'the_array'  => [ 2 ],
			'the_number' => 4,
			'the_text'   => 'reset text',
			'new_entry'  => 'foo',
		];

		add_filter( 'fixture_reset_settings_values', [ $this, 'reset_values_filter' ], 1000 );

		$this->assertSame( $expected, $sanitization->get_reset_values() );

		remove_filter( 'fixture_reset_settings_values', [ $this, 'reset_values_filter' ] );
	}

	public function reset_values_filter( $values ) {
		$this->assertSame(
			[
				'version'    => '',
				'the_array'  => [ 2 ],
				'the_number' => 2,
				'the_text'   => 'reset text',
			],
			$values
		);

		$values['new_entry']  = 'foo';
		$values['the_number'] = 4;
		unset( $values['the_text'] );

		return $values;
	}
}

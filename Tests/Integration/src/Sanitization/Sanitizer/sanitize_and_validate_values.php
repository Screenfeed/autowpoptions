<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\Sanitizer\ExampleOptionDefinition;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer\TestCase;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::sanitize_and_validate_values().
 *
 * @covers Sanitizer::sanitize_and_validate_values
 * @group  Sanitizer
 */
class Test_SanitizeAndValidateValues extends TestCase {

	public function testShouldReturnValidatedValues() {
		$values    = [
			'the_array'  => [ '2', '3', '6', 3 ],
			'the_number' => '4',
			'the_text'   => 'Some <script> custom text',
			'foobar'     => 'barbaz',
		];
		$expected = [
			'the_array'  => [ 2, 3, 6 ],
			'the_number' => 0,
			'the_text'   => 'Some custom text',
			'version'    => $this->testVersion,
		];

		$sanitizer = new Sanitizer( $this->testVersion . '<script>', new ExampleOptionDefinition() );
		$validated = $sanitizer->sanitize_and_validate_values( $values );

		$this->assertSame( $expected, $validated );
	}
}

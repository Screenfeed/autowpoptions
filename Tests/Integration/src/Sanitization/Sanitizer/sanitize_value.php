<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\Sanitizer\ExampleOptionDefinition;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer\TestCase;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::sanitize_value().
 *
 * @covers Sanitizer::sanitize_value
 * @group  Sanitizer
 */
class Test_SanitizeValue extends TestCase {

	public function testShouldReturnDefaultValue() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );
		$sanitized = $sanitizer->sanitize_value( 'the_number', '0' );

		$this->assertSame( 0, $sanitized );

		$sanitized = $sanitizer->sanitize_value( 'the_number', '2', 2 );

		$this->assertSame( 2, $sanitized );
	}

	public function testShouldReturnSanitizedVersion() {
		$sanitizer = new Sanitizer( $this->testVersion . '<script>', new ExampleOptionDefinition() );
		$sanitized = $sanitizer->sanitize_value( 'version', $this->testVersion );

		$this->assertSame( $this->testVersion, $sanitized );
	}

	public function testShouldReturnSanitizedValue() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );
		$sanitized = $sanitizer->sanitize_value( 'the_text', 'Some custom <script> text' );

		$this->assertSame( 'Some custom text', $sanitized );

		$sanitized = $sanitizer->sanitize_value( 'the_array', [ '4', '6' ] );

		$this->assertSame( [ 4, 6 ], $sanitized );
	}
}

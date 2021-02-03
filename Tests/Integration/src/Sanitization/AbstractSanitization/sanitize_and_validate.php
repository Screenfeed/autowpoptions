<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\AbstractSanitization\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization\TestCase;

/**
 * Tests for AbstractSanitization::sanitize_and_validate().
 *
 * @covers AbstractSanitization::sanitize_and_validate
 * @group  AbstractSanitization
 */
class Test_SanitizeAndValidate extends TestCase {

	public function testShouldReturnDefaultValue() {
		$sanitization = new Sanitization( $this->testVersion );
		$sanitized    = $sanitization->sanitize_and_validate( 'the_number', '0' );

		$this->assertSame( 0, $sanitized );

		$sanitized = $sanitization->sanitize_and_validate( 'the_number', '2', 2 );

		$this->assertSame( 2, $sanitized );
	}

	public function testShouldReturnSanitizedVersion() {
		$sanitization = new Sanitization( $this->testVersion . '<script>' );
		$sanitized    = $sanitization->sanitize_and_validate( 'version', $this->testVersion );

		$this->assertSame( $this->testVersion, $sanitized );
	}

	public function testShouldReturnSanitizedValue() {
		$sanitization = new Sanitization( $this->testVersion );

		$sanitized = $sanitization->sanitize_and_validate( 'the_text', 'Some custom <script> text' );

		$this->assertSame( 'Some custom text', $sanitized );

		$sanitized = $sanitization->sanitize_and_validate( 'the_array', [ '4', '6' ] );

		$this->assertSame( [ 4 => 4, 6 => 6 ], $sanitized );
	}
}

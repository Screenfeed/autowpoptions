<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\AbstractSanitization;

use Brain\Monkey\Functions;

/**
 * Tests for AbstractSanitization::sanitize_and_validate().
 *
 * @covers AbstractSanitization::sanitize_and_validate
 * @group  AbstractSanitization
 */
class Test_SanitizeAndValidate extends TestCase {

	public function testShouldReturnDefaultValue() {
		$sanitization = $this->getSanitizationMock();
		$sanitization
			->expects( $this->never() )
			->method( 'sanitize_and_validate_value' );
		Functions\expect( 'sanitize_text_field' )
			->never();

		$sanitized = $sanitization->sanitize_and_validate( 'the_number', '0' );

		$this->assertSame( 0, $sanitized );

		$sanitized = $sanitization->sanitize_and_validate( 'the_number', '2', 2 );

		$this->assertSame( 2, $sanitized );
	}

	public function testShouldReturnSanitizedVersion() {
		$sanitization = $this->getSanitizationMock();
		$sanitization
			->expects( $this->never() )
			->method( 'sanitize_and_validate_value' );
		Functions\expect( 'sanitize_text_field' )
			->once()
			->with( $this->testVersion )
			->andReturnUsing(
				function ( $arg ) {
					return $arg . '.0';
				}
			);

		$sanitized = $sanitization->sanitize_and_validate( 'version', $this->testVersion );

		$this->assertSame( $this->testVersion . '.0', $sanitized );
	}

	public function testShouldReturnSanitizedValue() {
		$sanitization = $this->getSanitizationMock();
		$sanitization
			->expects( $this->once() )
			->method( 'sanitize_and_validate_value' )
			->with( 'the_text', 'custom text', 'some text' )
			->willReturnCallback( function ( $key, $value, $default ) {
				return 'Some ' . $value;
			} );
		Functions\expect( 'sanitize_text_field' )
			->never();

		$sanitized = $sanitization->sanitize_and_validate( 'the_text', 'custom text' );

		$this->assertSame( 'Some custom text', $sanitized );
	}
}

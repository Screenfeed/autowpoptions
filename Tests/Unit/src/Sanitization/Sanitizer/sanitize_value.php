<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\Sanitizer;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::sanitize_value().
 *
 * @covers Sanitizer::sanitize_value
 * @group  Sanitizer
 */
class Test_SanitizeValue extends TestCase {

	public function testShouldReturnDefaultValue() {
		$option_definition = $this->getOptionDefinitionMock();
		$option_definition
			->expects( $this->once() ) // Called by `$this->get_default_values()`.
			->method( 'get_prefix' )
			->willReturn( $this->optionDefPrefix );
		$option_definition
			->expects( $this->once() ) // Called by `$this->get_default_values()`.
			->method( 'get_identifier' )
			->willReturn( $this->optionDefIdentifier );
		$option_definition
			->expects( $this->never() )
			->method( 'sanitize_value' );
		Functions\expect( 'sanitize_text_field' )
			->never();

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );

		$sanitized = $sanitizer->sanitize_value( 'the_number', '0' );

		$this->assertSame( 0, $sanitized );

		$sanitized = $sanitizer->sanitize_value( 'the_number', '2', 2 );

		$this->assertSame( 2, $sanitized );
	}

	public function testShouldReturnSanitizedVersion() {
		$option_definition = $this->getOptionDefinitionMock();
		$option_definition
			->expects( $this->once() ) // Called by `$this->get_default_values()`.
			->method( 'get_prefix' );
		$option_definition
			->expects( $this->once() ) // Called by `$this->get_default_values()`.
			->method( 'get_identifier' );
		$option_definition
			->expects( $this->never() )
			->method( 'sanitize_value' );
		Functions\expect( 'sanitize_text_field' )
			->once()
			->with( $this->testVersion )
			->andReturnUsing(
				function ( $arg ) {
					return $arg . '.0';
				}
			);

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );

		$sanitized = $sanitizer->sanitize_value( 'version', $this->testVersion );

		$this->assertSame( $this->testVersion . '.0', $sanitized );
	}

	public function testShouldReturnSanitizedValue() {
		$option_definition = $this->getOptionDefinitionMock();
		$option_definition
			->expects( $this->once() ) // Called by `$this->get_default_values()`.
			->method( 'get_prefix' );
		$option_definition
			->expects( $this->once() ) // Called by `$this->get_default_values()`.
			->method( 'get_identifier' );
		$option_definition
			->expects( $this->once() )
			->method( 'sanitize_value' )
			->with( 'the_text', 'custom text', $this->optionDefDefaultValues['the_text'] )
			->willReturnCallback( function ( $key, $value, $default ) {
				return 'Some ' . $value;
			} );
		Functions\expect( 'sanitize_text_field' )
			->never();

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );

		$sanitized = $sanitizer->sanitize_value( 'the_text', 'custom text' );

		$this->assertSame( 'Some custom text', $sanitized );
	}
}

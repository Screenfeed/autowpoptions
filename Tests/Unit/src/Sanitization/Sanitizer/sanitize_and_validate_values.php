<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\Sanitizer;

use Brain\Monkey\Functions;
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
			'the_array' => [ '2', '3', '6' ],
			'the_text'  => 'custom text',
			'foobar'    => 'barbaz',
		];
		$sanitized = [
			'the_array' => [ 2, 3, 6 ],
			'the_text'  => 'Some custom text',
			'version'   => $this->testVersion . '.0',
		];
		$expected  = [
			'the_array' => [ 2, 6 ],
			'the_text'  => 'Some custom text',
			'version'   => $this->testVersion . '.0',
		];

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
			->expects( $this->exactly( 2 ) )
			->method( 'sanitize_value' )
			->withConsecutive(
				[ 'the_array', [ '2', '3', '6' ], $this->optionDefDefaultValues['the_array'] ],
				[ 'the_text', 'custom text', $this->optionDefDefaultValues['the_text'] ]
			)
			->willReturnCallback( function ( $key, $value, $default ) {
				switch ( $key ) {
					case 'the_array':
						return array_map( 'intval', $value );
					case 'the_text':
						return 'Some ' . $value;
					default:
						return false;
				}
			} );
		$option_definition
			->expects( $this->once() )
			->method( 'validate_values' )
			->with( $sanitized )
			->willReturnCallback( function ( $values ) {
				if ( isset( $values['the_array'] ) ) {
					$values['the_array'] = array_values( array_diff( $values['the_array'], [ 3 ] ) );
				}
				return $values;
			} );
		Functions\expect( 'sanitize_text_field' )
			->once()
			->with( $this->testVersion )
			->andReturnUsing(
				function ( $arg ) {
					return $arg . '.0';
				}
			);

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );

		$validated = $sanitizer->sanitize_and_validate_values( $values );

		$this->assertSame( $expected, $validated );
	}
}

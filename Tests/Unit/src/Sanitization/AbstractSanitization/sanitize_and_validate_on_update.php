<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\AbstractSanitization;

use Brain\Monkey\Functions;

/**
 * Tests for AbstractSanitization::sanitize_and_validate_on_update().
 *
 * @covers AbstractSanitization::sanitize_and_validate_on_update
 * @group  AbstractSanitization
 */
class Test_SanitizeAndValidateOnUpdate extends TestCase {

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

		$sanitization = $this->getSanitizationMock();
		$sanitization
			->expects( $this->exactly( 2 ) )
			->method( 'sanitize_and_validate_value' )
			->withConsecutive(
				[ 'the_array', [ '2', '3', '6' ], [] ],
				[ 'the_text', 'custom text', 'some text' ]
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
		$sanitization
			->expects( $this->once() )
			->method( 'validate_values_on_update' )
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

		$validated = $sanitization->sanitize_and_validate_on_update( $values );

		$this->assertSame( $expected, $validated );
	}
}

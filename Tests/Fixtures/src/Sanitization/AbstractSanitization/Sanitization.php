<?php

namespace Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Sanitization\AbstractSanitization;

class Sanitization extends AbstractSanitization {

	protected $prefix = 'fixture';

	protected $identifier = 'settings';

	protected $default_values = [
		'the_array'  => [],
		'the_number' => 0,
		'the_text'   => 'some text',
	];

	protected $reset_values = [
		'the_array'  => [ 2 ],
		'the_number' => 2,
		'the_text'   => 'reset text',
	];

	protected function sanitize_and_validate_value( $key, $value, $default ) {
		switch ( $key ) {
			case 'the_array':
				$value = is_array( $value ) ? array_unique( array_map( 'absint', $value ) ) : [];
				$value = ! empty( $value ) ? array_combine( $value, $value ) : [];
				return $value;
			case 'the_number':
				return absint( $value );
			case 'the_text':
				return (string) sanitize_text_field( $value );

			default:
				return false;
		}
	}

	protected function validate_values_on_update( array $values ) {
		if ( ! empty( $values['the_number'] ) && ! in_array( $values['the_number'], $values['the_array'], true ) ) {
			$values['the_number'] = 0;
		}
		return $values;
	}
}

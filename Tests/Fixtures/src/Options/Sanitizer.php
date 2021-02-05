<?php

namespace Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options;

use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;

class Sanitizer implements SanitizationInterface {

	public function get_prefix() {
		return 'fixture';
	}

	public function get_identifier() {
		return 'settings';
	}

	public function get_default_values() {
		return [
			'the_array'  => [],
			'the_number' => 0,
			'the_text'   => 'some text',
		];
	}

	public function get_reset_values() {
		return [
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'reset text',
		];
	}

	public function sanitize_value( $key, $value, $default = null ) {
		switch ( $key ) {
			case 'the_array':
				$value = is_array( $value ) ? array_unique( array_map( 'absint', $value ) ) : [];
				$value = ! empty( $value ) ? array_combine( $value, $value ) : [];
				return $value;
			case 'the_number':
				return absint( $value );
			case 'the_text':
				return sanitize_text_field( $value );

			default:
				return false;
		}
	}

	public function sanitize_and_validate_values( array $values ) {
		if ( ! empty( $values['the_number'] ) && ! in_array( $values['the_number'], $values['the_array'], true ) ) {
			$values['the_number'] = 0;
		}
		return $values;
	}
}

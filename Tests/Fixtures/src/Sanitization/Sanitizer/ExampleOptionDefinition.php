<?php

namespace Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\OptionDefinition\OptionDefinitionInterface;

class ExampleOptionDefinition implements OptionDefinitionInterface {

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
			'the_text'   => 'Default text',
		];
	}

	public function get_reset_values() {
		return [
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'Reset text',
		];
	}

	public function sanitize_value( $key, $value, $default = null ) {
		switch ( $key ) {
			case 'the_array':
				return array_values( array_unique( array_map( 'absint', $value ) ) );
			case 'the_number':
				return absint( $value );
			case 'the_text':
				return (string) sanitize_text_field( $value );
		}

		return false;
	}

	public function validate_values( array $values ) {
		if ( ! empty( $values['the_number'] ) && ! in_array( $values['the_number'], $values['the_array'], true ) ) {
			$values['the_number'] = 0;
		}
		return $values;
	}
}

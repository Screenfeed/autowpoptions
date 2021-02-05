<?php
/**
 * Example class that sanitizes and validates a set of options.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\OptionDefinition;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Example class that sanitizes and validates a set of options.
 *
 * @since 2.0.0
 */
class Example implements OptionDefinitionInterface {

	/**
	 * Returns the prefix used in hook names.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_prefix() {
		return 'myplugin';
	}

	/**
	 * Returns the identifier used in the hook names.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_identifier() {
		return 'settings';
	}

	/** ----------------------------------------------------------------------------------------- */
	/** DEFAULT + RESET VALUES ================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Returns default option values.
	 * These values are used to correctly "guess" how to type cast the option values.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed>
	 */
	public function get_default_values() {
		return [
			'the_array'      => [],
			'the_number'     => 0,
			'the_text'       => '',
			'the_other_text' => '',
		];
	}

	/**
	 * Returns the values used when the option is empty.
	 * Keys that are not set are filled with default values:
	 * return an empty array if you have nothing special to put in here.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed>
	 */
	public function get_reset_values() {
		return [
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'Reset text',
		];
	}

	/** ----------------------------------------------------------------------------------------- */
	/** SANITIZATION, VALIDATION ================================================================ */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Sanitizes an option value.
	 * This is used when getting the value from storage, and also before storing it.
	 * Type cast has already been done when reaching this point.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $key     The option key.
	 * @param  mixed  $value   The value.
	 * @param  mixed  $default The default value.
	 * @return mixed
	 */
	public function sanitize_value( $key, $value, $default = null ) {
		switch ( $key ) {
			case 'the_array':
				return array_values( array_unique( array_map( 'absint', $value ) ) );
			case 'the_number':
				return absint( $value );
			case 'the_text':
				return (string) sanitize_text_field( $value );
			case 'the_other_text':
				return (string) sanitize_text_field( $value );
		}

		return false;
	}

	/**
	 * Sanitizes and validates the values.
	 * This is used before storing them.
	 * Type cast and sanitization have already been done when reaching this point.
	 *
	 * @since 2.0.0
	 *
	 * @param  array<mixed> $values The option values.
	 * @return array<mixed>
	 */
	public function validate_values( array $values ) {
		if ( ! empty( $values['the_number'] ) && ! in_array( $values['the_number'], $values['the_array'], true ) ) {
			$values['the_number'] = 0;
		}
		return $values;
	}
}

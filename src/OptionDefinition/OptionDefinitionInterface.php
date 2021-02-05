<?php
/**
 * Interface to use to sanitize and validate options.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\OptionDefinition;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Interface to use to sanitize and validate options.
 *
 * @since 2.0.0
 */
interface OptionDefinitionInterface {

	/**
	 * Returns the prefix used in hook names.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_prefix();

	/**
	 * Returns the identifier used in the hook names.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_identifier();

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
	public function get_default_values();

	/**
	 * Returns the values used when the option is empty.
	 * Keys that are not set are filled with default values:
	 * return an empty array if you have nothing special to put in here.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed>
	 */
	public function get_reset_values();

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
	public function sanitize_value( $key, $value, $default = null );

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
	public function validate_values( array $values );
}

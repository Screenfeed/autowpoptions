<?php
/**
 * Interface to use to sanitize and validate options.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Sanitization;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Interface to use to sanitize and validate options.
 *
 * @since 1.0.0
 */
interface SanitizationInterface {

	/**
	 * Returns the prefix used in hook names.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_prefix();

	/**
	 * Returns the identifier used in the hook names.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_identifier();

	/** ----------------------------------------------------------------------------------------- */
	/** DEFAULT + RESET VALUES ================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Returns default option values.
	 *
	 * @since 1.0.0
	 *
	 * @return array<mixed>
	 */
	public function get_default_values();

	/**
	 * Returns the values used when the option is empty.
	 *
	 * @since 1.0.0
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
	 *
	 * @since 1.0.0
	 * @since 2.0.0 Renamed from `sanitize_and_validate()` to `sanitize_value()`.
	 *
	 * @param  string $key     The option key.
	 * @param  mixed  $value   The value.
	 * @param  mixed  $default The default value.
	 * @return mixed
	 */
	public function sanitize_value( $key, $value, $default = null );

	/**
	 * Sanitizes and validates the values before storing the option.
	 * Basic sanitization and validation is done, value by value.
	 * It is useful when we want to change a value depending on another one.
	 *
	 * @since 1.0.0
	 * @since 2.0.0 Renamed from `sanitize_and_validate_on_update()` to `sanitize_and_validate_values()`.
	 *
	 * @param  array<mixed> $values The option values.
	 * @return array<mixed>
	 */
	public function sanitize_and_validate_values( array $values );
}

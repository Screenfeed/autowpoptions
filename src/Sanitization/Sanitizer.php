<?php
/**
 * Class to use to sanitize and validate options.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Sanitization;

use Screenfeed\AutoWPOptions\OptionDefinition\OptionDefinitionInterface;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Class to use to sanitize and validate options.
 *
 * @since 2.0.0
 */
class Sanitizer implements SanitizationInterface {

	/**
	 * Current plugin version.
	 * It is stored with the options, and may be used during an upgrade process.
	 *
	 * @var   string
	 * @since 2.0.0
	 */
	protected $version;

	/**
	 * An instance of OptionDefinitionInterface.
	 *
	 * @var   OptionDefinitionInterface
	 * @since 2.0.0
	 */
	protected $definition;

	/**
	 * The default values.
	 * These are the "zero state" values.
	 * Don't use null as value. `cached` and `version` are reserved keys, do not use them.
	 *
	 * @var   array<mixed>
	 * @since 2.0.0
	 */
	protected $default_values;

	/**
	 * The values used when they are set the first time or reset.
	 * Values identical to default values are not listed.
	 * `cached` and `version` are reserved keys, do not use them.
	 *
	 * @var   array<mixed>
	 * @since 2.0.0
	 */
	protected $reset_values = [];

	/**
	 * The constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param  string                    $version    Current plugin version.
	 * @param  OptionDefinitionInterface $definition An instance of OptionDefinitionInterface.
	 * @return void
	 */
	public function __construct( $version, OptionDefinitionInterface $definition ) {
		$this->version        = $version;
		$this->definition     = $definition;
		$this->default_values = array_merge(
			[
				'version' => '',
			],
			$definition->get_default_values()
		);
		$this->reset_values   = $definition->get_reset_values();
	}

	/**
	 * Returns the prefix used in hook names.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_prefix() {
		return $this->definition->get_prefix();
	}

	/**
	 * Returns the identifier used in the hook names.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_identifier() {
		return $this->definition->get_identifier();
	}

	/** ----------------------------------------------------------------------------------------- */
	/** DEFAULT + RESET VALUES ================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Returns default option values.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed>
	 */
	public function get_default_values() {
		$default_values = $this->default_values;

		if ( ! empty( $default_values['cached'] ) ) {
			unset( $default_values['cached'] );
			return $default_values;
		}

		$prefix     = $this->get_prefix();
		$identifier = $this->get_identifier();

		/**
		 * Allows to add more default option values.
		 *
		 * @since 2.0.0
		 *
		 * @param array $new_values     New default option values.
		 * @param array $default_values Plugin default option values.
		 */
		$new_values = apply_filters( "{$prefix}_default_{$identifier}_values", [], $default_values );
		$new_values = is_array( $new_values ) ? $new_values : [];

		if ( ! empty( $new_values ) ) {
			// Don't allow new values to overwrite the plugin values.
			$new_values = array_diff_key( $new_values, $default_values );
		}

		if ( ! empty( $new_values ) ) {
			$default_values       = array_merge( $default_values, $new_values );
			$this->default_values = $default_values;
		}

		$this->default_values['cached'] = 1;

		return $default_values;
	}

	/**
	 * Returns the values used when the option is empty.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed>
	 */
	public function get_reset_values() {
		$reset_values = $this->reset_values;

		if ( ! empty( $reset_values['cached'] ) ) {
			unset( $reset_values['cached'] );
			return $reset_values;
		}

		$default_values = $this->get_default_values();
		$reset_values   = array_merge( $default_values, $reset_values );
		$prefix         = $this->get_prefix();
		$identifier     = $this->get_identifier();

		/**
		 * Allows to filter the "reset" option values.
		 *
		 * @since 2.0.0
		 *
		 * @param array $reset_values Plugin reset option values.
		 */
		$new_values = apply_filters( "{$prefix}_reset_{$identifier}_values", $reset_values );

		if ( ! empty( $new_values ) && is_array( $new_values ) ) {
			$reset_values = array_merge( $reset_values, $new_values );
		}

		$this->reset_values           = $reset_values;
		$this->reset_values['cached'] = 1;

		return $reset_values;
	}

	/** ----------------------------------------------------------------------------------------- */
	/** SANITIZATION, VALIDATION ================================================================ */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Sanitizes an option value.
	 * This is used when getting the value from storage, and also before storing it.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $key     The option key.
	 * @param  mixed  $value   The value.
	 * @param  mixed  $default The default value.
	 * @return mixed
	 */
	public function sanitize_value( $key, $value, $default = null ) {
		if ( ! isset( $default ) ) {
			$default_values = $this->get_default_values();
			$default        = $default_values[ $key ];
		}

		// Cast the value.
		$value = $this->cast( $value, $default );

		if ( $value === $default ) {
			return $value;
		}

		// Version.
		if ( 'version' === $key ) {
			return (string) sanitize_text_field( $value );
		}

		return $this->definition->sanitize_value( $key, $value, $default );
	}

	/**
	 * Sanitizes and validates the values.
	 * This is used before storing them.
	 *
	 * @since 2.0.0
	 *
	 * @param  array<mixed> $values The option values.
	 * @return array<mixed>
	 */
	public function sanitize_and_validate_values( array $values ) {
		$default_values = $this->get_default_values();

		if ( empty( $values['version'] ) ) {
			$values['version'] = $this->version;
		}

		foreach ( $default_values as $key => $default ) {
			if ( isset( $values[ $key ] ) ) {
				$values[ $key ] = $this->sanitize_value( $key, $values[ $key ], $default );
			}
		}

		$values = array_intersect_key( $values, $default_values );

		return $this->definition->validate_values( $values );
	}

	/** ----------------------------------------------------------------------------------------- */
	/** TOOLS =================================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Casts a value, depending on its default value type.
	 *
	 * @since 2.0.0
	 *
	 * @param  mixed $value   The value to cast.
	 * @param  mixed $default The default value.
	 * @return mixed
	 */
	protected function cast( $value, $default ) {
		if ( is_array( $default ) ) {
			return is_array( $value ) ? $value : [];
		}

		if ( is_int( $default ) ) {
			return (int) $value;
		}

		if ( is_bool( $default ) ) {
			return (bool) $value;
		}

		if ( is_float( $default ) ) {
			return round( (float) $value, 3 );
		}

		return $value;
	}
}

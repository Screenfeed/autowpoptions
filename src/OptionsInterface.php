<?php
/**
 * Interface to use to handle the plugin options.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions;

use WP_Error;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Interface to use to handle the plugin options.
 *
 * @since 2.0.0
 */
interface OptionsInterface {

	/**
	 * Launches the hooks.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function init();

	/**
	 * Returns the storage instance.
	 *
	 * @since 2.0.0
	 *
	 * @return StorageInterface
	 */
	public function get_storage();

	/**
	 * Returns the errors.
	 *
	 * @since 2.0.0
	 *
	 * @return WP_Error
	 */
	public function get_errors();

	/** ----------------------------------------------------------------------------------------- */
	/** GET/SET/DELETE OPTION(S) ================================================================ */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Returns an option.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $key The option name.
	 * @return mixed       The option value. Null if the key does not exist.
	 */
	public function get( $key );

	/**
	 * Returns all options (no cast, no sanitization, no validation).
	 * Reset values are returned if the whole option does not exist.
	 * Default values are added for the keys that are missing.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed> The options.
	 */
	public function get_all();

	/**
	 * Sets one or multiple options.
	 * Empty fields are not deleted.
	 *
	 * @since 2.0.0
	 *
	 * @param  array<mixed> $values An array of option name / option value pairs.
	 * @return bool                 True if the value was updated, false otherwise.
	 */
	public function set( array $values );

	/**
	 * Deletes one or multiple options.
	 *
	 * @since 2.0.0
	 *
	 * @param  array<string>|string $keys An array of option names or a single option name.
	 * @return bool                       True if the value was updated, false otherwise.
	 */
	public function delete( $keys );

	/**
	 * Deletes all options.
	 *
	 * @since 2.0.0
	 *
	 * @return bool True if the value was updated, false otherwise.
	 */
	public function delete_all();

	/**
	 * Checks if the option with the given name exists.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $key The option name.
	 * @return bool
	 */
	public function has( $key );
}

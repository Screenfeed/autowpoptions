<?php
/**
 * Class that defines our options storage (WP option).
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Storage;

use Screenfeed\AutoWPOptions\Traits\ErrorCatcher;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Class that defines our options storage (WP option).
 *
 * @since 1.0.0
 */
class WpOption implements StorageInterface {
	use ErrorCatcher;

	/**
	 * Suffix used in the name of the option.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	protected $option_name;

	/**
	 * Tells if the option should be a network option.
	 *
	 * @var   bool
	 * @since 1.0.0
	 */
	protected $is_network_option;

	/**
	 * Tells if the option should be autoloaded by WP.
	 * Possible values are 'yes' and 'no'.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	protected $autoload;

	/**
	 * The network ID.
	 *
	 * @var   int
	 * @since 1.0.0
	 */
	protected $network_id;

	/**
	 * The constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param  string       $option_name       Name of the option.
	 * @param  bool         $is_network_option True if a network option. False otherwise.
	 * @param  array<mixed> $args              {
	 *     Optionnal arguments.
	 *
	 *     @type bool $autoload   True if the option must be autoloaded. False otherwise. Not used for network options. Default value is true.
	 *     @type int  $network_id ID of the network. Used only for network options. Can be `0` to default to the current network ID. Default value is the current network ID.
	 * }
	 * @return void
	 */
	public function __construct( $option_name, $is_network_option, array $args = [] ) {
		$this->option_name       = (string) $option_name;
		$this->is_network_option = (bool) $is_network_option;
		$this->autoload          = isset( $args['autoload'] ) && ( 'no' === $args['autoload'] || false === $args['autoload'] ) ? 'no' : 'yes';
		$this->network_id        = ! empty( $args['network_id'] ) && is_numeric( $args['network_id'] ) ? absint( $args['network_id'] ) : get_current_network_id();
		$this->set_errors();
	}

	/**
	 * Returns the type of the storage.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'wp_option';
	}

	/**
	 * Returns the "name" of the option that stores the settings.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_full_name() {
		return $this->option_name;
	}

	/**
	 * Returns the network ID of the option.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_network_id() {
		return $this->network_id;
	}

	/**
	 * Tells if the option is a network option.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_network_option() {
		return $this->is_network_option;
	}

	/**
	 * Returns the value of all options.
	 *
	 * @since 1.0.0
	 *
	 * @return array<mixed>|false The options. False if not set yet. An empty array if invalid.
	 */
	public function get() {
		if ( $this->is_network_option() ) {
			$values = $this->box( 'get_network_option', [ $this->get_network_id(), $this->get_full_name() ] );
		} else {
			$values = $this->box( 'get_option', [ $this->get_full_name() ] );
		}

		if ( false !== $values && ! is_array( $values ) ) {
			return [];
		}

		return $values;
	}

	/**
	 * Updates the options.
	 *
	 * @since 1.0.0
	 *
	 * @param  array<mixed> $values An array of option name / option value pairs.
	 * @return bool                 True if the value was updated, false otherwise.
	 */
	public function set( array $values ) {
		if ( empty( $values ) ) {
			// The option is empty: delete it.
			return $this->delete();
		}
		if ( $this->is_network_option() ) {
			return $this->box( 'update_network_option', [ $this->get_network_id(), $this->get_full_name(), $values ] );
		}
		return $this->box( 'update_option', [ $this->get_full_name(), $values, $this->autoload ] );
	}

	/**
	 * Deletes all options.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the option was deleted, false otherwise.
	 */
	public function delete() {
		if ( $this->is_network_option() ) {
			return $this->box( 'delete_network_option', [ $this->get_network_id(), $this->get_full_name() ] );
		}
		return $this->box( 'delete_option', [ $this->get_full_name() ] );
	}
}

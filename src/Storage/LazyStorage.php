<?php
/**
 * Class to use for "lazy storage".
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Storage;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Class to use for "lazy storage".
 * We call "lazy storage" the fact that the options are stored only once per run:
 * the values are stored in the instance, then in the real storage support upon instance destruct.
 * It must be used as a wrapper of another storage.
 *
 * @since 2.0.0
 */
class LazyStorage implements StorageInterface {

	/**
	 * The wrapped storage instance.
	 *
	 * @var   StorageInterface
	 * @since 2.0.0
	 */
	protected $storage;

	/**
	 * The option values, stored locally.
	 *
	 * @var   array<mixed>|false
	 * @since 2.0.0
	 */
	protected $values;

	/**
	 * The constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param  StorageInterface $storage The wrapped storage instance.
	 * @return void
	 */
	public function __construct( StorageInterface $storage ) {
		$this->storage = $storage;
	}

	/**
	 * The destructor.
	 * Save the options on instance destruction.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function __destruct() {
		if ( false === $this->values ) {
			$this->storage->delete();
		} elseif ( is_array( $this->values ) ) {
			$this->storage->set( $this->values );
		}
	}

	/**
	 * Returns the type of the storage.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'lazy|' . $this->storage->get_type();
	}

	/**
	 * Returns the "name" of the option that stores the settings.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_full_name() {
		return $this->storage->get_full_name();
	}

	/**
	 * Returns the network ID of the option.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_network_id() {
		return $this->storage->get_network_id();
	}

	/**
	 * Tells if the option is a network option.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_network_option() {
		return $this->storage->is_network_option();
	}

	/**
	 * Returns the value of all options.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed>|false The options. False if not set yet. An empty array if invalid.
	 */
	public function get() {
		$this->maybe_store_locally();

		return $this->values;
	}

	/**
	 * Updates the options.
	 *
	 * @since 2.0.0
	 *
	 * @param  array<mixed> $values An array of option name / option value pairs.
	 * @return bool                 True if the value was updated, false otherwise.
	 */
	public function set( array $values ) {
		$this->maybe_store_locally();

		$previous     = $this->values;
		$this->values = $values;

		return $values !== $previous;
	}

	/**
	 * Deletes all options.
	 *
	 * @since 2.0.0
	 *
	 * @return bool True if the option was deleted, false otherwise.
	 */
	public function delete() {
		$this->maybe_store_locally();

		$previous     = $this->values;
		$this->values = false;

		return false !== $previous;
	}

	/**
	 * Returns the value of all options.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function maybe_store_locally() {
		if ( false !== $this->values && ! is_array( $this->values ) ) {
			$this->values = $this->storage->get();
		}
	}
}

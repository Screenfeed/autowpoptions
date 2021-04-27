<?php
/**
 * Abstract class that defines a config file options storage.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Storage;

use WP_Error;
use Screenfeed\AutoWPOptions\Filesystem\Filesystem;
use Screenfeed\AutoWPOptions\Filesystem\FilesystemInterface;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Abstract class that defines a config file options storage.
 *
 * @since 2.0.0
 */
abstract class AbstractConfigFile {

	/**
	 * Suffix used in the name of the option.
	 *
	 * @var   string
	 * @since 2.0.0
	 */
	protected $file_path;

	/**
	 * Tells if the option should be a network option.
	 *
	 * @var   bool
	 * @since 2.0.0
	 */
	protected $is_network_option;

	/**
	 * The network ID.
	 * Null for the current network ID.
	 *
	 * @var   int
	 * @since 2.0.0
	 */
	protected $network_id;

	/**
	 * An instance of FilesystemInterface.
	 *
	 * @var   FilesystemInterface
	 * @since 2.0.0
	 */
	protected $filesystem;

	/**
	 * Value to use to chmod directories.
	 * Should be `493` (for octal `0755`).
	 *
	 * @var   int
	 * @since 2.0.0
	 */
	protected $fs_chmod_dir;

	/**
	 * Value to use to chmod files.
	 * Should be `420` (for octal `0644`).
	 *
	 * @var   int
	 * @since 2.0.0
	 */
	protected $fs_chmod_file;

	/**
	 * The constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param  string       $file_path         Path to the config file.
	 * @param  bool         $is_network_option True if a network option. False otherwise.
	 * @param  array<mixed> $args              {
	 *     Optionnal arguments.
	 *
	 *     @type int                $network_id    ID of the network. Used only for network options. Can be `0` to default to the current network ID. Default value is the current network ID.
	 *     @type WP_Filesystem_Base $filesystem    An instance of WP_Filesystem_Base.
	 *     @type int                $fs_chmod_dir  Value to use to chmod directories. Should be something like `493` or `0755` (octal notation). Default is the value of the constant `FS_CHMOD_DIR`.
	 *     @type int                $fs_chmod_file Value to use to chmod files. Should be something like `420` or `0644` (octal notation). Default is the value of the constant `FS_CHMOD_FILE`.
	 * }
	 * @return void
	 */
	public function __construct( $file_path, $is_network_option, array $args = [] ) {
		$this->file_path         = (string) $file_path;
		$this->is_network_option = (bool) $is_network_option;
		$this->network_id        = ! empty( $args['network_id'] ) && is_numeric( $args['network_id'] ) ? absint( $args['network_id'] ) : get_current_network_id();
		$this->filesystem        = ! empty( $args['filesystem'] ) ? $args['filesystem'] : false;
		$this->fs_chmod_dir      = ! empty( $args['fs_chmod_dir'] ) && is_numeric( $args['fs_chmod_dir'] ) ? intval( $args['fs_chmod_dir'], 0 ) : 0;
		$this->fs_chmod_file     = ! empty( $args['fs_chmod_file'] ) && is_numeric( $args['fs_chmod_file'] ) ? intval( $args['fs_chmod_file'], 0 ) : 0;

		$this->format_file_path();
		$this->set_missing_filesystem_properties();
	}

	/**
	 * Returns the "name" of the option that stores the settings.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_full_name() {
		return $this->file_path;
	}

	/**
	 * Returns the network ID of the option.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_network_id() {
		return $this->network_id;
	}

	/**
	 * Tells if the option is a network option.
	 *
	 * @since 2.0.0
	 *
	 * @return bool
	 */
	public function is_network_option() {
		return $this->is_network_option;
	}

	/**
	 * Returns the value of all options.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed>|false The options. False if not set yet. An empty array if invalid.
	 */
	public function get() {
		if ( ! $this->filesystem->exists( $this->get_full_name() ) || ! $this->filesystem->is_file( $this->get_full_name() ) ) {
			return false;
		}

		return $this->get_file_values();
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
		$option          = $this->get_full_name();
		$original_values = $values;

		if ( $this->filesystem->exists( $option ) ) {
			if ( ! $this->filesystem->is_file( $option ) ) {
				// The target exists but is not a file, we won't be able to write it (and don't want to delete it).
				return false;
			}
			// Merge new values into the old ones.
			$values = array_merge( $this->get_file_values(), $values );

		} elseif ( ! $this->filesystem->mkdir_recursive( dirname( $option ), $this->fs_chmod_dir ) ) {
			// The parent folder could not be created.
			return false;
		}

		/**
		 * Filters an option values.
		 *
		 * @since 2.0.0
		 * @since WP 2.3.0
		 * @since WP 4.3.0 Added the `$original_values` parameter.
		 * @see   sanitize_option()
		 *
		 * @param array  $values          The option values.
		 * @param string $option          The option name (file path).
		 * @param array  $original_values The original values passed to the function.
		 */
		$sanitized_values = apply_filters( "sanitize_option_{$option}", $values, $option, $original_values );

		if ( is_array( $sanitized_values ) ) {
			$values = $sanitized_values;
		}

		return $this->set_file_values( $values );
	}

	/**
	 * Deletes all options.
	 *
	 * @since 2.0.0
	 *
	 * @return bool True if the option was deleted, false otherwise.
	 */
	public function delete() {
		return (bool) $this->filesystem->delete( $this->get_full_name(), false, 'f' );
	}

	/**
	 * Returns the errors.
	 *
	 * @since 2.0.0
	 *
	 * @return WP_Error
	 */
	public function get_errors() {
		return $this->filesystem->get_errors();
	}

	/** ----------------------------------------------------------------------------------------- */
	/** INTERNAL TOOLS ========================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Formats the file path. It can be used to replace placeholders with their corresponding value.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	abstract protected function format_file_path();

	/**
	 * Sets the properties that are missing.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function set_missing_filesystem_properties() {
		if ( ! $this->filesystem instanceof FilesystemInterface ) {
			$this->filesystem = new Filesystem();
		}

		if ( empty( $this->fs_chmod_dir ) ) {
			$this->fs_chmod_dir = $this->filesystem->get_dir_permissions();
		}

		if ( empty( $this->fs_chmod_file ) ) {
			$this->fs_chmod_file = $this->filesystem->get_file_permissions();
		}
	}

	/**
	 * Returns the array contained in the config file.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed> The array returned by the config file.
	 */
	abstract protected function get_file_values();

	/**
	 * Writes the array into the config file.
	 *
	 * @since 2.0.0
	 *
	 * @param  array<mixed> $data The array to write into the config file.
	 * @return bool               True on success, false otherwise.
	 */
	abstract protected function set_file_values( array $data );

	/**
	 * Formats string data to make it more readable.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $data    The data to format.
	 * @param  string $default Default data to return if the data is "empty".
	 * @return string $data
	 */
	abstract protected function format_data( $data, $default = '' );
}

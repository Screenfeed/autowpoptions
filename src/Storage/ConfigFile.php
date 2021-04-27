<?php
/**
 * Class that defines our options storage (config file).
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Storage;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Class that defines our options storage (config file).
 *
 * @since 2.0.0
 */
class ConfigFile extends AbstractConfigFile implements StorageInterface {

	/**
	 * The constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param  string       $file_path         Path to the config file. Some placeholders are replaced with their corresponding value:
	 *                                         - `{autowpoptions-network-id}` is replaced with the network ID.
	 *                                         - `{autowpoptions-blog-id}` is replaced with `network` for a network option, or with the blog ID for a blog option.
	 *                                         The php file extension is also enforced.
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
	public function __construct( $file_path, $is_network_option, array $args = [] ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		// UselessOverridingMethod: used to add doc about the placeholders in the file path.
		parent::__construct( $file_path, $is_network_option, $args );
	}

	/**
	 * Returns the type of the storage.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'config_file';
	}

	/** ----------------------------------------------------------------------------------------- */
	/** INTERNAL TOOLS ========================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Formats the file path by replacing placeholders with their corresponding value:
	 * - `{autowpoptions-network-id}` is replaced with the network ID.
	 * - `{autowpoptions-blog-id}` is replaced with `network` for a network option, or with the blog ID for a blog option.
	 * This also enforces the php file extension.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function format_file_path() {
		// Replace placeholders.
		$network_id_placeholder = '{autowpoptions-network-id}';
		$blog_id_placeholder    = '{autowpoptions-blog-id}';
		$blog_id_replacement    = $this->is_network_option ? 'network' : get_current_blog_id();

		$this->file_path = str_replace( $network_id_placeholder, "{$this->network_id}", $this->file_path );
		$this->file_path = str_replace( $blog_id_placeholder, "{$blog_id_replacement}", $this->file_path );

		// Enforce php file extension.
		if ( pathinfo( $this->file_path, PATHINFO_EXTENSION ) !== 'php' ) {
			$this->file_path .= '.php';
		}
	}

	/**
	 * Returns the array contained in the config file.
	 *
	 * @since 2.0.0
	 *
	 * @return array<mixed> The array returned by the config file.
	 */
	protected function get_file_values() {
		ob_start();
		$values = include $this->get_full_name();
		ob_end_clean();

		if ( ! is_array( $values ) ) {
			return [];
		}

		return $values;
	}

	/**
	 * Writes the array into the config file.
	 *
	 * @since 2.0.0
	 *
	 * @param  array<mixed> $data The array to write into the config file.
	 * @return bool               True on success, false otherwise.
	 */
	protected function set_file_values( array $data ) {
		$data = call_user_func( '\var_export', $data, true );
		$data = $this->format_data( $data, 'array()' );
		$data = "<?php\ndefined( 'ABSPATH' ) || exit;\n\nreturn {$data};\n";

		return $this->filesystem->put_contents( $this->get_full_name(), $data, $this->fs_chmod_file );
	}

	/**
	 * Formats string data to make it more readable.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $data    The data to format.
	 * @param  string $default Default data to return if the data is "empty".
	 * @return string $data
	 */
	protected function format_data( $data, $default = '' ) {
		if ( empty( $data ) || ! is_string( $data ) ) {
			return (string) $default;
		}

		$replace = [
			'/=>\s+array/'              => '=> array',
			'/=>\s+\(object\)\s+array/' => '=> (object) array',
			'/__set_state\(array/'      => '__set_state( array',
			'/\)\)/'                    => ') )',
			'/array\s*\(/'              => 'array(',
			'/array\(\s+\)/'            => 'array()',
		];

		foreach ( $replace as $search => $replacement ) {
			$data = (string) preg_replace( $search, $replacement, $data );
		}

		return $data;
	}
}

<?php
/**
 * Abstract class that contains tools to work with the filesystem.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Filesystem;

use Error;
use WP_Error;
use WP_Filesystem_Base;
use Screenfeed\AutoWPOptions\Traits\ErrorCatcher;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Abstract class that contains tools to work with the filesystem.
 *
 * @since 2.0.0
 *
 * @method int|false    atime( string $file ) Gets the file's last access time.
 * @method bool         chdir( string $dir ) Changes current directory.
 * @method bool         chgrp( string $file, string|int $group, bool $recursive = false ) Changes the file group.
 * @method bool         chmod( string $file, int|false $mode = false, bool $recursive = false ) Changes filesystem permissions.
 * @method bool         chown( string $file, string|int $owner, bool $recursive = false ) Changes the owner of a file or directory.
 * @method bool         copy( string $source, string $destination, bool $overwrite = false, int|false $mode = false ) Copies a file.
 * @method string|false cwd() Gets the current working directory.
 * @method bool         delete( string $file, bool $recursive = false, string|false $type = false ) Deletes a file or directory.
 * @method array|false  dirlist( string $path, bool $include_hidden = true, bool $recursive = false ) Gets details for files in a directory or a specific file.
 * @method bool         exists( string $path ) Checks if a file or directory exists.
 * @method array|false  get_contents_array( string $file ) Reads entire file into an array.
 * @method string|false get_contents( string $file ) Reads entire file into a string.
 * @method string       getchmod( string $file ) Gets the permissions of the specified file or filepath in their octal format.
 * @method string|false group( string $file ) Gets the file's group.
 * @method bool         is_dir( string $path ) Checks if a resource is a directory.
 * @method bool         is_file( string $path ) Checks if a resource is a file.
 * @method bool         is_readable( string $path ) Checks if a file is readable.
 * @method bool         is_writable( string $path ) Checks if a file or directory is writable.
 * @method bool         mkdir( string $path, int|false $chmod = false, string|int|false $chown = false, string|int|false $chgrp = false ) Creates a directory.
 * @method bool         move( string $source, string $destination, bool $overwrite = false ) Moves a file.
 * @method int|false    mtime( string $file ) Gets the file modification time.
 * @method string|false owner( string $path ) Gets the file owner.
 * @method bool         put_contents( string $file, string $contents, int|false $mode = false ) Writes a string to a file.
 * @method bool         rmdir( string $file, bool $recursive = false ) Deletes a directory.
 * @method int|false    size( string $file ) Gets the file size (in bytes).
 * @method bool         touch( string $file, int $time = 0, int $atime = 0 ) Sets the access and modification times of a file.
 */
abstract class AbstractFilesystem {
	use ErrorCatcher;

	/**
	 * An instance of WP_Filesystem_Base.
	 *
	 * @var   WP_Filesystem_Base
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
	 * Constructor.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Filesystem_Base $filesystem Optional. An instance of WP_Filesystem_Base.
	 *                                        Make sure to connect the filesystem with `request_filesystem_credentials()` and `WP_Filesystem()` before injecting it.
	 *                                        Defaults to the filesystem currently used on the site. CREDENTIALS ARE NOT REQUESTED TO THE USER IN THIS CASE.
	 * @return void
	 */
	public function __construct( $filesystem = null ) {
		if ( $filesystem instanceof WP_Filesystem_Base ) {
			$this->filesystem = $filesystem;

			if ( is_wp_error( $this->filesystem->errors ) ) {
				$this->set_errors( $this->filesystem->errors );
			} else {
				// This should not be needed, but just in case.
				$this->set_errors();
			}
		} else {
			$this->set_default_filesystem();
		}

		// Default permissions.
		$this->fs_chmod_dir  = defined( 'FS_CHMOD_DIR' ) ? FS_CHMOD_DIR : fileperms( ABSPATH ) & 0777 | 0755;
		$this->fs_chmod_file = defined( 'FS_CHMOD_FILE' ) ? FS_CHMOD_FILE : fileperms( ABSPATH . 'index.php' ) & 0777 | 0644;
	}

	/**
	 * Get the filesystem permissions for directories.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_dir_permissions() {
		return $this->fs_chmod_dir;
	}

	/**
	 * Get the filesystem permissions for files.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_file_permissions() {
		return $this->fs_chmod_file;
	}

	/**
	 * Use `WP_Filesystem_Base`'s methods.
	 *
	 * @since  2.0.0
	 * @throws Error When the method does not exist in `WP_Filesystem_Base`.
	 *
	 * @param  string       $method    Name of the called method.
	 * @param  array<mixed> $arguments Arguments passed to the method.
	 * @return mixed
	 */
	public function __call( $method, array $arguments = [] ) {
		$methods = [
			'atime'              => 1,
			'chdir'              => 1,
			'chgrp'              => 1,
			'chmod'              => 1,
			'chown'              => 1,
			'copy'               => 1,
			'cwd'                => 1,
			'delete'             => 1,
			'dirlist'            => 1,
			'exists'             => 1,
			'get_contents_array' => 1,
			'get_contents'       => 1,
			'getchmod'           => 1,
			'group'              => 1,
			'is_dir'             => 1,
			'is_file'            => 1,
			'is_readable'        => 1,
			'is_writable'        => 1,
			'mkdir'              => 1,
			'move'               => 1,
			'mtime'              => 1,
			'owner'              => 1,
			'put_contents'       => 1,
			'rmdir'              => 1,
			'size'               => 1,
			'touch'              => 1,
		];

		if ( empty( $methods[ $method ] ) ) {
			throw new Error( "Call to undefined method $method" );
		}

		return $this->box( [ $this->filesystem, $method ], $arguments );
	}

	/** ----------------------------------------------------------------------------------------- */
	/** INTERNAL TOOLS ========================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Set the default filesystem.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	protected function set_default_filesystem() {
		global $wp_filesystem;

		// `get_filesystem_credentials()` and `box()` may need this.
		$this->set_errors();

		$success = $this->box( 'WP_Filesystem', [ $this->get_filesystem_credentials() ] );

		if ( ! is_wp_error( $wp_filesystem->errors ) ) {
			// This should not be needed, but just in case.
			$wp_filesystem->errors = new WP_Error();
		}

		$this->filesystem = $wp_filesystem;

		if ( $this->get_errors()->has_errors() ) {
			// Credentials error, sandbox error, or filesystem internal error.
			$this->copy_errors( $this->get_errors(), $this->filesystem->errors );
			$this->set_errors( $this->filesystem->errors );
			return;
		}

		$this->set_errors( $this->filesystem->errors );

		if ( ! $success && ! $this->get_errors()->has_errors() ) {
			// Filesystem connection error.
			$this->get_errors()->add( 'unable_to_connect_to_filesystem', 'Unable to connect to the filesystem. Please confirm your credentials.' );
		}
	}

	/**
	 * Returns the filesystem credentials.
	 *
	 * @since 2.0.0
	 *
	 * @return array<string>
	 */
	protected function get_filesystem_credentials() {
		ob_start();
		$credentials = request_filesystem_credentials( '' );
		ob_end_clean();

		if ( false === $credentials ) {
			$this->get_errors()->add( 'unable_to_get_filesystem_credentials', 'Unable to get the filesystem credentials.' );
		}

		if ( ! is_array( $credentials ) ) {
			return [];
		}

		return $credentials;
	}

	/**
	 * Copies errors from one WP_Error instance to another.
	 *
	 * @since 2.0.0
	 * @see   Inspired from WP_Error::copy_errors() (WP 5.6.0).
	 *
	 * @param  WP_Error $from The WP_Error object to copy from.
	 * @param  WP_Error $to   The WP_Error object to copy to.
	 * @return void
	 */
	protected function copy_errors( WP_Error $from, WP_Error $to ) {
		if ( method_exists( $to, 'merge_from' ) ) {
			$to->merge_from( $from );
			return;
		}

		foreach ( $from->get_error_codes() as $code ) {
			foreach ( $from->get_error_messages( $code ) as $error_message ) {
				$to->add( $code, $error_message );
			}

			if ( isset( $from->error_data[ $code ] ) ) {
				$to->add_data( $from->error_data[ $code ], $code );
			}
		}
	}
}

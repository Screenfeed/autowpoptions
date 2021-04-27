<?php
/**
 * Filesystem Class.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Filesystem;

use WP_Filesystem_Base;
use Screenfeed\AutoWPOptions\Traits\FilePathTools;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Filesystem Class.
 *
 * @since 2.0.0
 *
 * WARNING: some, if not all, of these methods will return null upon failure.
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
class Filesystem extends AbstractFilesystem implements FilesystemInterface {
	use FilePathTools;

	/**
	 * Recursive directory creation based on full path. Will attempt to set permissions on folders.
	 *
	 * @since 2.0.0
	 *
	 * @param  string           $path  Path for new directory.
	 * @param  int|false        $chmod Optional. The permissions. Should be something like `493` or `0755` (octal notation). Default false. Falls back to the value of the constant `FS_CHMOD_DIR`.
	 * @param  string|int|false $chown Optional. A user name or number (or false to skip chown). Default false.
	 * @param  string|int|false $chgrp Optional. A group name or number (or false to skip chgrp). Default false.
	 * @return bool                    Whether the path was created. True if path already exists.
	 */
	public function mkdir_recursive( $path, $chmod = false, $chown = false, $chgrp = false ) {
		$normalized_path = $this->normalize_dir_path_array( $path );

		if ( empty( $chmod ) ) {
			$chmod = $this->get_dir_permissions();
		}

		if ( $this->path_uses_traversals( $normalized_path['path'] ) ) {
			return false;
		}

		if ( $this->exists( $normalized_path['path'] ) ) {
			if ( ! $this->path_is_dir( $normalized_path['path'] ) ) {
				// The path exists but is not a directory.
				return false;
			}
			return $this->chmod_dir_and_check_if_writable( $normalized_path['path'], $chmod );
		}

		$parent_path = $this->get_closest_existing_parent_dir( $normalized_path );

		foreach ( $this->get_remaining_dirs( $normalized_path['path'], $parent_path ) as $folder_part ) {
			$path = $parent_path . '/' . $folder_part;
			$path = str_replace( ':///', '://', $path );

			if ( ! $this->path_is_dir( $parent_path ) ) {
				// The parent path is not a directory.
				return false;
			}

			if ( ! $this->chmod_dir_and_check_if_writable( $parent_path, $chmod ) ) {
				// The parent directory is not writable.
				return false;
			}

			if ( ! $this->mkdir( $path, $chmod, $chown, $chgrp ) ) {
				// Failed to create the directory.
				$this->get_errors()->add(
					'path_not_created',
					sprintf( 'The path `%s` could not be created.', $path ),
					compact( 'path' )
				);
				return false;
			}
		}

		return $this->chmod_dir_and_check_if_writable( $path, $chmod );
	}

	/** ----------------------------------------------------------------------------------------- */
	/** INTERNAL TOOLS ========================================================================== */
	/** ----------------------------------------------------------------------------------------- */

	/**
	 * Tells if the given path uses path traversals (`../`).
	 * This also adds an error if path traversals are detected.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $path A normalized path.
	 * @return bool
	 */
	protected function path_uses_traversals( $path ) {
		if ( ! preg_match( '@(?:^|/|:)\.\./@', $path . '/' ) ) {
			return false;
		}

		$this->get_errors()->add(
			'path_traversals_not_allowed',
			sprintf( 'Path traversals (use of `../`) are not allowed in `%s`.', $path ),
			compact( 'path' )
		);
		return true;
	}

	/**
	 * Tells if the given path is a directory.
	 * This also adds an error if it isn't.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $path A path.
	 * @return bool
	 */
	protected function path_is_dir( $path ) {
		if ( $this->is_dir( $path ) ) {
			return true;
		}

		$this->get_errors()->add(
			'path_not_dir',
			sprintf( 'The path `%s` exists but is not a folder.', $path ),
			compact( 'path' )
		);
		return false;
	}

	/**
	 * Applies `chmod()` to a dir then checks it is writable.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $path  Full path to test.
	 * @param  int    $chmod Optional. The permissions. Should be something like `493` or `0755` (octal notation). Default false. Falls back to the value of the constant `FS_CHMOD_DIR`.
	 * @return bool          Whether the path is writable.
	 */
	protected function chmod_dir_and_check_if_writable( $path, $chmod ) {
		$this->chmod( $path, $chmod );

		if ( $this->is_writable( $path ) ) {
			return true;
		}

		$this->get_errors()->add(
			'failed_to_apply_file_permissions',
			sprintf( 'Failed to apply file permissions, the path `%s` is not writable.', $path ),
			compact( 'path', 'chmod' )
		);
		return false;
	}

	/**
	 * Get the path to closest existing valid parent directory.
	 *
	 * @since 2.0.0
	 * @see   FilePathTools->normalize_dir_path_array()
	 *
	 * @param  array<string> $normalized_path {
	 *     Array of path's infos.
	 *
	 *     @type string $wrapper The wrapper if the path is a stream URL.
	 *     @type string $root    The root of the path. Ex: `/`, ``, `C:/`, `C:`.
	 *     @type string $path    The normalized full path without trailing slash.
	 * }
	 * @return string A path (without trailing slash) to an existing directory within the given path.
	 */
	protected function get_closest_existing_parent_dir( $normalized_path ) {
		$path   = $normalized_path['path'];
		$parent = dirname( $path );
		$root   = $normalized_path['wrapper'] . $normalized_path['root'];

		while ( '.' !== $parent && $path !== $parent && ! $this->exists( $path ) ) {
			$path   = $parent;
			$parent = dirname( $path );
		}

		if ( strlen( $path ) <= strlen( $root ) ) {
			// `dirname( 'vfs://root/' )` will return `vfs:` instead of `vfs://`, we need to fix that.
			return $root;
		}

		return $path;
	}

	/**
	 * Get a list of directories from `$path` that are not in `$sub_path`.
	 *
	 * @since 2.0.0
	 *
	 * @param  string $path     A path (with or without trailing slash).
	 * @param  string $sub_path A sub path of `$path` (with or without trailing slash).
	 * @return array<string>    A list of directories from `$path` that are not in `$sub_path`.
	 */
	protected function get_remaining_dirs( $path, $sub_path ) {
		$folder_parts = explode( '/', substr( $path, strlen( $sub_path ) ) );

		return array_values(
			array_filter(
				$folder_parts,
				function ( $folder_part ) {
					return '' !== $folder_part;
				}
			)
		);
	}
}

<?php
/**
 * Interface for filesystem methods.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Filesystem;

use WP_Error;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * Interface for filesystem methods.
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
interface FilesystemInterface {

	/**
	 * Get the filesystem permissions for directories.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_dir_permissions();

	/**
	 * Get the filesystem permissions for files.
	 *
	 * @since 2.0.0
	 *
	 * @return int
	 */
	public function get_file_permissions();

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
	public function mkdir_recursive( $path, $chmod = false, $chown = false, $chgrp = false );

	/**
	 * Returns the errors.
	 *
	 * @since 2.0.0
	 *
	 * @return WP_Error
	 */
	public function get_errors();
}

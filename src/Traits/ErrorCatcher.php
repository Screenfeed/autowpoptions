<?php
/**
 * ErrorCatcher trait, a sandbox to catch errors.
 *
 * @package Screenfeed/autowpoptions
 */

namespace Screenfeed\AutoWPOptions\Traits;

use ErrorException;
use WP_Error;

defined( 'ABSPATH' ) || exit; // @phpstan-ignore-line

/**
 * ErrorCatcher trait, a sandbox to catch errors.
 * This is inspired by https://github.com/symfony/filesystem/blob/5.x/Filesystem.php#L738-L765.
 *
 * @since 2.0.0
 */
trait ErrorCatcher {

	/**
	 * Errors.
	 *
	 * @var   WP_Error
	 * @since 2.0.0
	 */
	protected $errors;

	/**
	 * Returns the errors.
	 *
	 * @since 2.0.0
	 *
	 * @return WP_Error
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Sets the entire errors object.
	 *
	 * @since 2.0.0
	 *
	 * @param  WP_Error $errors Optional. A WP_Error object. Default is a new WP_Error instance.
	 * @return void
	 */
	protected function set_errors( WP_Error $errors = null ) {
		if ( ! empty( $errors ) ) {
			$this->errors = $errors;
		} else {
			$this->errors = new WP_Error();
		}
	}

	/**
	 * Sand box to catch warnings.
	 *
	 * @since 2.0.0
	 *
	 * @param  callable     $func A callable to sandbox.
	 * @param  array<mixed> $args An array of arguments to use with the callable.
	 * @return mixed
	 */
	protected function box( callable $func, array $args = [] ) {
		set_error_handler( [ $this, 'error_handler' ] ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_set_error_handler

		try {
			$result = call_user_func_array( $func, $args );
		} catch ( ErrorException $e ) {
			if ( is_array( $func ) && isset( $func[0], $func[1] ) && is_object( $func[0] ) && is_string( $func[1] ) ) {
				$func = get_class( $func[0] ) . '->' . $func[1];
			}

			if ( empty( $this->errors ) ) {
				$this->set_errors();
			}

			$this->errors->add(
				'sandbox_error',
				$e->getMessage(),
				[
					'callable' => $func,
					'args'     => $args,
					'file'     => wp_normalize_path( $e->getFile() ),
					'line'     => $e->getLine(),
					'trace'    => wp_debug_backtrace_summary( null, 1, false ), // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_wp_debug_backtrace_summary
				]
			);
			$result = null;
		}

		restore_error_handler();

		return $result;
	}

	/**
	 * Error handler that throws `ErrorException` exceptions.
	 * The aim is to catch non-exception errors (like `E_WARNING`).
	 *
	 * @since  2.0.0
	 * @access private
	 * @throws ErrorException Triggered every time.
	 *
	 * @param  int    $severity Level of the error raised.
	 * @param  string $message  Error message.
	 * @param  string $file     Filename that the error was raised in.
	 * @param  int    $line     Line number where the error was raised.
	 * @return void
	 */
	public function error_handler( $severity, $message, $file, $line ) {
		throw new ErrorException( $message, 0, $severity, $file, $line );
	}
}

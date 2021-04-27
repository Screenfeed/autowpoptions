<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Traits\ErrorCatcher;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\ErrorCatcher\ErrorCatcherWrapper;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use WP_Error;

/**
 * Tests for ErrorCatcher::box().
 *
 * @covers ErrorCatcher::box
 * @group  ErrorCatcher
 */
class Test_Box extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		Functions\when( 'wp_normalize_path' )->alias(
			function( $path ) {
				return str_replace( '\\', '/', $path );
			}
		);
		Functions\when( 'wp_debug_backtrace_summary' )->alias(
			function( $ignore_class = null, $skip_frames = 0, $pretty = true ) {
				return debug_backtrace( false );
			}
		);
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function testShouldCatchError( $error_type ) {
		$catcher = new ErrorCatcherWrapper();
		$message = "Error Type $error_type";
		$result  = $this->invokeMethod(
			$catcher,
			'box',
			[
				'trigger_error',
				[ $message, constant( $error_type ) ],
			]
		);

		$this->assertNull( $result );

		$errors = $this->getPropertyValue( $catcher, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'sandbox_error', $errors->get_error_code() );
		$this->assertSame( $message, $errors->get_error_message() );

		$error_data = $errors->get_error_data();
		$this->assertIsArray( $error_data );
		$this->assertArrayHasKey( 'callable', $error_data );
		$this->assertSame( 'trigger_error', $error_data['callable'] );
	}

	public function testShouldCatchMethodError() {
		$catcher = new ErrorCatcherWrapper();
		$result  = $this->invokeMethod(
			$catcher,
			'box',
			[
				[ $catcher, 'trigger_error_method' ],
				[],
			]
		);

		$this->assertNull( $result );

		$errors = $this->getPropertyValue( $catcher, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );

		$error_data = $errors->get_error_data();
		$this->assertIsArray( $error_data );
		$this->assertArrayHasKey( 'callable', $error_data );
		$this->assertSame( ErrorCatcherWrapper::class . '->trigger_error_method', $error_data['callable'] );
	}

	public function dataProvider() {
		return [
			[
				'E_USER_WARNING',
			],
			[
				'E_USER_NOTICE',
			],
			[
				'E_USER_ERROR',
			],
		];
	}
}

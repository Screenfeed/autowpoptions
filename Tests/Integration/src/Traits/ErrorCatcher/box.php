<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Traits\ErrorCatcher;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\ErrorCatcher\ErrorCatcherWrapper;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;
use WP_Error;

/**
 * Tests for ErrorCatcher::box().
 *
 * @covers ErrorCatcher::box
 * @group  ErrorCatcher
 */
class Test_Box extends TestCase {

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

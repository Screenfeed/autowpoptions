<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Traits\ErrorCatcher;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\ErrorCatcher\ErrorCatcherWrapper;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;
use WP_Error;

/**
 * Tests for ErrorCatcher::set_errors().
 *
 * @covers ErrorCatcher::set_errors
 * @group  ErrorCatcher
 */
class Test_SetErrors extends TestCase {

	public function testShouldSetEmptyWPError() {
		$catcher = new ErrorCatcherWrapper();
		$this->invokeMethod(
			$catcher,
			'set_errors'
		);
		$errors = $this->getPropertyValue( $catcher, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( '', $errors->get_error_code() );
		$this->assertSame( '', $errors->get_error_message() );
	}

	public function testShouldSetWPError() {
		$error_code    = 'error_code';
		$error_message = 'Error Message.';

		$catcher = new ErrorCatcherWrapper();
		$this->invokeMethod(
			$catcher,
			'set_errors',
			[ new WP_Error( $error_code, $error_message ) ]
		);
		$errors = $this->getPropertyValue( $catcher, 'errors' );

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( $error_code, $errors->get_error_code() );
		$this->assertSame( $error_message, $errors->get_error_message() );
	}
}

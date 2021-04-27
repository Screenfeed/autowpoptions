<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Traits\ErrorCatcher;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\ErrorCatcher\ErrorCatcherWrapper;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use WP_Error;

/**
 * Tests for ErrorCatcher::get_errors().
 *
 * @covers ErrorCatcher::get_errors
 * @group  ErrorCatcher
 */
class Test_GetErrors extends TestCase {

	public function testShouldReturnWPError() {
		$error_code    = 'error_code';
		$error_message = 'Error Message.';

		$catcher = new ErrorCatcherWrapper();
		$this->setPropertyValue( $catcher, 'errors', new WP_Error( $error_code, $error_message ) );
		$errors = $catcher->get_errors();

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( $error_code, $errors->get_error_code() );
		$this->assertSame( $error_message, $errors->get_error_message() );
	}
}

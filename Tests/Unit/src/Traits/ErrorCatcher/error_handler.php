<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Traits\ErrorCatcher;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\ErrorCatcher\ErrorCatcherWrapper;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use ErrorException;

/**
 * Tests for ErrorCatcher::error_handler().
 *
 * @covers ErrorCatcher::error_handler
 * @group  ErrorCatcher
 */
class Test_ErrorHandler extends TestCase {

	public function testShouldThrowException() {
		$message = 'Error Message.';
		$this->expectException( ErrorException::class );
		$this->expectExceptionCode( 0 );
		$this->expectExceptionMessage( $message );

		( new ErrorCatcherWrapper() )->error_handler( E_USER_WARNING, $message, 'test.php', 16 );
	}
}

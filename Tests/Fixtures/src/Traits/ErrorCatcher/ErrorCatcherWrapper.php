<?php

namespace Screenfeed\AutoWPOptions\Tests\Fixtures\src\Traits\ErrorCatcher;

use Screenfeed\AutoWPOptions\Traits\ErrorCatcher;

class ErrorCatcherWrapper {
	use ErrorCatcher;

	public function trigger_error_method() {
		trigger_error( 'Error Type E_USER_WARNING', E_USER_WARNING );
	}
}

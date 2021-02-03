<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration;

use Screenfeed\AutoWPOptions\Tests\Integration\HookCallbackTrait;
use Screenfeed\AutoWPOptions\Tests\TestCaseTrait;
use WP_UnitTestCase;

abstract class TestCase extends WP_UnitTestCase {
	use HookCallbackTrait;
	use TestCaseTrait;

	public function return_true() {
		return true;
	}

	public function return_false() {
		return false;
	}
}

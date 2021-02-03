<?php
/**
 * Test Case for the `WpOption` unit tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Unit
 */

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase as BaseUnitTestCase;

abstract class TestCase extends BaseUnitTestCase {
	protected $option_name = 'autowpoptions_tests_settings';
}

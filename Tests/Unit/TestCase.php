<?php
/**
 * Test Case for all of the unit tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Unit
 */

namespace Screenfeed\AutoWPOptions\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Brain\Monkey;
use Screenfeed\AutoWPOptions\Tests\TestCaseTrait;

abstract class TestCase extends PHPUnitTestCase {
	use TestCaseTrait;

	/**
	 * Prepares the test environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	/**
	 * Cleans up the test environment after each test.
	 */
	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}

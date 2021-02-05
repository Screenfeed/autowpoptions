<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\Sanitizer\ExampleOptionDefinition;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer\TestCase;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_prefix().
 *
 * @covers Sanitizer::get_prefix
 * @group  Sanitizer
 */
class Test_GetPrefix extends TestCase {

	public function testShouldReturnPrefix() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );

		$this->assertSame( 'fixture', $sanitizer->get_prefix() );
	}
}

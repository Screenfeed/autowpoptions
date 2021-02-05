<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\Sanitizer\ExampleOptionDefinition;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\Sanitizer\TestCase;
use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_identifier().
 *
 * @covers Sanitizer::get_identifier
 * @group  Sanitizer
 */
class Test_GetIdentifier extends TestCase {

	public function testShouldReturnIdentifier() {
		$sanitizer = new Sanitizer( $this->testVersion, new ExampleOptionDefinition() );

		$this->assertSame( 'settings', $sanitizer->get_identifier() );
	}
}

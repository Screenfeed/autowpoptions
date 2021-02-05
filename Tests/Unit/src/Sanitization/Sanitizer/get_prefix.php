<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_prefix().
 *
 * @covers Sanitizer::get_prefix
 * @group  Sanitizer
 */
class Test_GetPrefix extends TestCase {

	public function testShouldReturnPrefix() {
		$option_definition = $this->getOptionDefinitionMock();
		$option_definition
			->expects( $this->once() )
			->method( 'get_prefix' )
			->willReturn( $this->optionDefPrefix );

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );

		$this->assertSame( $this->optionDefPrefix, $sanitizer->get_prefix() );
	}
}

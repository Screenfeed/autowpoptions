<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\Sanitization\Sanitizer;

/**
 * Tests for Sanitizer::get_identifier().
 *
 * @covers Sanitizer::get_identifier
 * @group  Sanitizer
 */
class Test_GetIdentifier extends TestCase {

	public function testShouldReturnPrefix() {
		$option_definition = $this->getOptionDefinitionMock();
		$option_definition
			->expects( $this->once() )
			->method( 'get_identifier' )
			->willReturn( $this->optionDefIdentifier );

		$sanitizer = new Sanitizer( $this->testVersion, $option_definition );

		$this->assertSame( $this->optionDefIdentifier, $sanitizer->get_identifier() );
	}
}

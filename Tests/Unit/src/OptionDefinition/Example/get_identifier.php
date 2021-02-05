<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\OptionDefinition\Example;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

/**
 * Tests for Example::get_identifier().
 *
 * @covers Example::get_identifier
 * @group  Example
 */
class Test_GetIdentifier extends TestCase {

	public function testShouldReturnIdentifier() {
		$option_definition = new Example();

		$this->assertSame( 'settings', $option_definition->get_identifier() );
	}
}

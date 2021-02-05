<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\OptionDefinition\Example;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

/**
 * Tests for Example::get_default_values().
 *
 * @covers Example::get_default_values
 * @group  Example
 */
class Test_GetDefaultValues extends TestCase {

	public function testShouldReturnDefaultValues() {
		$option_definition = new Example();
		$expected          = [
			'the_array'      => [],
			'the_number'     => 0,
			'the_text'       => '',
			'the_other_text' => '',
		];

		$this->assertSame( $expected, $option_definition->get_default_values() );
	}
}

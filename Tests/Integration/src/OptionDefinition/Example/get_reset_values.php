<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\OptionDefinition\Example;

use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

/**
 * Tests for Example::get_reset_values().
 *
 * @covers Example::get_reset_values
 * @group  Example
 */
class Test_GetResetValues extends TestCase {

	public function testShouldReturnResetValues() {
		$option_definition = new Example();
		$expected          = [
			'the_array'  => [ 2 ],
			'the_number' => 2,
			'the_text'   => 'Reset text',
		];

		$this->assertSame( $expected, $option_definition->get_reset_values() );
	}
}

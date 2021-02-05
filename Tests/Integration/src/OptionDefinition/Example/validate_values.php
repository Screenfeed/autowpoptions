<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\OptionDefinition\Example;

use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

/**
 * Tests for Example::validate_values().
 *
 * @covers Example::validate_values
 * @group  Example
 */
class Test_ValidateValues extends TestCase {

	public function testShouldReturnValidatedValues() {

		$option_definition = new Example();

		$this->assertSame(
			[
				'the_array'  => [ 4 ],
				'the_number' => 0,
				'the_text'   => 'some text',
			],
			$option_definition->validate_values(
				[
					'the_array'  => [ 4 ],
					'the_number' => 2,
					'the_text'   => 'some text',
				]
			)
		);

		$this->assertSame(
			[
				'the_array'  => [ 4 ],
				'the_number' => '',
				'the_text'   => 'some text',
			],
			$option_definition->validate_values(
				[
					'the_array'  => [ 4 ],
					'the_number' => '',
					'the_text'   => 'some text',
				]
			)
		);
	}
}

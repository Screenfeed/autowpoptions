<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\OptionDefinition\Example;

use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

/**
 * Tests for Example::sanitize_value().
 *
 * @covers Example::sanitize_value
 * @group  Example
 */
class Test_SanitizeValue extends TestCase {

	public function testShouldReturnSanitizedValue() {
		$tests = [
			[
				'option'   => 'the_array',
				'value'    => [ '7', '8', 7, '-9' ],
				'expected' => [ 7, 8, 9 ],
			],
			[
				'option'   => 'the_number',
				'value'    => '-9',
				'expected' => 9,
			],
			[
				'option'   => 'the_text',
				'value'    => 'Some <script> text',
				'expected' => 'Some text',
			],
			[
				'option'   => 'the_other_text',
				'value'    => 'Some other <script> text',
				'expected' => 'Some other text',
			],
		];

		$option_definition = new Example();

		foreach ( $tests as $test ) {
			$this->assertSame(
				$test['expected'],
				$option_definition->sanitize_value( $test['option'], $test['value'] )
			);
		}
	}
}

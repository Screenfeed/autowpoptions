<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\OptionDefinition\Example;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

/**
 * Tests for Example::sanitize_value().
 *
 * @covers Example::sanitize_value
 * @group  Example
 */
class Test_SanitizeValue extends TestCase {

	public function testShouldReturnSanitizedValue() {
		Functions\expect( 'sanitize_text_field' )
			->withAnyArgs()
			->andReturnUsing(
				function ( $arg ) {
					return $arg . ' sanitized.';
				}
			);
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
				'value'    => 'Some text',
				'expected' => 'Some text sanitized.',
			],
			[
				'option'   => 'the_other_text',
				'value'    => 'Some other text',
				'expected' => 'Some other text sanitized.',
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

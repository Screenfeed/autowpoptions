<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\OptionDefinition\Example;

use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;
use Screenfeed\AutoWPOptions\OptionDefinition\Example;

/**
 * Tests for Example::get_prefix().
 *
 * @covers Example::get_prefix
 * @group  Example
 */
class Test_GetPrefix extends TestCase {

	public function testShouldReturnPrefix() {
		$option_definition = new Example();

		$this->assertSame( 'myplugin', $option_definition->get_prefix() );
	}
}

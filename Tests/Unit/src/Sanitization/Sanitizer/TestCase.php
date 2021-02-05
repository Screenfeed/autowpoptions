<?php
/**
 * Test Case for the `Sanitizer` unit tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Unit
 */

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\Sanitizer;

use Screenfeed\AutoWPOptions\OptionDefinition\OptionDefinitionInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase as BaseUnitTestCase;

abstract class TestCase extends BaseUnitTestCase {
	protected $testVersion            = '2.3.4';
	protected $optionDefPrefix        = 'fixture';
	protected $optionDefIdentifier    = 'settings';
	protected $optionDefDefaultValues = [
		'the_array'  => [],
		'the_number' => 0,
		'the_text'   => 'default text',
	];
	protected $optionDefResetValues   = [
		'the_array'  => [ 2 ],
		'the_number' => 2,
		'the_text'   => 'reset text',
	];

	public function getOptionDefinitionMock() {
		$option_definition = $this->createMock( OptionDefinitionInterface::class );
		$option_definition
			->expects( $this->once() )
			->method( 'get_default_values' )
			->willReturn( $this->optionDefDefaultValues );
		$option_definition
			->expects( $this->once() )
			->method( 'get_reset_values' )
			->willReturn( $this->optionDefResetValues );

		return $option_definition;
	}
}

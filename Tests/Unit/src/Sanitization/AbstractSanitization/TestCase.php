<?php
/**
 * Test Case for the `AbstractSanitization` unit tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Unit
 */

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Sanitization\AbstractSanitization;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase as BaseUnitTestCase;

abstract class TestCase extends BaseUnitTestCase {
	protected $testVersion = '2.3.4';

	public function getSanitizationMock( $args = [] ) {
		$methods = [ 'sanitize_and_validate_value', 'validate_values_on_update' ];

		if ( ! empty( $args['methods'] ) && is_array( $args['methods'] ) ) {
			$methods = array_merge( $methods, $args['methods'] );
		}

		if ( ! isset( $args['prefix'] ) || ! is_string( $args['prefix'] ) ) {
			$args['prefix'] = 'fixture';
		}

		if ( ! isset( $args['identifier'] ) || ! is_string( $args['identifier'] ) ) {
			$args['identifier'] = 'settings';
		}

		if ( ! isset( $args['default_values'] ) || ! is_array( $args['default_values'] ) ) {
			$args['default_values'] = [
				'the_array'  => [],
				'the_number' => 0,
				'the_text'   => 'some text',
			];
		}

		if ( ! isset( $args['reset_values'] ) || ! is_array( $args['reset_values'] ) ) {
			$args['reset_values'] = [
				'the_array'  => [ 2 ],
				'the_number' => 2,
				'the_text'   => 'reset text',
			];
		}

		$sanitization = $this->getMockBuilder( AbstractSanitization::class )
			->disableOriginalConstructor()
			->setMethods( $methods )
			->getMock();
		$this->setPropertyValue( $sanitization, 'prefix', $args['prefix'] );
		$this->setPropertyValue( $sanitization, 'identifier', $args['identifier'] );
		$this->setPropertyValue( $sanitization, 'default_values', $args['default_values'] );
		$this->setPropertyValue( $sanitization, 'reset_values', $args['reset_values'] );

		$sanitization->__construct( $this->testVersion );

		return $sanitization;
	}
}

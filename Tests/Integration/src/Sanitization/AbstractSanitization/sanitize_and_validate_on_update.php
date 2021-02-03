<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\AbstractSanitization\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization\TestCase;

/**
 * Tests for AbstractSanitization::sanitize_and_validate_on_update().
 *
 * @covers AbstractSanitization::sanitize_and_validate_on_update
 * @group  AbstractSanitization
 */
class Test_SanitizeAndValidateOnUpdate extends TestCase {

	public function testShouldReturnValidatedValues() {
		$values    = [
			'the_array'  => [ '2', '3', '6' ],
			'the_number' => '4',
			'the_text'   => 'Some <script> custom text',
			'foobar'     => 'barbaz',
		];
		$expected = [
			'the_array'  => [ 2 => 2, 3 => 3, 6 => 6 ],
			'the_number' => 0,
			'the_text'   => 'Some custom text',
			'version'    => $this->testVersion,
		];

		$sanitization = new Sanitization( $this->testVersion . '<script>' );
		$validated    = $sanitization->sanitize_and_validate_on_update( $values );

		$this->assertSame( $expected, $validated );
	}
}

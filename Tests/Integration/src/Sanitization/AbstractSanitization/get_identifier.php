<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\AbstractSanitization\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization\TestCase;

/**
 * Tests for AbstractSanitization::get_identifier().
 *
 * @covers AbstractSanitization::get_identifier
 * @group  AbstractSanitization
 */
class Test_GetIdentifier extends TestCase {

	public function testShouldReturnIdentifier() {
		$prefix = ( new Sanitization( $this->testVersion ) )->get_identifier();

		$this->assertSame( 'settings', $prefix );
	}
}

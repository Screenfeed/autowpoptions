<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\AbstractSanitization;

/**
 * Tests for AbstractSanitization::get_identifier().
 *
 * @covers AbstractSanitization::get_identifier
 * @group  AbstractSanitization
 */
class Test_GetIdentifier extends TestCase {

	public function testShouldReturnIdentifier() {
		$sanitization = $this->getSanitizationMock();

		$this->assertSame( 'settings', $sanitization->get_identifier() );
	}
}

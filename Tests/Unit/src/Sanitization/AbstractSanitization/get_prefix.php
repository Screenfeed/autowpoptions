<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Sanitization\AbstractSanitization;

/**
 * Tests for AbstractSanitization::get_prefix().
 *
 * @covers AbstractSanitization::get_prefix
 * @group  AbstractSanitization
 */
class Test_GetPrefix extends TestCase {

	public function testShouldReturnPrefix() {
		$sanitization = $this->getSanitizationMock();

		$this->assertSame( 'fixture', $sanitization->get_prefix() );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization;

use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Sanitization\AbstractSanitization\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Integration\src\Sanitization\AbstractSanitization\TestCase;

/**
 * Tests for AbstractSanitization::get_prefix().
 *
 * @covers AbstractSanitization::get_prefix
 * @group  AbstractSanitization
 */
class Test_GetPrefix extends TestCase {

	public function testShouldReturnStorage() {
		$prefix = ( new Sanitization( $this->testVersion ) )->get_prefix();

		$this->assertSame( 'fixture', $prefix );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitizer;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::get_storage().
 *
 * @covers Options::get_storage
 * @group  Options
 */
class Test_GetStorage extends TestCase {

	public function testShouldReturnStorage() {
		$storage   = new Storage();
		$sanitizer = new Sanitizer();
		$retrieved = ( new Options( $storage, $sanitizer ) )->get_storage();

		$this->assertSame( $storage, $retrieved );
	}
}

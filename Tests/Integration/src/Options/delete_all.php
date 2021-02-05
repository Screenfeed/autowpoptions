<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitizer;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::delete_all().
 *
 * @covers Options::delete_all
 * @group  Options
 */
class Test_DeleteAll extends TestCase {

	public function testShouldDeleteAll() {
		$storage   = new Storage();
		$sanitizer = new Sanitizer();

		$result = ( new Options( $storage, $sanitizer ) )->delete_all();

		$this->assertTrue( $result );
		$this->assertFalse( $storage->values );
	}
}

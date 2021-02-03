<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitization;
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
		$storage      = new Storage();
		$sanitization = new Sanitization();

		$result = ( new Options( $storage, $sanitization ) )->delete_all();

		$this->assertTrue( $result );
		$this->assertFalse( $storage->values );
	}
}

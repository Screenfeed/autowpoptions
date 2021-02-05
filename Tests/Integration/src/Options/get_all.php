<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitizer;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::get_all().
 *
 * @covers Options::get_all
 * @group  Options
 */
class Test_GetAll extends TestCase {

	public function testShouldReturnOnlyValidValues() {
		$storage   = new Storage();
		$sanitizer = new Sanitizer();
		$result    = ( new Options( $storage, $sanitizer ) )->get_all();
		$expected  = [
			'the_array'  => [],
			'the_number' => '-7',
			'the_text'   => 'some text',
		];

		$this->assertSame( $expected, $result );
	}
}

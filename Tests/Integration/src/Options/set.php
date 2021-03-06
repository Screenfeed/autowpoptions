<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::set().
 *
 * @covers Options::set
 * @group  Options
 */
class Test_Set extends TestCase {

	public function testShouldSetOnlyValidValues() {
		$storage      = new Storage();
		$sanitization = new Sanitization();

		$result   = ( new Options( $storage, $sanitization ) )->set(
			[
				'test'       => [ '5' ],
				'the_number' => '2',
				'yolo'       => 1,
			]
		);
		$expected = [
			'the_array'  => [],
			'the_number' => '2',
			'the_text'   => 'some text',
		];

		$this->assertTrue( $result );
		$this->assertSame( $expected, $storage->values );
	}
}

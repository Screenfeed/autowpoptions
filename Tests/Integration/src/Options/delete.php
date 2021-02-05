<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitizer;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::delete().
 *
 * @covers Options::delete
 * @group  Options
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteOnlyValidValues() {
		$storage   = new Storage();
		$sanitizer = new Sanitizer();

		$result   = ( new Options( $storage, $sanitizer ) )->delete(
			[
				'test',
				'the_number',
			]
		);
		$expected = [
			'the_unknown' => [ '-7' ],
		];

		$this->assertTrue( $result );
		$this->assertSame( $expected, $storage->values );
	}

	public function testShouldDeleteOption() {
		$storage   = new Storage( [] );
		$sanitizer = new Sanitizer();

		$result = ( new Options( $storage, $sanitizer ) )->delete(
			[
				'test',
				'the_number',
			]
		);
		$expected = false;

		$this->assertTrue( $result );
		$this->assertSame( $expected, $storage->values );
	}

	public function testShouldNotDelete() {
		$storage   = new Storage( false );
		$sanitizer = new Sanitizer();

		$result = ( new Options( $storage, $sanitizer ) )->delete(
			[
				'test',
				'the_number',
			]
		);

		$this->assertFalse( $result );
	}
}

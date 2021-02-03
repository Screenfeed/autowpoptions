<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitization;
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
		$storage      = new Storage();
		$sanitization = new Sanitization();

		$result   = ( new Options( $storage, $sanitization ) )->delete(
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
		$storage      = new Storage( [] );
		$sanitization = new Sanitization();

		$result = ( new Options( $storage, $sanitization ) )->delete(
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
		$storage      = new Storage( false );
		$sanitization = new Sanitization();

		$result = ( new Options( $storage, $sanitization ) )->delete(
			[
				'test',
				'the_number',
			]
		);

		$this->assertFalse( $result );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitizer;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::has().
 *
 * @covers Options::has
 * @group  Options
 */
class Test_Has extends TestCase {

	public function testShouldReturnIfHasKey() {
		$storage   = new Storage();
		$sanitizer = new Sanitizer();
		$options   = new Options( $storage, $sanitizer );

		$this->assertFalse( $options->has( 22 ) );
		$this->assertFalse( $options->has( 'test' ) );
		$this->assertTrue( $options->has( 'the_array' ) );
	}
}

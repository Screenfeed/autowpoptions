<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use WP_Error;
use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitizer;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::get_errors().
 *
 * @covers Options::get_errors
 * @group  Options
 */
class Test_GetErrors extends TestCase {

	public function testShouldReturnWPErrorInstance() {
		$storage   = new Storage();
		$sanitizer = new Sanitizer();
		$errors    = ( new Options( $storage, $sanitizer ) )->get_errors();

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'fixture_error', $errors->get_error_code() );
	}
}

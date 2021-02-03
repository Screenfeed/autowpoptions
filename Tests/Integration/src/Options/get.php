<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Sanitization;
use Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options\Storage;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase;

/**
 * Tests for Options::get().
 *
 * @covers Options::get
 * @group  Options
 */
class Test_Get extends TestCase {

	public function testShouldReturnNullWhenNotStringOption() {
		$storage      = new Storage();
		$sanitization = new Sanitization();
		$result       = ( new Options( $storage, $sanitization ) )->get( 12 );

		$this->assertNull( $result );
	}

	public function testShouldReturnNullWhenUnkownOption() {
		$storage      = new Storage();
		$sanitization = new Sanitization();
		$result       = ( new Options( $storage, $sanitization ) )->get( 'test' );

		$this->assertNull( $result );
	}

	public function testShouldReturnPreFilteredValue() {
		$storage      = new Storage();
		$sanitization = new Sanitization();

		add_filter( 'pre_get_fixture_settings_the_number', [ $this, 'theNumberPreFilter' ], 10, 2 );

		$result = ( new Options( $storage, $sanitization ) )->get( 'the_number' );

		remove_filter( 'pre_get_fixture_settings_the_number', [ $this, 'theNumberPreFilter' ] );

		$this->assertSame( 42, $result );
	}

	public function testShouldReturnFilteredValue() {
		$storage      = new Storage();
		$sanitization = new Sanitization();

		add_filter( 'get_fixture_settings_the_number', [ $this, 'theNumberFilter' ], 10, 2 );

		$result = ( new Options( $storage, $sanitization ) )->get( 'the_number' );

		remove_filter( 'get_fixture_settings_the_number', [ $this, 'theNumberFilter' ] );

		$this->assertSame( 42, $result );
	}

	public function testShouldReturnValue() {
		$storage      = new Storage();
		$sanitization = new Sanitization();
		$result       = ( new Options( $storage, $sanitization ) )->get( 'the_number' );

		$this->assertSame( 7, $result );
	}

	public function theNumberPreFilter( $value, $default ) {
		$this->assertNull( $value );
		$this->assertSame( 0, $default );
		return 42;
	}

	public function theNumberFilter( $value, $default ) {
		$this->assertSame( 7, $value );
		$this->assertSame( 0, $default );
		return 42;
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Brain\Monkey\Filters;
use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::get().
 *
 * @covers Options::get
 * @group  Options
 */
class Test_Get extends TestCase {

	public function testShouldReturnNullWhenNotStringOption() {
		$storage      = $this->createMock( StorageInterface::class );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$result       = ( new Options( $storage, $sanitization ) )->get( 12 );

		$this->assertNull( $result );
	}

	public function testShouldReturnNullWhenUnkownOption() {
		$storage      = $this->createMock( StorageInterface::class );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$sanitization
			->expects( $this->once() )
			->method( 'get_default_values' )
			->willReturn( [ 'the_number' => 0 ] );
		$result       = ( new Options( $storage, $sanitization ) )->get( 'test' );

		$this->assertNull( $result );
	}

	public function testShouldReturnValue() {
		$storage      = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( [ 'the_number' => '-7' ] );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$sanitization
			->expects( $this->exactly( 2 ) )
			->method( 'get_default_values' )
			->willReturn( [ 'the_number' => 0 ] );
		$sanitization
			->expects( $this->never() )
			->method( 'get_reset_values' );
		$sanitization
			->expects( $this->once() )
			->method( 'get_prefix' )
			->willReturn( 'fixture' );
		$sanitization
			->expects( $this->once() )
			->method( 'get_identifier' )
			->willReturn( 'settings' );
		$sanitization
			->expects( $this->once() )
			->method( 'sanitize_and_validate' )
			->willReturnCallback(
				function ( $key, $value, $default ) {
					switch ( $key ) {
						case 'the_number':
							return absint( $value );

						default:
							return false;
					}
				}
			);

		$result = ( new Options( $storage, $sanitization ) )->get( 'the_number' );

		$this->assertSame( 7, $result );
	}
}

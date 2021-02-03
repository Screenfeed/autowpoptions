<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::get_all().
 *
 * @covers Options::get_all
 * @group  Options
 */
class Test_GetAll extends TestCase {

	public function testShouldReturnResetValues() {
		$storage = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( [] );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$sanitization
			->expects( $this->once() )
			->method( 'get_reset_values' )
			->willReturn( [ 'the_number' => 2 ] );

		$result = ( new Options( $storage, $sanitization ) )->get_all();

		$this->assertSame( [ 'the_number' => 2 ], $result );
	}

	public function testShouldReturnValues() {
		$storage = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( [ 'the_number' => '-7' ] );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$sanitization
			->expects( $this->never() )
			->method( 'get_reset_values' );
		$sanitization
			->expects( $this->once() )
			->method( 'get_default_values' )
			->willReturn( [ 'the_number' => 0 ] );

		$result = ( new Options( $storage, $sanitization ) )->get_all();

		$this->assertSame( [ 'the_number' => '-7' ], $result );
	}

	public function testShouldReturnOnlyValidValues() {
		$storage = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn(
				[
					'the_array'  => [ '-7' ],
					'the_number' => '-7',
				]
			);
		$sanitization = $this->createMock( SanitizationInterface::class );
		$sanitization
			->expects( $this->never() )
			->method( 'get_reset_values' );
		$sanitization
			->expects( $this->once() )
			->method( 'get_default_values' )
			->willReturn(
				[
					'test'       => [ 24 ],
					'the_number' => 0,
				]
			);

		$result   = ( new Options( $storage, $sanitization ) )->get_all();
		$expected = [
			'test'       => [ 24 ],
			'the_number' => '-7',
		];

		$this->assertSame( $expected, $result );
	}
}

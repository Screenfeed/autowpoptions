<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::delete().
 *
 * @covers Options::delete
 * @group  Options
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteOnlyValidValues() {
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
		$storage
			->expects( $this->never() )
			->method( 'delete' );
		$storage
			->expects( $this->once() )
			->method( 'set' )
			->with(
				[
					'the_number' => '-7',
				]
			)
			->willReturn( true );
		$sanitization = $this->createMock( SanitizationInterface::class );

		$result = ( new Options( $storage, $sanitization ) )->delete(
			[
				'the_array',
				'test',
			]
		);

		$this->assertTrue( $result );
	}

	public function testShouldDeleteOption() {
		$storage = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( [] );
		$storage
			->expects( $this->once() )
			->method( 'delete' )
			->willReturn( true );
		$storage
			->expects( $this->never() )
			->method( 'set' );
		$sanitization = $this->createMock( SanitizationInterface::class );

		$result = ( new Options( $storage, $sanitization ) )->delete(
			[
				'the_array',
				'test',
			]
		);

		$this->assertTrue( $result );
	}

	public function testShouldNotDelete() {
		$storage = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get' )
			->willReturn( false );
		$storage
			->expects( $this->never() )
			->method( 'delete' );
		$storage
			->expects( $this->never() )
			->method( 'set' );
		$sanitization = $this->createMock( SanitizationInterface::class );

		$result = ( new Options( $storage, $sanitization ) )->delete(
			[
				'the_array',
				'test',
			]
		);

		$this->assertFalse( $result );
	}
}

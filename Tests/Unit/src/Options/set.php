<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::set().
 *
 * @covers Options::set
 * @group  Options
 */
class Test_Set extends TestCase {

	public function testShouldSetOnlyValidValues() {
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
			->expects( $this->once() )
			->method( 'set' )
			->with(
				[
					'test'       => [ 24 ],
					'the_number' => 2,
				]
			)
			->willReturn( true );
		$sanitization = $this->createMock( SanitizationInterface::class );
		$sanitization
			->expects( $this->exactly( 2 ) )
			->method( 'get_default_values' )
			->willReturn(
				[
					'test'       => [ 24 ],
					'the_number' => 0,
				]
			);

		$result = ( new Options( $storage, $sanitization ) )->set(
			[
				'the_array'  => [ '5' ],
				'the_number' => '2',
				'yolo'       => 1,
			]
		);

		$this->assertTrue( $result );
	}
}

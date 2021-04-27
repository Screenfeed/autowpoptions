<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Options;

use WP_Error;
use Screenfeed\AutoWPOptions\Options;
use Screenfeed\AutoWPOptions\Sanitization\SanitizationInterface;
use Screenfeed\AutoWPOptions\Storage\StorageInterface;
use Screenfeed\AutoWPOptions\Tests\Unit\TestCase;

/**
 * Tests for Options::get_errors().
 *
 * @covers Options::get_errors
 * @group  Options
 */
class Test_GetErrors extends TestCase {

	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass() {
		require_once ABSPATH . 'wp-includes/class-wp-error.php';
	}

	public function testShouldReturnWPErrorInstance() {
		$storage   = $this->createMock( StorageInterface::class );
		$storage
			->expects( $this->once() )
			->method( 'get_errors' )
			->willReturn( new WP_Error() );
		$sanitizer = $this->createMock( SanitizationInterface::class );
		$errors    = ( new Options( $storage, $sanitizer ) )->get_errors();

		$this->assertInstanceOf( WP_Error::class, $errors );
	}
}

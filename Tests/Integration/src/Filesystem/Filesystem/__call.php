<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

use Error;
use Screenfeed\AutoWPOptions\Filesystem\Filesystem;

/**
 * Tests for Filesystem::__call().
 *
 * @covers Filesystem::__call
 * @group  Filesystem
 */
class Test___Call extends TestCase {

	public function testShouldCallFilesystemMethodWhenAllowed() {
		$this->filesystem_init( [ 'test.txt' => 'Some text.' ] );

		$filesystem = new Filesystem();
		$file_path  = $this->get_file_path( 'test.txt' );

		$this->assertFalse( method_exists( $filesystem, 'get_contents' ) );

		$result = $filesystem->get_contents( $file_path );

		$this->assertSame( 'Some text.', $result );
	}

	public function testShouldNotCallFilesystemMethodWhenNotAllowed() {
		$filesystem = new Filesystem();

		$this->assertFalse( method_exists( $filesystem, 'connect' ) );
		$this->expectException( Error::class );
		$this->expectExceptionMessage( 'Call to undefined method connect' );

		$filesystem->connect();
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Filesystem\FilesystemInterface;
use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::set().
 *
 * @covers ConfigFile::set
 * @group  ConfigFile
 */
class Test_Set extends TestCase {

	public function testShouldReturnFalseWhenTargetIsNotAFile() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [ $file_name => [] ] );

		$this->assertDirectoryExists( $this->get_file_path( true, 3, 1 ) );

		$success = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->set( [ 'foo' => 'bar' ] );

		$this->assertFalse( $success );
	}

	public function testShouldReturnFalseWhenCantCreateFolder() {
		$this->filesystem_init( [] );

		$this->assertFileNotExists( $this->get_file_path( true, 3, 1 ) );

		// Path traversal is not allowed.
		$success = ( new ConfigFile( $this->get_raw_file_path( '..' ), true, [ 'network_id' => 3 ] ) )->set( [ 'foo' => 'bar' ] );

		$this->assertFalse( $success );
	}

	public function testShouldMergeOldAndNewValues() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [ $file_name => "<?php\nreturn [ 'old' => 2 ];\n" ] );

		$file_path = $this->get_file_path( true, 3, 1 );

		$this->assertFileExists( $file_path );

		$success = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->set( [ 'foo' => 'bar' ] );

		$this->assertTrue( $success );
		$this->assertFileExists( $file_path );

		$values   = include $file_path;
		$expected = [
			'old' => 2,
			'foo' => 'bar',
		];

		$this->assertSame( $expected, $values );
	}

	public function testShouldSetNewValues() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [] );

		$file_path = $this->get_file_path( true, 3, 1 );

		$this->assertFileNotExists( $file_path );

		$success = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->set( [ 'foo' => 'bar' ] );

		$this->assertTrue( $success );
		$this->assertFileExists( $file_path );

		$values   = include $file_path;
		$expected = [ 'foo' => 'bar' ];

		$this->assertSame( $expected, $values );
	}
}

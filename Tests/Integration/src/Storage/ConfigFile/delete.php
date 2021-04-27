<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::delete().
 *
 * @covers ConfigFile::delete
 * @group  ConfigFile
 */
class Test_Delete extends TestCase {

	public function testShouldDeleteFileWhenFileExists() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [ $file_name => "<?php\nreturn [ 'old' => 2 ];\n" ] );

		$file_path   = $this->get_file_path( true, 3, 1 );
		$config_file = new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] );

		$this->assertFileExists( $file_path );
		$this->assertTrue( $config_file->delete() );
		$this->assertFileNotExists( $file_path );
	}

	public function testShouldReturnFalseWhenFileDoesNotExist() {
		$this->filesystem_init( [] );

		$file_path   = $this->get_file_path( true, 3, 1 );
		$config_file = new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] );

		$this->assertFileNotExists( $file_path );
		$this->assertFalse( $config_file->delete() );
	}

	public function testShouldReturnFalseWhenDeletionFails() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [ $file_name => [] ] );

		$file_path  = $this->get_file_path( true, 3, 1 );
		$config_file = new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] );

		$this->assertDirectoryExists( $file_path );
		$this->assertFalse( $config_file->delete() );
		$this->assertDirectoryExists( $file_path );
	}
}

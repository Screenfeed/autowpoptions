<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::get().
 *
 * @covers ConfigFile::get
 * @group  ConfigFile
 */
class Test_Get extends TestCase {

	public function testShouldReturnFalseWhenTargetDoesNotExist() {
		$this->filesystem_init();

		$this->assertFileNotExists( $this->get_file_path( true, 3, 1 ) );
		$this->expectOutputString( '' );

		$values = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->get();

		$this->assertFalse( $values );
	}

	public function testShouldReturnFalseWhenTargetIsNotAFile() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [ $file_name => [] ] );

		$this->assertDirectoryExists( $this->get_file_path( true, 3, 1 ) );
		$this->expectOutputString( '' );

		$values = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->get();

		$this->assertFalse( $values );
	}

	public function testShouldReturnAnEmptyArrayWhenValuesAreNotAnArray() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [ $file_name => "<?php\nreturn 'test';\n?>\n\n" ] );

		$this->assertFileExists( $this->get_file_path( true, 3, 1 ) );
		$this->expectOutputString( '' );

		$values = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->get();

		$this->assertSame( [], $values );
	}

	public function testShouldReturnValuesWhenTargetExistsAndIsAFile() {
		$file_name = $this->get_file_name( true, 3, 1 );

		$this->filesystem_init( [ $file_name => "<?php\necho 'fooo';\nreturn [ 'foo' => 'test' ];\n" ] );

		$this->assertFileExists( $this->get_file_path( true, 3, 1 ) );
		$this->expectOutputString( '' );

		$values = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->get();

		$this->assertSame( [ 'foo' => 'test' ], $values );
	}
}

<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::get_full_name().
 *
 * @covers ConfigFile::get_full_name
 * @group  ConfigFile
 */
class Test_GetFullName extends TestCase {

	public function testShouldReturnAFormattedPath() {
		$this->filesystem_init();

		// With provided network ID.
		$file_path = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->get_full_name();
		$expected  = $this->get_file_path( true, 3, 1 );

		$this->assertSame( $expected, $file_path );

		// With automatic network/blog IDs.
		$file_path = ( new ConfigFile( $this->get_raw_file_path(), false ) )->get_full_name();
		$expected  = $this->get_file_path( false, get_current_network_id(), get_current_blog_id() );

		$this->assertSame( $expected, $file_path );
	}
}

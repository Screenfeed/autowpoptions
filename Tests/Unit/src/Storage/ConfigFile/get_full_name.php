<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\ConfigFile;

use Brain\Monkey\Functions;
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
		$expected  = $this->get_file_path( true, 3, 1 );//ABSPATH . 'myplugin-settings-3-network.php';

		$this->assertSame( $expected, $file_path );

		// With automatic network/blog IDs.
		Functions\expect( 'get_current_network_id' )
			->once()
			->withNoArgs()
			->andReturn( 2 );

		Functions\expect( 'get_current_blog_id' )
			->once()
			->withNoArgs()
			->andReturn( 6 );

		$file_path = ( new ConfigFile( $this->get_raw_file_path(), false ) )->get_full_name();
		$expected  = $this->get_file_path( false, 2, 6 );

		$this->assertSame( $expected, $file_path );
	}
}

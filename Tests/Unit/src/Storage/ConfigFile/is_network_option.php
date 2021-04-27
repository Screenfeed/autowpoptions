<?php

namespace Screenfeed\AutoWPOptions\Tests\Unit\src\Storage\ConfigFile;

use Brain\Monkey\Functions;
use Screenfeed\AutoWPOptions\Storage\ConfigFile;

/**
 * Tests for ConfigFile::is_network_option().
 *
 * @covers ConfigFile::is_network_option
 * @group  ConfigFile
 */
class Test_IsNetworkOption extends TestCase {

	public function testShouldReturnIfNetworkOption() {
		$this->filesystem_init();

		$is_network_option = ( new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] ) )->is_network_option();

		$this->assertTrue( $is_network_option );

		Functions\expect( 'get_current_blog_id' )
			->once()
			->withNoArgs()
			->andReturn( 6 );

		$is_network_option = ( new ConfigFile( $this->get_raw_file_path(), false, [ 'network_id' => 3 ] ) )->is_network_option();

		$this->assertFalse( $is_network_option );
	}
}

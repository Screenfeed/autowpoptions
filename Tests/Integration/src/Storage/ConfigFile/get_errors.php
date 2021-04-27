<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use Screenfeed\AutoWPOptions\Storage\ConfigFile;
use WP_Error;

/**
 * Tests for ConfigFile::get_errors().
 *
 * @covers ConfigFile::get_errors
 * @group  ConfigFile
 */
class Test_GetErrors extends TestCase {

	public function testShouldReturnErrors() {
		global $wp_filesystem;

		$this->filesystem_init( [] );

		$config_file = new ConfigFile( $this->get_raw_file_path(), true, [ 'network_id' => 3 ] );
		$wp_filesystem->errors->add( 'test-error', 'Test Error' );

		$errors = $config_file->get_errors();

		$this->assertInstanceOf( WP_Error::class, $errors );
		$this->assertSame( 'test-error', $errors->get_error_code() );
	}
}

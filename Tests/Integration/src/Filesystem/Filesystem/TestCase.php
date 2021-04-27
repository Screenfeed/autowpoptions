<?php
/**
 * Test Case for the `Filesystem` integration tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Integration
 */

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Filesystem\Filesystem;

use org\bovigo\vfs\vfsStream;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase as BaseIntegrationTestCase;

abstract class TestCase extends BaseIntegrationTestCase {
	/**
	 * @var vfsStreamDirectory
	 */
	protected $fs_root;

	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass() {
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		require_once ABSPATH . 'wp-includes/class-wp-error.php';
	}

	protected function filesystem_init( $filesystem_structure = [] ) {
		if ( is_array( $filesystem_structure ) ) {
			$this->fs_root = vfsStream::setup( 'root', 0755, $filesystem_structure );
		}
	}

	protected function get_file_path( $file_name ) {
		return $this->fs_root->url() . '/' . $file_name;
	}
}

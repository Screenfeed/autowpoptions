<?php
/**
 * Test Case for the `ConfigFile` integration tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Integration
 */

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\ConfigFile;

use org\bovigo\vfs\vfsStream;
use Screenfeed\AutoWPOptions\Tests\FilesystemTestCaseTrait;
use Screenfeed\AutoWPOptions\Tests\Integration\TestCase as BaseIntegrationTestCase;

abstract class TestCase extends BaseIntegrationTestCase {
	use FilesystemTestCaseTrait;

	protected function filesystem_init( $filesystem_structure = [] ) {
		$this->fs_root = vfsStream::setup( trim( ABSPATH, '/\\' ), 0755, $filesystem_structure );
	}
}

<?php
/**
 * Test Case for the `WpOption` integration tests.
 *
 * @package Screenfeed\AutoWPOptions\Tests\Integration
 */

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\WpOption;

use Screenfeed\AutoWPOptions\Tests\Integration\TestCase as BaseIntegrationTestCase;

abstract class TestCase extends BaseIntegrationTestCase {
	protected $option_name = 'autowpoptions_tests_settings';
	protected $network_id  = 4;

	/**
	 * Prepares the test environment before each test.
	 */
	public function setUp(): void {
		parent::setUp();

		if ( false !== get_option( $this->option_name ) ) {
			delete_option( $this->option_name );
		}
		if ( false !== get_network_option( $this->network_id, $this->option_name ) ) {
			delete_network_option( $this->network_id, $this->option_name );
		}
	}

	/**
	 * Cleans up the test environment after each test.
	 */
	public function tearDown(): void {
		if ( false !== get_option( $this->option_name ) ) {
			delete_option( $this->option_name );
		}
		if ( false !== get_network_option( $this->network_id, $this->option_name ) ) {
			delete_network_option( $this->network_id, $this->option_name );
		}

		parent::tearDown();
	}
}

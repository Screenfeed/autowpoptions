<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration\src\Storage\LazyStorage;

use WP_Error;
use Screenfeed\AutoWPOptions\Storage\LazyStorage;
use Screenfeed\AutoWPOptions\Storage\WpOption;

/**
 * Tests for LazyStorage::get_errors().
 *
 * @covers LazyStorage::get_errors
 * @group  LazyStorage
 */
class Test_GetErrors extends TestCase {

	public function testShouldReturnWPErrorInstance() {
		$storage = new WpOption( $this->option_name, false );

		$storage->get_errors()->add( 'fixture_error', 'Fixture error.' );

		$errors = ( new LazyStorage( $storage ) )->get_errors();

		$this->assertInstanceOf( WP_Error::class, $errors );

		$this->assertSame( 'fixture_error', $errors->get_error_code() );
	}
}

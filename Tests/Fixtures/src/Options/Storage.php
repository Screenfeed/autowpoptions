<?php

namespace Screenfeed\AutoWPOptions\Tests\Fixtures\src\Options;

use Screenfeed\AutoWPOptions\Storage\StorageInterface;

class Storage implements StorageInterface {
	public $values;
	public $network_id;

	public function __construct( $values = null ) {
		if ( ! isset( $values ) ) {
			$values = [
				'the_unknown' => [ '-7' ],
				'the_number'  => '-7',
			];
		}
		$this->values     = $values;
		$this->network_id = ! empty( $args['network_id'] ) && is_numeric( $args['network_id'] ) ? absint( $args['network_id'] ) : get_current_network_id();
	}

	public function get_type() {
		return 'fixture_type';
	}

	public function get_full_name() {
		return 'fixture_settings';
	}

	public function get_network_id() {
		return $this->network_id;
	}

	public function is_network_option() {
		return false;
	}

	public function get() {
		return $this->values;
	}

	public function set( array $values ) {
		$this->values = (array) sanitize_option( $this->get_full_name(), $values );
		return true;
	}

	public function delete() {
		$this->values = false;
		return true;
	}
}

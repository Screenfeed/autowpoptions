<?php

class WP_Filesystem_AWPO_Fixture_Error extends WP_Filesystem_Base {
	private $error;

	public function __construct( $arg ) {
		$this->method = 'AWPO_Fixture_Error';
		$this->error  = ! empty( $arg['error'] ) ? $arg['error'] : false;

		if ( 'wp_error' === $this->error ) {
			$this->errors = new WP_Error( 'test-error', 'Test error' );
		} else {
			$this->errors = new WP_Error();
		}
	}

	public function connect() {
		if ( 'sandbox' === $this->error ) {
			trigger_error( 'Internal Error', E_USER_WARNING );
		}
		return 'connect' !== $this->error;
	}
}

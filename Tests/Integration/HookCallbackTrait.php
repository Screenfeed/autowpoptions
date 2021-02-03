<?php

namespace Screenfeed\AutoWPOptions\Tests\Integration;

trait HookCallbackTrait {

	/**
	 * Asserts that a hook callback has been registered.
	 * Examples:
	 * $this->assertHookCallbackRegistered( 'admin_init', 'my_func', 50 );
	 * $this->assertHookCallbackRegistered( 'admin_init', [ $instance, 'my_method' ], 20 );
	 * $this->assertHookCallbackRegistered( 'admin_init', [ MyClass::class, 'my_method' ], 5 );
	 * This last example will also work for an instance method (by the use of `assertInstanceOf()`).
	 *
	 * @param  string   $hook_name Name of the hook.
	 * @param  callable $callable  Can be a function name, an anonymous function,
	 *                             an array "instance object + method name", an array "class name + method name".
	 * @param  int      $priority  Hook priority.
	 * @return void
	 */
	protected function assertHookCallbackRegistered( $hook_name, $callable, $priority = 10 ) {
		global $wp_filter;

		$this->assertTrue( has_action( $hook_name ) );

		$callbacks    = $wp_filter[ $hook_name ]->callbacks;
		$registration = current( $callbacks[ $priority ] );

		if ( is_array( $callable ) ) {
			$this->assertInternalType( 'array', $registration['function'] );

			if ( is_string( $callable[0] ) && is_object( $registration['function'][0] ) ) {
				$this->assertInstanceOf( $callable[0], $registration['function'][0] );
				$this->assertEquals( $callable[1], $registration['function'][1] );
			} else {
				$this->assertEquals( $callable, $registration['function'] );
			}
		} else {
			$this->assertEquals( $callable, $registration['function'] );
		}
	}
}

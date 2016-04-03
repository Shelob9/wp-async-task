<?php
/**
 * Hook up task to callback
 *
 * @package   shelob9\async
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 Josh Pollock
 */

namespace shelob9\async;


class system {

	/**
	 * Args for hook
	 *
	 * @var array
	 */
	protected $args;

	/**
	 * @param \shelob9\async\task $task Task object
	 * @param string|array $async_callback Function to call asynchronously
	 * @param array $args Priority & number of args for callback
	 */
	public function __construct( task $task, $aync_callback = false, array $args = [ 20, 1 ] ){
		$this->set_args( $args );
		if( is_callable( [ $task, 'callback' ] ) ){
			$this->set_action( $task->get_action(), [ $task, 'callback' ], $args );
		}else{
			$this->set_action( $task->get_action(), $aync_callback, $args );
		}

	}

	private function set_args( array $args ){
		if( ! isset( $args[0]) || ! is_numeric( $args[0]) ){
			$this->args[0] = 20;
		}else{
			$this->args[0] = (int) $args[0];
		}

		if( ! isset( $args[1]) || ! is_numeric( $args[1]) ){
			$this->args[1] = 1;
		}else{
			$this->args[1] = (int) $args[1];
		}
	}

	/**
	 * @param string $action Action name
	 * @param string|array $async_callback Function to call asynchronously
	 */
	protected function set_action( $action, $async_callback ){
		add_action( 'wp_async_' . $action, $async_callback, $this->args[0], $this->args[1] );
	}

}

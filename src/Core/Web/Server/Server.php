<?php

namespace Fox\Core\Web\Server;


class Server {

	/**
	 * @type array Contain SUPERGLOBAL $_SERVER variable
	 */
	private $serverGlobal;

	/**
	 * Server constructor.
	 */
	public function __construct() {
		$this->serverGlobal = $_SERVER;
	}

	/**
	 * Get an interface variable
	 *
	 * @param $interface
	 *
	 * @return mixed
	 */
	public function get($interface) {
		return $this->serverGlobal[$interface];
	}

	/**
	 * Edit an interface variable
	 *
	 * @param $interface
	 * @param $value
	 */
	public function set($interface, $value) {
		$this->serverGlobal[$interface] = $value;
	}

	/**
	 * Get the SUPERGLOBAL variable
	 *
	 * @return array
	 */
	public function getServer() {
		return $this->serverGlobal;
	}

}
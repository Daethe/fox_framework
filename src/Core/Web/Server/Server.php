<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 19/02/2017
 * Time: 16:50
 */

namespace Core\Web\Server;


class Server {

	private $serverGlobal;

	public function __construct() {
		$this->serverGlobal = $_SERVER;
	}

	public function get($interface) {
		return $this->serverGlobal[$interface];
	}

	public function set($interface, $value) {
		$this->serverGlobal[$interface] = $value;
	}

	public function getServer() {
		return $this->serverGlobal;
	}

}
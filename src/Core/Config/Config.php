<?php

namespace Core\Config;


class Config {

	private $config = [];
	
	public function __construct() {
		$this->config = require(ROOT . '/Config/config.php');
	}
	
	public function get($key) {
		try {
			return $this->config[$key];
		} catch(Exception $e) {
			throw new InvalidKeyException();
		}
	}

}
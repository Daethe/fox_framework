<?php
/**
 * Store all the application configuration
 *
 * @link http://www.foxframework.com/
 * @copyright Copyright (c) 2017 Daethe
 * @license http://www.foxframework.com/license/
 */

namespace Core\Config;

/**
 * Class Config
 * @package Core\Config
 */
class Config {

	/**
	 * Configuration array. Contains all the configuration loaded from file
	 * @type array|mixed
	 */
	private $config = [];

	/**
	 * Initialize all the configuration
	 */
	public function __construct() {
		$this->config = require(ROOT . '/Config/config.php');
	}

	/**
	 * Return the configuration from config array
	 * @param $key Wanted key from array
	 *
	 * @return mixed
	 * @throws \Core\Config\InvalidKeyException
	 */
	public function get($key) {
		try {
			return $this->config[$key];
		} catch(Exception $e) {
			throw new InvalidKeyException();
		}
	}

}
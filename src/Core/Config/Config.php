<?php
/**
 * Store all the application configuration
 *
 * @link http://www.foxframework.com/
 * @copyright Copyright (c) 2017 Daethe
 * @license http://www.foxframework.com/license/
 */

namespace Core\Config;
use Core\Exception\InvalidKeyException;
use Core\Exception\NotAnArrayException;

/**
 * Class Config
 * @package Core\Config
 */
class Config {

	/**
	 * Configuration array. Contains all the configuration loaded from file
	 * @type array|mixed
	 */
	protected $config = [];

	/**
	 * Initialize all the configuration
	 */
	public function __construct() {
		$this->config = require(ROOT . '/Config/config.php');
	}

	/**
	 * Return the configuration from config array
	 *
	 * @param $key Wanted key from array
	 *
	 * @return mixed
	 * @throws \Core\Exception\InvalidKeyException
	 */
	public function get($key) {
		if (!isset($this->config[$key])) {
			throw new InvalidKeyException('Invalid key');
		}
		return $this->config[$key];
	}

	/**
	 * @param $key
	 * @param $value
	 *
	 * @throws \Core\Exception\InvalidKeyException
	 */
	public function set($key, $value) {
		if (!isset($this->config[$key])) {
			throw new InvalidKeyException('Invalid key');
		}
		if (is_array($this->config[$key]) && is_array($value)) {
			foreach ($value as $k => $string) {
				$this->config[$key][$k] = $string;
			}
		} elseif (is_array($this->config[$key]) && !is_array($value) || !is_array($this->config[$key]) && is_array($value)) {
			throw new NotAnArrayException('You need to send an array');
		} else {
			$this->config[$key] = $value;
		}
		return true;
	}

}
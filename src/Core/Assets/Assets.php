<?php

namespace Core\Assets;

use Core\Exception\InvalidKeyException;
use Core\Exception\UnknownDirException;
use Core\Exception\NotAStringException;

class Assets {

	/**
	 * @type string Path for current files
	 */
	public $_path;

	/**
	 * @type array Registered files
	 */
	public $_files  = [];

	/**
	 * @type array Registered plains value
	 */
	public $_plains = [];

	/**
	 * Construct the assets with path
	 *
	 * @param string $path Path where is stored file
	 *
	 * @throws \Core\Exception\NotAStringException
	 * @throws \Core\Exception\UnknownDirException
	 */
	public function __construct($path = '') {
		if (is_string($path)) {
			if (is_dir($path)) {
				$this->_path = $path;
			} else {
				throw new UnknownDirException();
			}
		} else {
			throw new NotAStringException();
		}
	}

	/**
	 * Register assets file to respective type [css, js]
	 *
	 * @param string $index Index of the array for the file
	 * @param string $file File to register
	 */
	public function register($index = '', $file = '') {
		$this->registers([$index => $file]);
	}

	/**
	 * Register plain assets to respective type [css, js]
	 *
	 * @param string $index Index of the array for the file
	 * @param string $plain Plain value to register
	 */
	public function registerPlain($index = '', $plain = '') {
		$this->registersPlain([$index => $plain]);
	}

	/**
	 * Register multiple assets file to respective type [css, js]
	 *
	 * @param array $files Associative array for registering
	 */
	public function registers($files = []) {
		$this->registration('_files', $files);
	}

	/**
	 * Register plain assets to respective type [css, js]
	 *
	 * @param array $plains Associative array for registering
	 */
	public function registersPlain($plains = []) {
		$this->registration('_plains', $plains);
	}

	/**
	 * Check if array is associative
	 *
	 * @param array $values
	 *
	 * @return bool
	 */
	private function hasStringKeys($values = []) {
		return count(array_filter(array_keys($values), 'is_string')) > 0;
	}

	/**
	 * Register the file or plain content
	 *
	 * @param       $variable
	 * @param array $content
	 *
	 * @throws \Core\Exception\InvalidKeyException
	 */
	private function registration($variable, $content = []) {
		if ($this->hasStringKeys($content)) {
			$this->{$variable} += $content;
		} else {
			throw new InvalidKeyException();
		}
	}

}
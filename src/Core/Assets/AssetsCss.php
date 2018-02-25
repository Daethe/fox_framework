<?php

namespace Fox\Core\Assets;

use Fox\Core\Exception\InvalidKeyException;
use Fox\Core\Web\Url;

class AssetsCSS extends Assets {

	/**
	 * Dump all the files and plains for html use
	 * @return string Generated output
	 */
	public function dump() {
		$dom = '';
		if (!empty($this->_files)) {
			foreach ($this->_files as $filename) {
				$dom .= '<link rel="stylesheet" href="' . Url::To('assets', ['file' => $this->_path . $filename]) . '">';
			}
		}
		if (!empty($this->_plains)) {
			$dom .= '<style>';
			foreach ($this->_plains as $content) {
				$dom .= $content;
			}
			$dom .= '</style>';
		}

		return $dom;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 * @throws \Fox\Core\Exception\InvalidKeyException
	 */
	public function dumpOneFile($key = 'default') {
		if (array_key_exists($key, $this->_files)) {
			return '<link rel="stylesheet" href="' . Url::To('assets', ['file' => $this->_path . $this->_files[$key]]) . '">';
		} else {
			throw new InvalidKeyException("Key '$key' doesn't exists in configuration array");
		}
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 * @throws \Fox\Core\Exception\InvalidKeyException
	 */
	public function dumpOnePlain($key = 'default') {
		if (array_key_exists($key, $this->_plains)) {
			return '<style>' . $this->_plains[$key] . '</style>';
		} else {
			throw new InvalidKeyException("Key '$key' doesn't exists in configuration array");
		}
	}

}
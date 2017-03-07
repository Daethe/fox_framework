<?php

namespace Core\Assets;

use Core\Exception\NotAnAssociativeArrayKeyException;
use Core\Web\Url;

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
	 * @throws \Core\Exception\NotAnAssociativeArrayKeyException
	 */
	public function dumpOneFile($key = 'default') {
		if (array_key_exists($key, $this->_files)) {
			return '<link rel="stylesheet" href="' . Url::To('assets', ['file' => $this->_path . $this->_files[$key]]) . '">';
		} else {
			throw new NotAnAssociativeArrayKeyException();
		}
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 * @throws \Core\Exception\NotAnAssociativeArrayKeyException
	 */
	public function dumpOnePlain($key = 'default') {
		if (array_key_exists($key, $this->_plains)) {
			return '<style>' . $this->_plains[$key] . '</style>';
		} else {
			throw new NotAnAssociativeArrayKeyException();
		}
	}

}
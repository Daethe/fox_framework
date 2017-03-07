<?php

namespace Core\Assets;

use Core\Exception\NotAnAssociativeArrayKeyException;
use Core\Web\Url;

class AssetsJS extends Assets {

	/**
	 * Dump all the files and plains for html use
	 * @return string Generated output
	 */
	public function dump() {
		$dom = '';
		if (!empty($this->_files)) {
			foreach ($this->_files as $filename) {
				$dom .= '<script src="' . Url::To('assets', ['file' => $this->_path . $filename]) . '"></script>';
			}
		}
		if (!empty($this->_plains)) {
			$dom .= '<script>';
			foreach ($this->_plains as $content) {
				$dom .= $content;
			}
			$dom .= '</script>';
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
			return '<script src="' . Url::To('assets', ['file' => $this->_path . $this->_files[$key]]) . '"></script>';
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
			return '<script>' . $this->_plains[$key] . '</script>';
		} else {
			throw new NotAnAssociativeArrayKeyException();
		}
	}

}
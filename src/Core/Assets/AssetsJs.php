<?php
/**
 * Created by PhpStorm.
 * User: marcp
 * Date: 18/02/2017
 * Time: 16:48
 */

namespace Core\Assets;

use \Core\Web\Url;

/**
 * Class AssetsJs
 * @package Core\Assets
 */
class AssetsJs implements AssetsInterface {

	/**
	 * @type array Registered JS file
	 */
	private $file = [];

	/**
	 * @type array Registered plain JS
	 */
	private $plain = [];

	/**
	 * AssetsJs constructor.
	 */
	public function __construct() {
	}

	/**
	 * Register a file to the assets
	 *
	 * @param string $file File to register
	 */
	public function registerFile($file) {
		$this->file = array_merge($this->file, [$file]);
	}

	public function registerPlain($plain) {
		$this->plain = array_merge($this->plain, [$plain]);
	}

	/**
	 * Dump all the files for html use
	 * @return string Generated output
	 */
	public function dump() {
		$dom = '';
		if (!empty($this->file)) {
			foreach ($this->file as $filename) {
				$dom .= '<script src="' . Url::To('assets', ['file' => Assets::$assetsPath . 'js/' . $filename]) . '"></script>';
			}
		}
		if (!empty($this->plain)) {
			$dom .= '<script>';
			foreach ($this->plain as $content) {
				$dom .= $content;
			}
			$dom .= '</script>';
		}

		return $dom;
	}

}
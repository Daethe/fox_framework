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
 * Class AssetsCss
 * @package Core\Assets
 */
class AssetsCss implements AssetsInterface {

	/**
	 * @type array Registered CSS file
	 */
	private $file = [];

	/**
	 * @type array Registered plain CSS
	 */
	private $plain = [];

	/**
	 * AssetsCss constructor.
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
				$dom .= '<link rel="stylesheet" href="' . Url::To('assets', ['file' => Assets::$assetsPath . 'css/' . $filename]) . '">';
			}
		}
		if (!empty($this->plain)) {
			$dom .= '<style>';
			foreach ($this->plain as $content) {
				$dom .= $content;
			}
			$dom .= '</style>';
		}

		return $dom;
	}

}
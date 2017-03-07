<?php

namespace Core\Assets;

class AssetsManager {

	const CT_CSS_PATH = 'App/Assets/stylesheet/';
	const CT_JS_PATH = 'App/Assets/script/';

	private $_cssInstance;
	private $_jsInstance;

	/**
	 * AssetsManager constructor.
	 *
	 * @param string $cssPath
	 * @param string $jsPath
	 */
	public function __construct($cssPath = self::CT_CSS_PATH, $jsPath = self::CT_JS_PATH) {
		$this->_cssInstance = new AssetsCSS($cssPath);
		$this->_jsInstance = new AssetsJS($jsPath);
	}

	/**
	 * Get instance of AssetsCSS
	 *
	 * @return \Core\Assets\AssetsCss
	 */
	public function CSS() {
		return $this->_cssInstance;
	}

	/**
	 * Get instance of AssetsJS
	 *
	 * @return \Core\Assets\AssetsJS
	 */
	public function JS() {
		return $this->_jsInstance;
	}

}
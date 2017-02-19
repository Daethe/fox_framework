<?php
/**
 * @link http://www.foxframework.com/
 * @copyright Copyright (c) 2017 Daethe
 * @license http://www.foxframework.com/license/
 */

namespace Core\Assets;

/**
 * Class Assets
 * @package Core\Assets
 */
class Assets {

	/**
	 * @var string Application assets path
	 */
	public static $assetsPath = 'App/Assets/';

	/**
	 * @type \Core\Assets\AssetsCss CSS Assets instance
	 */
	private $cssInstance;

	/**
	 * @type \Core\Assets\AssetsJs JS Assets instance
	 */
	private $jsInstance;

	/**
	 * Assets constructor
	 */
	public function __construct() {
		$this->cssInstance = new AssetsCss();
		$this->jsInstance = new AssetsJs();
	}

	/**
	 * Dump as a DOM all listed file
	 * Plain value listed at the end of dom
	 *
	 * @param $type
	 *
	 * @return string
	 */
    public function dump($type = '') {
        $type = strtolower($type);
        if ($type === 'css' || $type === 'js') {
			return $this->{$type . 'Instance'}->dump();
        }
		return null;
    }

	/**
	 * Register an asset file
	 * @param $type
	 * @param $file
	 */
    public function registerFile($type, $file) {
        $type = strtolower($type);
		if ($type === 'css' || $type === 'js') {
			$this->{$type . 'Instance'}->registerFile($file);
			return true;
        }
		return false;
	}

	// ----------------------- //
	// -- CSS  REGISTRATION -- //
	// ----------------------- //

	/**
	 * Register a CSS file
	 * @param $cssFile
	 */
    public function registerCssFile($cssFile) {
		$this->cssInstance->registerFile($cssFile);
	}

	/**
	 * Register a plain CSS
	 *
	 * @param $css
	 */
	public function registerCss($css) {
		$this->cssInstance->registerPlain($css);
	}

	// ----------------------- //
	// --- JS REGISTRATION --- //
	// ----------------------- //

	/**
	 * Register a JS file
	 * @param $jsFile
	 */
    public function registerJsFile($jsFile) {
		$this->jsInstance->registerFile($jsFile);
    }

	/**
	 * Register a plain JS
	 *
	 * @param $js
	 */
	public function registerJs($js) {
		$this->jsInstance->registerPlain($js);
	}

}
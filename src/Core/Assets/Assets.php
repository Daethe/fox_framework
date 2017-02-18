<?php
/**
 * @link http://www.foxframework.com/
 * @copyright Copyright (c) 2017 Daethe
 * @license http://www.foxframework.com/license/
 */

namespace Core\Assets;

use \Core\Web\Url;

/**
 * Class Assets
 * @package Core\Assets
 */
class Assets {

	/**
	 * @var string Application assets path
	 */
    public $assetsPath = 'App/Assets/';

	/**
	 * @var array CSS file used in application
	 */
    protected $css = [];

	/**
	 * @var array JS file used in application
	 */
    protected $js = [];

	/**
	 * Assets constructor
	 */
    public function __construct() {}

	/**
	 * Dump as a DOM all listed file
	 * @param $type
	 * @return string
	 */
    public function dump($type) {
        $type = strtolower($type);
        if ($type === 'css' || $type === 'js') {
            return $this->dumpList($type, $this->{$type});
        }
    }

	/**
	 * Register an asset file
	 * @param $type
	 * @param $file
	 */
    public function registerFile($type, $file) {
        $type = strtolower($type);
        if ($type === 'css') {
            $this->registerCssFile($file);
        } elseif ($type === 'js') {
            $this->registerJsFile($file);
        }
    }

	/**
	 * Register a CSS file
	 * @param $cssFile
	 */
    public function registerCssFile($cssFile) {
        $this->css = array_merge($this->css, $this->checkArray($cssFile));
    }

	/**
	 * Register a JS file
	 * @param $jsFile
	 */
    public function registerJsFile($jsFile) {
        $this->js = array_merge($this->js, $this->checkArray($jsFile));
    }

	/**
	 * Check if the parameters is an array
	 * @param $value
	 * @return array
	 */
    private function checkArray($value) {
        return (!is_array($value)) ? [$value] : $value;
    }

	/**
	 * Make the list of all file inside a DOM
	 * @param $type
	 * @param $values
	 * @return string
	 */
    private function dumpList($type, $values) {
        $dom = '';
        if (!empty($values)) {
            foreach($values as $value) {
                $path = $this->assetsPath . $type . '/' . $value;
                if ($type === 'css') {
                    $dom .= '<link rel="stylesheet" href="' . Url::To('assets', ['file' => $path]) . '">';
                } else {
                    $dom .= '<script src="' . Url::To('assets', ['file' => $path]) . '"></script>';
                }
            }
        }
        return $dom;
    }

}
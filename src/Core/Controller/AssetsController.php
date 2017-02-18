<?php
/**
 * @link http://www.foxframework.com/
 * @copyright Copyright (c) 2017 Daethe
 * @license http://www.foxframework.com/license/
 */

namespace Core\Controller;

/**
 * Class AssetsController
 * @package Core\Controller
 */
class AssetsController extends Controller {

	/**
	 * @param $file
	 */
    public function dump($file) {
        var_dump($file);
    }

}